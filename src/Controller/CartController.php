<?php

namespace App\Controller;

use App\Repository\ProductRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
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
    public function add($id, SessionInterface $session, Request $request): Response
    {
        $panier = $session->get('panier', []);

        // On récupère la quantité du formulaire (1 par défaut si vide)
        // 'qty' sera le nom de notre input dans le HTML
        $quantity = $request->request->getInt('qty', 1);

        // Sécurité : on s'assure qu'on ajoute au moins 1 produit
        if ($quantity < 1) {
            $quantity = 1;
        }

        if (!empty($panier[$id])) {
            $panier[$id] += $quantity;
        } else {
            $panier[$id] = $quantity;
        }

        $session->set('panier', $panier);

        // Petit message flash dynamique
        $this->addFlash('success', 'Produit ajouté au panier (x' . $quantity . ') !');

        return $this->redirectToRoute('product_index');
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