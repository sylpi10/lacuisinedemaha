<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class SiteControllerTest extends WebTestCase
{
    public function testCheffeToulousePage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/cheffe-a-domicile-toulouse');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Cheffe à domicile à Toulouse et Pins-Justaret');
        self::assertPageTitleContains('Cheffe à domicile à Toulouse');
        self::assertSame(
            'https://lacuisinedemaha31.fr/cheffe-a-domicile-toulouse',
            $crawler->filter('link[rel="canonical"]')->attr('href'),
        );

        $areas = $crawler->filter('.zones-list li')->each(static fn ($li) => $li->text());
        foreach (['Pins-Justaret', 'Toulouse', 'Roques', 'Pinsaguel', 'Portet-sur-Garonne'] as $area) {
            self::assertContains($area, $areas);
        }

        $service = null;
        foreach ($crawler->filter('script[type="application/ld+json"]') as $script) {
            $data = json_decode($script->textContent, true, flags: \JSON_THROW_ON_ERROR);
            if ('Service' === $data['@type']) {
                $service = $data;
            }
        }
        self::assertNotNull($service);
        self::assertSame($areas, array_column($service['areaServed'], 'name'));
    }

    public function testCheffeToulousePageIsInSitemap(): void
    {
        $client = static::createClient();
        $client->request('GET', '/sitemap.xml');

        self::assertResponseIsSuccessful();
        self::assertStringContainsString(
            '<loc>https://lacuisinedemaha31.fr/cheffe-a-domicile-toulouse</loc>',
            $client->getResponse()->getContent(),
        );
    }
}
