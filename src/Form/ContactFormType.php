<?php

namespace App\Form;

use App\Entity\ContactForm;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Név', 'attr' => ['class' => 'form-control', 'placeholder' => 'Írja ide a nevét.']])
            ->add('email', EmailType::class, ['label' => 'E-mail cím', 'attr' => ['class' => 'form-control', 'placeholder' => 'Írja ide az e-mail címét']])
            ->add('message', TextareaType::class, ['label' => 'Üzenet', 'attr' => ['class' => 'form-control', 'rows' => '4', 'placeholder' => 'Kérjük írja ide üzenetét.']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ContactForm::class,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
