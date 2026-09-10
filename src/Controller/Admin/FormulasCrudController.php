<?php

namespace App\Controller\Admin;

use App\Entity\Formulas;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class FormulasCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Formulas::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title'),
            TextareaField::new('description'),
            TextField::new('price'),
            TextareaField::new('itemList')->setHelp('Une ligne par élément inclus dans la formule.'),
            AssociationField::new('image')->setRequired(false),
        ];
    }
}
