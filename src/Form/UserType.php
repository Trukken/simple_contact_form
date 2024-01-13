<?php

namespace App\Form;

use App\Entity\User;
use phpDocumentor\Reflection\Types\Collection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Email', EmailType::class, ['label' => 'E-mail cím', 'attr' => ['class' => 'form-control', 'placeholder' => 'azen@email@cim']])
            ->add('roles', ChoiceType::class, [
                'choices'  => [
                    'Felhasználó' => 'ROLE_USER',
                    'Admin' => 'ROLE_ADMIN',
                ],
                'label' => 'Jogok',
                'attr' => ['class' => 'form-control']
            ])
            ->add('Password', TextType::class, ['label' => 'Jelszó', 'attr' => ['class' => 'form-control']],)
        ;
        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesArray) {
                    return count($rolesArray)? $rolesArray[0]: null;
                },
                function ($rolesString) {
                    return [$rolesString];
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
