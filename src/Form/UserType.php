<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, ['label' => 'profile.email'])
            ->add('firstname', TextType::class, ['label' => 'profile.firstname'])
            ->add('lastname', TextType::class, ['label' => 'profile.lastname'])
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'label' => 'profile.temppwd',
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer un mot de passe']),
                    new Length(['min' => 6, 'minMessage' => 'Au moins {{ limit }} caractères']),
                ],
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'profile.roles',
                'choices' => [
                    'profile.admin' => 'ROLE_ADMIN',
                    'profile.user' => 'ROLE_USER',
                ],
                'multiple' => true,
                'expanded' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}