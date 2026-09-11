<?php

namespace App\Controller;

use App\Repository\FormulasRepository;
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
        PresentationRepository $presentationRepository,
        FormulasRepository $formulasRepository,
        ReviewsRepository $reviewsRepository,
    ): Response {
        return $this->render("home.html.twig", [
            "presentation" => $presentationRepository->getContent(),
            "formulas" => $formulasRepository->findAll(),
            "gallery" => $this->galeryRepository->getContent(),
            "reviews" => $reviewsRepository->findAll(),
        ]);
    }

    // #[Route("/formules", name: "site_formules")]
    // public function formules(): Response
    // {
    //     return $this->render("formules.html.twig");
    // }

    #[Route("/galerie", name: "site_galerie")]
    public function galerie(): Response
    {
        return $this->render("galerie.html.twig", [
            "gallery" => $this->galeryRepository->getContent(),
        ]);
    }

    #[Route("/contact", name: "site_contact")]
    public function contact(): Response
    {
        return $this->render("contact.html.twig");
    }
}
