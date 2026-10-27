<?php

namespace App\Tests\Service;

use App\Entity\ContactPage;
use App\Repository\ContactPageRepository;
use App\Service\SiteContact;
use PHPUnit\Framework\TestCase;

final class SiteContactTest extends TestCase
{
    public function testUsesEmailFromAdmin(): void
    {
        $siteContact = new SiteContact($this->repository(new ContactPage()->setEmail(' hello@example.com ')));

        self::assertSame('hello@example.com', $siteContact->getEmail());
    }

    public function testFallsBackWhenEmailIsEmptyOrMissing(): void
    {
        self::assertSame(SiteContact::DEFAULT_EMAIL, new SiteContact($this->repository(new ContactPage()->setEmail('')))->getEmail());
        self::assertSame(SiteContact::DEFAULT_EMAIL, new SiteContact($this->repository(null))->getEmail());
    }

    public function testQueriesOncePerRequest(): void
    {
        $repository = $this->createMock(ContactPageRepository::class);
        $repository->expects(self::exactly(2))->method('getContent')->willReturn(null);
        $siteContact = new SiteContact($repository);

        $siteContact->getEmail();
        $siteContact->getEmail();
        $siteContact->reset();
        $siteContact->getEmail();
    }

    private function repository(?ContactPage $page): ContactPageRepository
    {
        $repository = $this->createStub(ContactPageRepository::class);
        $repository->method('getContent')->willReturn($page);

        return $repository;
    }
}
