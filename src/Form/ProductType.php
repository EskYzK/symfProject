<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use App\Enum\ProductStatus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'formproduct.productname'
            ])
            ->add('price', MoneyType::class, [
                'label' => 'formproduct.price',
                'currency' => 'EUR'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'formproduct.desc'
            ])
            ->add('stock', IntegerType::class, [
                'label' => 'formproduct.stock'
            ])
            ->add('status', EnumType::class, [
                'class' => ProductStatus::class,
                'label' => 'formproduct.status',
                'choice_label' => fn ($choice) => match ($choice) {
                    ProductStatus::AVAILABLE => 'formproduct.avai',
                    ProductStatus::OUT_OF_STOCK => 'formproduct.oos',
                    ProductStatus::PRE_ORDER => 'formproduct.preorder',
                    default => $choice->name,
                },
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'label' => 'formproduct.category'
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'formproduct.image',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/webp',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger une image valide (JPG, PNG, WEBP)',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}