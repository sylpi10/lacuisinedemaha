<?php

namespace App\Controller;

use App\Repository\FormulasRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class FormulasController extends AbstractController
{
    #[Route("/formules", name: "site_formules")]
    public function index(FormulasRepository $formulasRepository): Response
    {
        return $this->render("formules.html.twig", [
            "formulas" => $formulasRepository->findAll(),
        ]);
    }
}
