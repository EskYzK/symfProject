<?php

namespace App\Controller\Admin;

use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')] // Sécurité : Seul l'admin rentre ici !
class DashboardController extends AbstractController
{
    #[Route('/', name: 'admin_dashboard')]
    public function index(ProductRepository $productRepo, OrderRepository $orderRepo): Response
    {
        // 1. Les 5 dernières commandes
        $lastOrders = $orderRepo->findBy([], ['createdAt' => 'DESC'], 5);

        // 2. Calcul du Chiffre d'affaires total (Commandes payées uniquement)
        // On le fait en PHP simple pour l'instant (on pourra optimiser en SQL plus tard)
        $allPaidOrders = $orderRepo->findBy(['status' => 'payee']);
        $totalSales = 0;
        foreach ($allPaidOrders as $order) {
            $totalSales += $order->getTotal();
        }

        // 3. Stats des produits (Stock vs Rupture)
        $products = $productRepo->findAll();
        $inStock = 0;
        $outOfStock = 0;
        
        foreach ($products as $product) {
            if ($product->getStock() > 0) {
                $inStock++;
            } else {
                $outOfStock++;
            }
        }

        return $this->render('admin/dashboard/index.html.twig', [
            'lastOrders' => $lastOrders,
            'totalSales' => $totalSales,
            'countProducts' => count($products),
            'inStock' => $inStock,
            'outOfStock' => $outOfStock
        ]);
    }
}