<?php

namespace App\Controller\Admin;

use App\Entity\Formulas;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class FormulasCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Formulas::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setEntityLabelInSingular("Formule")
            ->setEntityLabelInPlural("Formules");
    }

    public function configureActions(Actions $actions): Actions
    {
        return parent::configureActions($actions)->update(
            Crud::PAGE_INDEX,
            Action::NEW,
            fn(Action $action) => $action->setLabel(
                "Ajouter une nouvelle formule",
            ),
        );
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new("id")->hideOnForm(),
            TextField::new("title")->setLabel("Titre"),
            TextareaField::new("description"),
            TextField::new("price")->setLabel("prix"),
            TextareaField::new("itemList")
                ->setHelp("Une ligne par élément inclus dans la formule.")
                ->setLabel("liste des plats"),
            ImageField::new("image")
                ->setBasePath("/uploads/formules")
                ->setUploadDir("public/uploads/formules")
                ->setUploadedFileNamePattern("[randomhash].[extension]")
                ->setRequired(false),
        ];
    }
}
