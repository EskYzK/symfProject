<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/order')]
class OrderController extends AbstractController
{
    #[Route('/add', name: 'order_add')]
    #[IsGranted('ROLE_USER')]
    public function add(SessionInterface $session, ProductRepository $productRepository, EntityManagerInterface $em): Response
    {
        $panier = $session->get('panier', []);

        if (empty($panier)) {
            $this->addFlash('warning', 'Votre panier est vide.');
            return $this->redirectToRoute('product_index');
        }

        $order = new Order();
        $order->setUser($this->getUser());
        $order->setReference(uniqid());
        $order->setCreatedAt(new \DateTimeImmutable());
        $order->setStatus('Payée');
        $total = 0;

        foreach ($panier as $id => $quantity) {
            $product = $productRepository->find($id);

            if ($product) {
                $orderItem = new OrderItem();
                $orderItem->setProduct($product);
                $orderItem->setQuantity($quantity);
                $orderItem->setProductPrice($product->getPrice());
                $orderItem->setOrder($order);
                
                $total += $product->getPrice() * $quantity;

                $em->persist($orderItem);
            }
        }

        $order->setTotal($total);

        $em->persist($order);
        $em->flush();

        $session->remove('panier');

        return $this->render('order/success.html.twig', [
            'order' => $order
        ]);
    }
}