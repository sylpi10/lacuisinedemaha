<?php

namespace App\Controller\Admin;

use App\Entity\HomeConcept;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class HomeConceptCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return HomeConcept::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setEntityLabelInSingular("Concept (accueil)")
            ->setEntityLabelInPlural("Concept (accueil)");
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new("id")->hideOnForm(),
            TextField::new("subtitle", "Sous-titre (au-dessus du titre)")->setRequired(false),
            TextField::new("title", "Titre")->setRequired(false),
        ];
    }
}
