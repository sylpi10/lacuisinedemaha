<?php

namespace App\Controller\Admin;

use App\Entity\Galery;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class GaleryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Galery::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title')->setRequired(false),
            TextareaField::new('description')->setRequired(false),
            // les photos se rattachent à la galerie depuis l'écran "Photos"
            AssociationField::new('images')->hideOnForm(),
        ];
    }
}
