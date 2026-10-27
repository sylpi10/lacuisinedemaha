<?php

namespace App\Tests\Service;

use App\Entity\Contact;
use App\Service\ContactMailer;
use App\Service\SiteContact;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\Mailer\Exception\TransportException as MailerTransportException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Notifier\Bridge\Ntfy\NtfyOptions;
use Symfony\Component\Notifier\Exception\TransportException as NotifierTransportException;
use Symfony\Component\Notifier\Message\PushMessage;
use Symfony\Component\Notifier\TexterInterface;

final class ContactMailerTest extends KernelTestCase
{
    public function testSendsEmailWithoutAlert(): void
    {
        $mailer = $this->createMock(MailerInterface::class);
        $mailer->expects(self::once())->method('send')->with(self::callback(
            static fn (Email $email) => 'jeanne@example.com' === $email->getReplyTo()[0]->getAddress()
                && SiteContact::DEFAULT_EMAIL === $email->getTo()[0]->getAddress()
                && str_contains($email->getTextBody(), 'Nom : Jeanne Dupont'),
        ));
        $texter = $this->createMock(TexterInterface::class);
        $texter->expects(self::never())->method('send');

        $this->contactMailer($mailer, $texter)->send($this->contact());
    }

    public function testAlertsWithoutVisitorDataWhenEmailFails(): void
    {
        $mailer = $this->createStub(MailerInterface::class);
        $mailer->method('send')->willThrowException(new MailerTransportException('SMTP indisponible'));

        $sent = null;
        $texter = $this->createMock(TexterInterface::class);
        $texter->expects(self::once())->method('send')->willReturnCallback(
            static function (PushMessage $message) use (&$sent) {
                $sent = $message;

                return null;
            },
        );

        $this->contactMailer($mailer, $texter)->send($this->contact());

        self::assertInstanceOf(PushMessage::class, $sent);
        self::assertStringContainsString('Message n°42', $sent->getContent());
        self::assertStringContainsString('SMTP indisponible', $sent->getContent());
        self::assertInstanceOf(NtfyOptions::class, $sent->getOptions());
        self::assertSame(NtfyOptions::PRIORITY_HIGH, $sent->getOptions()->toArray()['priority']);
        foreach (['Jeanne', 'jeanne@example.com', '0601020304', 'Un dîner pour six'] as $visitorData) {
            self::assertStringNotContainsString($visitorData, $sent->getSubject().$sent->getContent());
        }
    }

    public function testAlertFailureDoesNotBreakTheVisitorRequest(): void
    {
        $mailer = $this->createStub(MailerInterface::class);
        $mailer->method('send')->willThrowException(new MailerTransportException('SMTP indisponible'));
        $texter = $this->createStub(TexterInterface::class);
        $texter->method('send')->willThrowException(new NotifierTransportException('ntfy injoignable', new MockResponse()));

        $this->contactMailer($mailer, $texter)->send($this->contact());

        $this->addToAssertionCount(1);
    }

    public function testNoAlertWhenTopicIsEmpty(): void
    {
        $mailer = $this->createStub(MailerInterface::class);
        $mailer->method('send')->willThrowException(new MailerTransportException('SMTP indisponible'));
        $texter = $this->createMock(TexterInterface::class);
        $texter->expects(self::never())->method('send');

        $container = static::getContainer();
        new ContactMailer($mailer, $texter, $container->get('logger'), $container->get(SiteContact::class), '')->send($this->contact());
    }

    private function contactMailer(MailerInterface $mailer, TexterInterface $texter): ContactMailer
    {
        $container = static::getContainer();
        $container->set(MailerInterface::class, $mailer);
        $container->set(TexterInterface::class, $texter);

        return $container->get(ContactMailer::class);
    }

    private function contact(): Contact
    {
        $contact = new Contact()
            ->setSenderName('Jeanne Dupont')
            ->setSenderEmail('jeanne@example.com')
            ->setSenderPhone('0601020304')
            ->setSenderMessage('Un dîner pour six');
        // l'id est normalement attribué par Doctrine au flush
        new \ReflectionProperty(Contact::class, 'id')->setValue($contact, 42);

        return $contact;
    }
}
