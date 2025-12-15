<?php

namespace App\Twig\Components;

use App\Entity\CreditCard;
use App\Form\CreditCardType;
use App\Repository\CreditCardRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
class WalletForm extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp]
    public ?CreditCard $initialFormData = null;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private CreditCardRepository $creditCardRepository
    ) {
    }

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(CreditCardType::class, new CreditCard());
    }

    public function getCards(): array
    {
        return $this->creditCardRepository->findBy(['user' => $this->getUser()]);
    }

    #[LiveAction]
    public function save()
    {
        $this->submitForm();

        /** @var CreditCard $card */
        $card = $this->getForm()->getData();
        
        $card->setUser($this->getUser());

        $this->entityManager->persist($card);
        $this->entityManager->flush();

        $this->addFlash('success', 'Carte ajoutée avec succès !');
        
        $this->resetForm();
    }

    #[LiveAction]
    public function delete(#[LiveArg] int $card): void
    {
        $cardToDelete = $this->creditCardRepository->find($card);

        if (!$cardToDelete) {
            $this->addFlash('error', 'Carte non trouvée.');
            return;
        }
        
        if ($cardToDelete->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $this->entityManager->remove($cardToDelete);
        $this->entityManager->flush();

        $this->addFlash('success', 'Carte supprimée avec succès !');
    }
}
