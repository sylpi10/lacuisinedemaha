<?php

namespace App\Service;

use App\Entity\Contact;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Notifier\Bridge\Ntfy\NtfyOptions;
use Symfony\Component\Notifier\Message\PushMessage;
use Symfony\Component\Notifier\TexterInterface;

final class ContactMailer
{
    private const ADDRESS = "contact@lacuisinedemaha31.fr";

    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly TexterInterface $texter,
        private readonly LoggerInterface $logger,
        #[Autowire(env: "NTFY_TOPIC")] private readonly string $ntfyTopic,
    ) {}

    /**
     * Le message doit déjà être enregistré en base : un échec d'envoi ne bloque
     * pas le visiteur, il est tracé et signalé pour pouvoir relancer depuis l'admin.
     */
    public function send(Contact $contact): void
    {
        $email = new Email()
            ->from(self::ADDRESS)
            ->replyTo($contact->getSenderEmail())
            ->to(self::ADDRESS)
            ->subject("Nouveau message depuis La Cuisine de Maha")
            ->text($this->buildEmailBody($contact));

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $exception) {
            $this->logger->error(
                "Échec de l'envoi de l'e-mail de contact : {$exception->getMessage()}",
            );
            $this->alertFailure($contact, $exception);
        }
    }

    /**
     * Alerte push (ntfy) quand le mail ne part pas : une alerte par mail serait perdue avec lui.
     * Pas de données du visiteur dans l'alerte, elle transite par un service tiers.
     * Désactivée tant que NTFY_TOPIC est vide.
     */
    private function alertFailure(Contact $contact, \Throwable $exception): void
    {
        if ($this->ntfyTopic === "") {
            return;
        }

        $message = new PushMessage(
            "La Cuisine de Maha : mail de contact non envoyé",
            \sprintf(
                "Message n°%d enregistré mais pas envoyé (consultable dans l'admin).\n%s",
                $contact->getId(),
                mb_substr($exception->getMessage(), 0, 300),
            ),
            new NtfyOptions()
                ->setPriority(NtfyOptions::PRIORITY_HIGH)
                ->addTag("warning"),
        );

        try {
            $this->texter->send($message);
        } catch (\Throwable $alertError) {
            $this->logger->error("Alerte ntfy impossible", [
                "exception" => $alertError,
            ]);
        }
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
