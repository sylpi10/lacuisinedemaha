<?php

namespace App\Controller\Admin;

use App\Entity\FaqItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

/**
 * Pas de menu dédié : ce contrôleur ne sert qu'à fournir le formulaire
 * imbriqué utilisé par le champ "items" de FaqCrudController.
 */
class FaqItemCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return FaqItem::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new("question"),
            TextareaField::new("answer", "Réponse"),
            IntegerField::new("position")->setRequired(false),
        ];
    }
}
