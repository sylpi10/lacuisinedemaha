<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SiteController extends AbstractController
{
    #[Route('/', name: 'site_home')]
    public function home(): Response { return $this->render('home.html.twig'); }

    #[Route('/formules', name: 'site_formules')]
    public function formules(): Response { return $this->render('formules.html.twig'); }

    #[Route('/galerie', name: 'site_galerie')]
    public function galerie(): Response { return $this->render('galerie.html.twig'); }

    #[Route('/contact', name: 'site_contact')]
    public function contact(): Response { return $this->render('contact.html.twig'); }
}
