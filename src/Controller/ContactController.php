<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactPageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

final class ContactController extends AbstractController
{
    private const SESSION_TOKEN_KEY = "contact_form_token";
    private const SESSION_ISSUED_AT_KEY = "contact_form_issued_at";

    // en dessous, un envoi est trop rapide pour être humain ; au-dessus, le
    // formulaire est resté ouvert trop longtemps et on préfère le regénérer
    private const MIN_FILL_SECONDS = 4;
    private const MAX_FORM_AGE_SECONDS = 86400;

    #[Route("/contact", name: "site_contact")]
    public function contact(
        Request $request,
        EntityManagerInterface $entityManager,
        MailerInterface $mailer,
        LoggerInterface $logger,
        ContactPageRepository $contactPageRepository,
    ): Response {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$this->passesAntiSpamGuards($request)) {
                // on ne donne aucune indication à un robot : il voit le même
                // écran de succès qu'un envoi légitime, sans que rien ne soit
                // enregistré ni envoyé.
                return $this->redirectToRoute("site_contact");
            }

            $entityManager->persist($contact);
            $entityManager->flush();

            $email = new Email()
                ->from("contact@lacuisinedemaha31.fr")
                ->replyTo($contact->getSenderEmail())
                ->to("contact@lacuisinedemaha31.fr")
                ->subject("Nouveau message depuis La Cuisine de Maha")
                ->text($this->buildEmailBody($contact));

            try {
                $mailer->send($email);
            } catch (TransportExceptionInterface $exception) {
                // le message est déjà enregistré en base : on ne bloque pas le visiteur
                // pour un souci d'envoi d'e-mail, mais on le trace pour pouvoir relancer.
                $logger->error(
                    "Échec de l'envoi de l'e-mail de contact : {$exception->getMessage()}",
                );
            }

            $this->addFlash(
                "success",
                "Votre demande a bien été envoyée, je reviens vers vous rapidement !",
            );

            return $this->redirectToRoute("site_contact");
        }

        [$contactToken, $contactIssuedAt] = $this->issueAntiSpamGuards($request);

        return $this->render("contact.html.twig", [
            "page" => $contactPageRepository->getContent(),
            "contactForm" => $form,
            "contact_token" => $contactToken,
            "contact_issued_at" => $contactIssuedAt,
        ]);
    }

    /**
     * Émet un nouveau couple jeton/horodatage pour le formulaire qui va être
     * affiché, et le garde en session pour pouvoir le vérifier à la soumission.
     *
     * @return array{0: string, 1: int}
     */
    private function issueAntiSpamGuards(Request $request): array
    {
        $token = bin2hex(random_bytes(16));
        $issuedAt = time();

        $session = $request->getSession();
        $session->set(self::SESSION_TOKEN_KEY, $token);
        $session->set(self::SESSION_ISSUED_AT_KEY, $issuedAt);

        return [$token, $issuedAt];
    }

    /**
     * Trois vérifications indépendantes, chacune suffisante pour rejeter :
     *  - un champ piège invisible pour un humain, mais que les robots
     *    remplissent souvent en soumettant tous les champs du formulaire ;
     *  - un jeton propre à cette session, pour rejeter les soumissions
     *    directes qui n'ont jamais chargé le formulaire ;
     *  - un délai minimum/maximum, comparé à l'horodatage stocké en session
     *    (jamais à une valeur envoyée par le client, qui serait falsifiable).
     */
    private function passesAntiSpamGuards(Request $request): bool
    {
        $honeypot = trim((string) $request->request->get("website", ""));
        if ("" !== $honeypot) {
            return false;
        }

        $session = $request->getSession();

        $submittedToken = (string) $request->request->get("contact_token", "");
        $sessionToken = (string) $session->get(self::SESSION_TOKEN_KEY, "");
        if ("" === $sessionToken || !hash_equals($sessionToken, $submittedToken)) {
            return false;
        }

        $issuedAt = (int) $session->get(self::SESSION_ISSUED_AT_KEY, 0);
        if ($issuedAt <= 0) {
            return false;
        }

        $elapsed = time() - $issuedAt;

        return $elapsed >= self::MIN_FILL_SECONDS && $elapsed <= self::MAX_FORM_AGE_SECONDS;
    }

    private function buildEmailBody(Contact $contact): string
    {
        $lines = [
            "Nom : {$contact->getSenderName()}",
            "E-mail : {$contact->getSenderEmail()}",
            "Téléphone : " . ($contact->getSenderPhone() ?? "non renseigné"),
            "Formule souhaitée : " .
            ($contact->getWishedFormula()?->getTitle() ?? "ne sait pas encore"),
            "Date envisagée : " .
            ($contact->getSenderDate()?->format("d/m/Y") ?? "non renseignée"),
            "Nombre de convives : " .
            ($contact->getSenderWishedNumber() ?? "non renseigné"),
            "",
            "Message :",
            $contact->getSenderMessage(),
        ];

        return implode("\n", $lines);
    }
}
