<?php

namespace App\Controller\Admin;

use App\Entity\Presentation;
use App\Service\ImageWebpConverter;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Validator\Constraints\Image;

class PresentationCrudController extends AbstractCrudController
{
    public function __construct(
        private readonly ImageWebpConverter $webpConverter,
    ) {}

    public static function getEntityFqcn(): string
    {
        return Presentation::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setEntityLabelInSingular("Présentation")
            ->setEntityLabelInPlural("Présentation");
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new("id")->hideOnForm(),
            TextField::new("title")->setRequired(false),
            TextField::new("subtitle")->setRequired(false),
            TextareaField::new("description"),
            ImageField::new('image')
                ->setBasePath('/uploads/presentation')
                ->setUploadDir('public/uploads/presentation')
                ->setUploadedFileNamePattern('[randomhash].webp')
                ->setFormTypeOption('upload_new', $this->webpConverter->uploadCallback(800))
                ->setFileConstraints(new Image(mimeTypes: ImageWebpConverter::SUPPORTED_MIME_TYPES))
                ->mimeTypes(implode(',', ImageWebpConverter::SUPPORTED_MIME_TYPES))
                ->setRequired(false),
        ];
    }
}
