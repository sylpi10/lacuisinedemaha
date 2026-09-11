<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
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
    #[Route("/contact", name: "site_contact")]
    public function contact(
        Request $request,
        EntityManagerInterface $entityManager,
        MailerInterface $mailer,
        LoggerInterface $logger,
    ): Response {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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

        return $this->render("contact.html.twig", [
            "contactForm" => $form,
        ]);
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
