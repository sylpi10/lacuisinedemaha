<?php

namespace App\Controller\Admin;

use App\Entity\GaleryImage;
use App\Service\ImageWebpConverter;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use Symfony\Component\Validator\Constraints\Image;

/**
 * Pas de menu dédié : ce contrôleur ne sert qu'à fournir le formulaire
 * imbriqué utilisé par le champ "images" de GaleryCrudController.
 */
class GaleryImageCrudController extends AbstractCrudController
{
    public function __construct(private readonly ImageWebpConverter $webpConverter)
    {
    }

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
                // 490x490 = 2x la colonne de la grille en desktop, couvre aussi le 2 colonnes mobile
                ->setUploadedFileNamePattern('[randomhash].webp')
                ->setFormTypeOption('upload_new', $this->webpConverter->uploadCallback(490, 490))
                ->setFileConstraints(new Image(mimeTypes: ImageWebpConverter::SUPPORTED_MIME_TYPES))
                ->mimeTypes(implode(',', ImageWebpConverter::SUPPORTED_MIME_TYPES))
                ->setHelp('Recadrée en carré et convertie en webp automatiquement.'),
            IntegerField::new('position')->setRequired(false),
        ];
    }
}
