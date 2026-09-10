<?php

namespace App\Controller\Admin;

use App\Entity\Image;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ImageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Image::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('filename'),
            TextField::new('path')->setHelp('Chemin relatif dans public/, ex. images/galerie/photo-01.jpg'),
            TextField::new('altText')->setRequired(false),
            IntegerField::new('position')->setRequired(false)->setHelp('Ordre d\'affichage dans la galerie.'),
            AssociationField::new('gallery')->setRequired(false),
            DateTimeField::new('createdAt')->hideOnForm(),
        ];
    }
}
