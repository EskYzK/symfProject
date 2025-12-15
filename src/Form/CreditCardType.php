<?php

namespace App\Form;

use App\Entity\CreditCard;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreditCardType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('number', TextType::class, [
                'label' => 'creditcard.num',
                'attr' => ['placeholder' => '0000 0000 0000 0000']
            ])
            ->add('expirationDate', TextType::class, [
                'label' => 'creditcard.exp',
                'attr' => ['placeholder' => '12/25']
            ])
            ->add('cvv', TextType::class, [
                'label' => 'creditcard.cvv',
                'attr' => ['placeholder' => '123', 'maxlength' => 3]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CreditCard::class,
        ]);
    }
}