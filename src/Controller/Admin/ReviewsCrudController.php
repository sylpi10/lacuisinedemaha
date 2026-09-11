<?php

namespace App\Controller\Admin;

use App\Entity\Reviews;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ReviewsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Reviews::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)->setEntityLabelInSingular(
            "un avis",
        );
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new("id")->hideOnForm(),
            TextareaField::new("text")->setLabel("Contenu"),
            TextField::new("reviewer")->setLabel("Auteur"),
            TextField::new("city")->setLabel("Ville de l'auteur"),
        ];
    }
}
