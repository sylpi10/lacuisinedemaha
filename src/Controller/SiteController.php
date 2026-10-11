<?php

namespace App\Controller;

use App\Entity\LegalPage;
use App\Repository\FormulasRepository;
use App\Repository\HomeConceptRepository;
use App\Repository\HomeHeroRepository;
use App\Repository\LegalPageRepository;
use App\Repository\GaleryRepository;
use App\Repository\PresentationRepository;
use App\Repository\ReviewsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SiteController extends AbstractController
{
    /** Communes citées sur la page de référencement local (liste + données structurées). */
    private const LOCAL_AREAS = [
        "Pins-Justaret",
        "Toulouse",
        "Roques",
        "Pinsaguel",
        "Portet-sur-Garonne",
        "Villate",
        "Saubens",
        "Roquettes",
        "Labarthe-sur-Lèze",
        "Muret",
        "Lacroix-Falgarde",
        "Cugnaux",
    ];

    public function __construct(protected GaleryRepository $galeryRepository) {}

    #[Route("/", name: "site_home")]
    public function home(
        HomeHeroRepository $homeHeroRepository,
        HomeConceptRepository $homeConceptRepository,
        PresentationRepository $presentationRepository,
        FormulasRepository $formulasRepository,
        ReviewsRepository $reviewsRepository,
    ): Response {
        return $this->render("home.html.twig", [
            "hero" => $homeHeroRepository->getContent(),
            "concept" => $homeConceptRepository->getContent(),
            "presentation" => $presentationRepository->getContent(),
            "formulas" => $formulasRepository->findAll(),
            "gallery" => $this->galeryRepository->getContent(),
            "reviews" => $reviewsRepository->findAll(),
        ]);
    }

    #[Route("/galerie", name: "site_galerie")]
    public function galerie(): Response
    {
        return $this->render("galerie.html.twig", [
            "gallery" => $this->galeryRepository->getContent(),
        ]);
    }

    #[Route("/cheffe-a-domicile-toulouse", name: "site_cheffe_toulouse")]
    public function cheffeToulouse(
        FormulasRepository $formulasRepository,
        ReviewsRepository $reviewsRepository,
    ): Response {
        return $this->render("cheffe_toulouse.html.twig", [
            "areas" => self::LOCAL_AREAS,
            "formulas" => $formulasRepository->findAll(),
            "gallery" => $this->galeryRepository->getContent(),
            "reviews" => $reviewsRepository->findAll(),
        ]);
    }

    #[Route("/mentions-legales", name: "site_mentions_legales")]
    public function mentionsLegales(LegalPageRepository $legalPageRepository): Response
    {
        return $this->renderLegalPage($legalPageRepository, LegalPage::MENTIONS_LEGALES);
    }

    #[Route("/cgv", name: "site_cgv")]
    public function cgv(LegalPageRepository $legalPageRepository): Response
    {
        return $this->renderLegalPage($legalPageRepository, LegalPage::CGV);
    }

    private function renderLegalPage(LegalPageRepository $legalPageRepository, string $slug): Response
    {
        $page = $legalPageRepository->findOneBySlug($slug)
            ?? throw $this->createNotFoundException();

        return $this->render("legal_page.html.twig", ["page" => $page]);
    }
}
