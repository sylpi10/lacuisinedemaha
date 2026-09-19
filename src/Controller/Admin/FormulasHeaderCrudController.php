<?php

namespace App\Controller\Admin;

use App\Entity\FormulasHeader;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class FormulasHeaderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return FormulasHeader::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setEntityLabelInSingular("En-tête des formules")
            ->setEntityLabelInPlural("En-tête des formules");
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new("id")->hideOnForm(),
            TextField::new("subtitle", "Sous-titre (au-dessus du titre)")->setRequired(false),
            TextField::new("title", "Titre")->setRequired(false),
            TextareaField::new("description")->setRequired(false),
        ];
    }
}
