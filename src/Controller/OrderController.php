<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Enum\OrderStatus; // Utilisation de ton Enum
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/order')]
class OrderController extends AbstractController
{
    #[Route('/add', name: 'order_add')]
    #[IsGranted('ROLE_USER')] // Empêche les non-connectés de commander
    public function add(SessionInterface $session, ProductRepository $productRepository, EntityManagerInterface $em): Response
    {
        // 1. On récupère le panier
        $panier = $session->get('panier', []);

        if (empty($panier)) {
            $this->addFlash('warning', 'Votre panier est vide !');
            return $this->redirectToRoute('cart_index');
        }

        // 2. On crée la commande
        $order = new Order();
        $order->setUser($this->getUser()); // L'utilisateur connecté
        $order->setCreatedAt(new \DateTimeImmutable());
        $order->setReference(uniqid('CMD-')); // Référence unique
        $order->setStatus(OrderStatus::en_attente->value); // Statut par défaut (via ton Enum)

        // 3. On parcourt le panier pour créer les OrderItems
        $total = 0;
        foreach ($panier as $id => $quantity) {
            $product = $productRepository->find($id);
            
            if (!$product) {
                continue;
            }

            $orderItem = new OrderItem();
            $orderItem->setProduct($product);
            $orderItem->setQuantity($quantity);
            $orderItem->setProductPrice($product->getPrice()); // On fige le prix au moment de l'achat
            $orderItem->setOrder($order); // On lie l'item à la commande

            $total += $product->getPrice() * $quantity;
            
            $em->persist($orderItem);
        }

        $order->setTotal($total);
        $em->persist($order);

        // 4. On enregistre tout en base de données
        $em->flush();

        // 5. On vide le panier
        $session->remove('panier');

        // 6. On redirige vers la page de succès
        return $this->redirectToRoute('order_success', ['reference' => $order->getReference()]);
    }

    #[Route('/success/{reference}', name: 'order_success')]
    public function success($reference): Response
    {
        return $this->render('order/success.html.twig', [
            'reference' => $reference
        ]);
    }
}