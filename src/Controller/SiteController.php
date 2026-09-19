<?php

namespace App\Controller;

use App\Repository\FormulasRepository;
use App\Repository\HomeHeroRepository;
use App\Repository\GaleryRepository;
use App\Repository\PresentationRepository;
use App\Repository\ReviewsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SiteController extends AbstractController
{
    public function __construct(protected GaleryRepository $galeryRepository) {}

    #[Route("/", name: "site_home")]
    public function home(
        HomeHeroRepository $homeHeroRepository,
        PresentationRepository $presentationRepository,
        FormulasRepository $formulasRepository,
        ReviewsRepository $reviewsRepository,
    ): Response {
        return $this->render("home.html.twig", [
            "hero" => $homeHeroRepository->getContent(),
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
}
