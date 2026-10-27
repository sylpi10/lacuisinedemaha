<?php

namespace App\Tests\Entity;

use App\Entity\Contact;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class ContactValidationTest extends KernelTestCase
{
    public function testRejectsPastDate(): void
    {
        $violations = $this->validate(new \DateTime('yesterday'));

        self::assertCount(1, $violations);
        self::assertSame('senderDate', $violations[0]->getPropertyPath());
        self::assertSame('Merci de choisir une date à venir.', $violations[0]->getMessage());
    }

    public function testAcceptsTodayFutureOrNoDate(): void
    {
        self::assertCount(0, $this->validate(new \DateTime('today')));
        self::assertCount(0, $this->validate(new \DateTime('+3 months')));
        self::assertCount(0, $this->validate(null));
    }

    private function validate(?\DateTime $date): iterable
    {
        $contact = new Contact()
            ->setSenderName('Jeanne Dupont')
            ->setSenderEmail('jeanne@example.com')
            ->setSenderMessage('Un dîner pour six')
            ->setSenderDate($date);

        return static::getContainer()->get(ValidatorInterface::class)->validate($contact);
    }
}
