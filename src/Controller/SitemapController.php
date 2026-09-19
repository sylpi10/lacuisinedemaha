<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SitemapController extends AbstractController
{
    private const PAGES = [
        "site_home" => "1.0",
        "site_formules" => "0.8",
        "site_galerie" => "0.6",
        "site_contact" => "0.6",
    ];

    #[Route("/sitemap.xml", name: "site_sitemap", format: "xml")]
    public function sitemap(): Response
    {
        $urls = [];
        foreach (self::PAGES as $route => $priority) {
            $urls[] = [
                "path" => $this->generateUrl($route),
                "priority" => $priority,
            ];
        }

        $response = $this->render("sitemap.xml.twig", ["urls" => $urls]);
        $response->headers->set("Content-Type", "application/xml");

        return $response;
    }
}
