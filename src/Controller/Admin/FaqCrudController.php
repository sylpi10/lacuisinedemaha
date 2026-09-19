<?php

namespace App\Controller\Admin;

use App\Entity\Faq;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class FaqCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Faq::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setEntityLabelInSingular("FAQ")
            ->setEntityLabelInPlural("FAQ");
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new("id")->hideOnForm(),
            TextField::new("subtitle", "Sous-titre (au-dessus du titre)")->setRequired(false),
            TextField::new("title", "Titre")->setRequired(false),
            CollectionField::new("items", "Questions")
                ->useEntryCrudForm(FaqItemCrudController::class)
                ->setEntryIsComplex()
                ->allowAdd()
                ->allowDelete(),
        ];
    }
}
