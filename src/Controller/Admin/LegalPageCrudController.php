<?php

namespace App\Controller\Admin;

use App\Entity\LegalPage;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class LegalPageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return LegalPage::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setEntityLabelInSingular("Page légale")
            ->setEntityLabelInPlural("Pages légales");
    }

    public function configureActions(Actions $actions): Actions
    {
        // les pages sont liées à des routes : on ne fait que les modifier
        return $actions->disable(Action::NEW, Action::DELETE, Action::BATCH_DELETE);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new("title", "Titre"),
            TextField::new("slug", "Adresse")->hideOnForm(),
            TextField::new("subtitle", "Sous-titre (au-dessus du titre)")->setRequired(false)->hideOnIndex(),
            TextField::new("metaDescription", "Description (moteurs de recherche)")->setRequired(false)->hideOnIndex(),
            TextEditorField::new("content", "Contenu")
                ->hideOnIndex()
                ->setNumOfRows(30)
                // le bouton « titre » de Trix produit des <h2> (le <h1> est le titre de la page)
                ->setTrixEditorConfig([
                    "blockAttributes" => [
                        "heading1" => ["tagName" => "h2"],
                    ],
                ]),
        ];
    }
}
