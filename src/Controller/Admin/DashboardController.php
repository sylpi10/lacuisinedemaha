<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use App\Entity\Formulas;
use App\Entity\Galery;
use App\Entity\Image;
use App\Entity\Presentation;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // return $this->redirectToRoute('admin_user_index');

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirectToRoute('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('La cuisine de Maha');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Présentation', 'fa fa-align-left', Presentation::class);
        yield MenuItem::linkToCrud('Formules', 'fa fa-utensils', Formulas::class);
        yield MenuItem::linkToCrud('Galerie', 'fa fa-images', Galery::class);
        yield MenuItem::linkToCrud('Photos', 'fa fa-image', Image::class);
        yield MenuItem::linkToCrud('Demandes de contact', 'fa fa-envelope', Contact::class);
        yield MenuItem::linkToCrud('Utilisateurs', 'fa fa-user-shield', User::class);
    }
}
