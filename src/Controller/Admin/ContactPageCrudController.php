<?php

namespace App\Controller\Admin;

use App\Entity\ContactPage;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ContactPageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ContactPage::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setEntityLabelInSingular("Page contact")
            ->setEntityLabelInPlural("Page contact");
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new("id")->hideOnForm(),
            TextField::new("subtitle", "Sous-titre (au-dessus du titre)")->setRequired(false),
            TextField::new("title", "Titre")->setRequired(false),
            TextareaField::new("description")->setRequired(false),
            TextField::new("email", "E-mail")->setRequired(false),
            TextField::new("phone", "Téléphone")->setRequired(false),
            TextField::new("serviceArea", "Zone d'intervention")->setRequired(false),
        ];
    }
}
