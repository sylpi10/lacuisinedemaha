<?php

namespace App\Form;

use App\Entity\Contact;
use App\Entity\Formulas;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('senderName', TextType::class, [
                'label' => 'Nom et prénom',
            ])
            ->add('senderEmail', EmailType::class, [
                'label' => 'E-mail',
            ])
            ->add('senderPhone', TelType::class, [
                'label' => 'Téléphone',
                'required' => false,
            ])
            ->add('wishedFormula', EntityType::class, [
                'class' => Formulas::class,
                'choice_label' => 'title',
                'label' => 'Formule souhaitée',
                'required' => false,
                'placeholder' => 'Je ne sais pas encore',
            ])
            ->add('senderDate', DateType::class, [
                'label' => 'Date envisagée',
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('senderWishedNumber', IntegerType::class, [
                'label' => 'Nombre de convives',
                'required' => false,
            ])
            ->add('senderMessage', TextareaType::class, [
                'label' => 'Votre message',
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label' => "J'accepte que ces informations soient utilisées pour traiter ma demande.",
                'constraints' => [
                    new IsTrue(message: "Merci d'accepter que vos informations soient utilisées pour traiter votre demande."),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
