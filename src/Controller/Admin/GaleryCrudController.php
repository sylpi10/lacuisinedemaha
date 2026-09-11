<?php

namespace App\Controller\Admin;

use App\Entity\Galery;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Asset;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class GaleryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Galery::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setEntityLabelInSingular('Galerie')
            ->setEntityLabelInPlural('Galeries');
    }

    public function configureAssets(Assets $assets): Assets
    {
        // le champ "images" utilise le widget d'upload d'ImageField via un formulaire imbriqué
        // (GaleryImageCrudController) : EasyAdmin ne charge pas automatiquement le JS de ce
        // widget dans ce cas, il faut le déclarer explicitement pour que le bouton "Ajouter
        // un fichier" fonctionne dans chaque ligne de la collection.
        return parent::configureAssets($assets)
            ->addJsFile(Asset::fromEasyAdminAssetPackage('field-image.js'))
            ->addJsFile(Asset::fromEasyAdminAssetPackage('field-file-upload.js'));
    }

    public function configureActions(Actions $actions): Actions
    {
        return parent::configureActions($actions)
            ->update(Crud::PAGE_INDEX, Action::NEW, fn (Action $action) => $action->setLabel('Ajouter une galerie'));
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('homepageName', "Nom (page d'accueil)")->setRequired(false),
            TextField::new('homepageTitle', "Titre (page d'accueil)")->setRequired(false),
            TextField::new('galleryName', 'Nom (page galerie)')->setRequired(false),
            TextField::new('galleryTitle', 'Titre (page galerie)')->setRequired(false),
            TextareaField::new('description')->setRequired(false),
            CollectionField::new('images', 'Photos')
                ->useEntryCrudForm(GaleryImageCrudController::class)
                ->setEntryIsComplex()
                ->allowAdd()
                ->allowDelete(),
        ];
    }
}
