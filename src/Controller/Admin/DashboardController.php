<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: "/admin", routeName: "admin")]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        return $this->redirectToRoute("admin_presentation_index");
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle("La cuisine de Maha")
            ->setLocales(["fr"]);
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard("Dashboard", "fa fa-home");
        yield MenuItem::linkTo(
            PresentationCrudController::class,
            "Présentation",
            "fa fa-align-left",
        );
        yield MenuItem::linkTo(
            FormulasCrudController::class,
            "Formules",
            "fa fa-utensils",
        );
        yield MenuItem::linkTo(
            GaleryCrudController::class,
            "Galerie",
            "fa fa-images",
        );
        yield MenuItem::linkTo(
            ContactCrudController::class,
            "Demandes de contact",
            "fa fa-envelope",
        );
        yield MenuItem::linkTo(
            UserCrudController::class,
            "Utilisateurs",
            "fa fa-user-shield",
        );
    }
}
