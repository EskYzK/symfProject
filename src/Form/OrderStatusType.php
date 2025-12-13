<?php

namespace App\Form;

use App\Entity\Order;
use App\Enum\OrderStatus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderStatusType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('status', ChoiceType::class, [
                'label' => 'Statut de la commande',
                'choices' => [
                    'Payée' => 'payee',
                    'En préparation' => 'preparation',
                    'Expédiée' => 'expediee',
                    'Livrée' => 'livree',
                    'Annulée' => 'annulee',
                ],
                'expanded' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}