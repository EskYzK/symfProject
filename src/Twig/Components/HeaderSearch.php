<?php

namespace App\Twig\Components;

use App\Entity\Product;
use App\Form\ProductAutocompleteField;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;

#[AsLiveComponent]
class HeaderSearch extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp]
    public ?Product $product = null;

    protected function instantiateForm(): FormInterface
    {
        return $this->createFormBuilder()
            ->add('product', ProductAutocompleteField::class, [
                'label' => 'Rechercher un produit (Live)',
                'attr' => ['class' => 'form-control'],
            ])
            ->getForm();
    }
}