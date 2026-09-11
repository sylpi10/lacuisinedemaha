<?php

namespace App\Controller\Admin;

use App\Entity\GaleryImage;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

/**
 * Pas de menu dédié : ce contrôleur ne sert qu'à fournir le formulaire
 * imbriqué utilisé par le champ "images" de GaleryCrudController.
 */
class GaleryImageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return GaleryImage::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            ImageField::new('image', 'Photo')
                ->setBasePath('/uploads/galerie')
                ->setUploadDir('public/uploads/galerie')
                ->setUploadedFileNamePattern('[randomhash].[extension]'),
            IntegerField::new('position')->setRequired(false),
        ];
    }
}
