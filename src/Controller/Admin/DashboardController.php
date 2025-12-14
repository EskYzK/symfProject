<?php

namespace App\Controller\Admin;

use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class DashboardController extends AbstractController
{
    #[Route('/', name: 'admin_dashboard')]
    public function index(ProductRepository $productRepo, OrderRepository $orderRepo): Response
    {
        $lastOrders = $orderRepo->findBy([], ['createdAt' => 'DESC'], 5);
        $allPaidOrders = $orderRepo->findBy(['status' => 'payee']);
        $totalSales = 0;
        foreach ($allPaidOrders as $order) {
            $totalSales += $order->getTotal();
        }

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