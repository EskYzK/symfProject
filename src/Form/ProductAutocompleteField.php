<?php

namespace App\Form;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\ParentEntityAutocompleteType;

#[AsEntityAutocompleteField]
class ProductAutocompleteField extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'class' => Product::class,
            'placeholder' => 'search.bar' ,
            'choice_label' => 'name',
            'query_builder' => function (ProductRepository $productRepository) {
                return $productRepository->createQueryBuilder('p')
                    ->orderBy('p.name', 'ASC');
            },
            'searchable_fields' => ['name'],
            'security' => 'PUBLIC_ACCESS',
        ]);
    }

    public function getParent(): string
    {
        return ParentEntityAutocompleteType::class;
    }
}