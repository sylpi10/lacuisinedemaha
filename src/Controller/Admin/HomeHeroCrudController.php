<?php

namespace App\Controller\Admin;

use App\Entity\HomeHero;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class HomeHeroCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return HomeHero::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setEntityLabelInSingular("Bandeau d'accueil")
            ->setEntityLabelInPlural("Bandeau d'accueil");
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new("id")->hideOnForm(),
            TextField::new("subtitle", "Sous-titre (au-dessus du titre)")->setRequired(false),
            TextField::new("title", "Titre")->setRequired(false),
            TextareaField::new("description")->setRequired(false),
            TextField::new("primaryButtonLabel", "Bouton principal (vers Contact)")->setRequired(false),
            TextField::new("secondaryButtonLabel", "Bouton secondaire (vers Formules)")->setRequired(false),
        ];
    }
}
