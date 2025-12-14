<?php

namespace App\Controller;

use App\Entity\Product;
use App\Enum\ProductStatus;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/cart')]
class CartController extends AbstractController
{
    #[Route('/', name: 'cart_index')]
    public function index(SessionInterface $session, ProductRepository $productRepository): Response
    {
        $panier = $session->get('panier', []);
        $panierWithData = [];
        $total = 0;

        foreach ($panier as $id => $quantity) {
            $product = $productRepository->find($id);
            if ($product) {
                $panierWithData[] = [
                    'product' => $product,
                    'quantity' => $quantity
                ];
                $total += $product->getPrice() * $quantity;
            }
        }

        return $this->render('cart/index.html.twig', [
            'items' => $panierWithData,
            'total' => $total
        ]);
    }

    #[Route('/add/{id}', name: 'cart_add')]
    #[IsGranted('ROLE_USER')]
    public function add($id, SessionInterface $session, Request $request, ProductRepository $productRepository): Response
    {
        $product = $productRepository->find($id);

        if (!$product) {
            $this->addFlash('danger', 'Produit introuvable.');
            return $this->redirectToRoute('product_index');
        }

        if ($product->getStatus() === ProductStatus::OUT_OF_STOCK) {
            $this->addFlash('danger', 'Impossible d\'ajouter ce produit : Rupture de stock !');
            return $this->redirectToRoute('product_index');
        }

        $panier = $session->get('panier', []);
        $quantity = $request->request->getInt('qty', 1);
        $quantityInCart = $panier[$id] ?? 0;

        

        if (!empty($panier[$id])) {
            $panier[$id] += $quantity;
        } else {
            $panier[$id] = $quantity;
        }

        $session->set('panier', $panier);
        
        $referer = $request->headers->get('referer');
        if ($referer && str_contains($referer, '/cart')) {
            return $this->redirectToRoute('cart_index');
        }

        $this->addFlash('success', 'Produit ajouté au panier !');
        return $this->redirectToRoute('product_index');
    }

    #[Route('/update/{id}', name: 'cart_update')]
    #[IsGranted('ROLE_USER')]
    public function update($id, SessionInterface $session, Request $request, ProductRepository $productRepository): Response
    {
        $panier = $session->get('panier', []);
        $quantity = $request->request->getInt('qty');
        
        $product = $productRepository->find($id);

        if ($product) {
            if ($quantity > $product->getStock()) {
                $this->addFlash('warning', sprintf(
                    'Désolé, vous ne pouvez pas commander plus de %d exemplaires de "%s".',
                    $product->getStock(),
                    $product->getName()
                ));
                $quantity = $product->getStock();
            }
        }
        if ($quantity > 0) {
            $panier[$id] = $quantity;
        } else {
            unset($panier[$id]);
        }

        $session->set('panier', $panier);

        return $this->redirectToRoute('cart_index');
    }

    #[Route('/remove/{id}', name: 'cart_remove')]
    public function remove($id, SessionInterface $session): Response
    {
        $panier = $session->get('panier', []);

        if (!empty($panier[$id])) {
            unset($panier[$id]);
        }

        $session->set('panier', $panier);

        return $this->redirectToRoute('cart_index');
    }
}