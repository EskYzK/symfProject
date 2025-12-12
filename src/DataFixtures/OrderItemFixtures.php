<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\Order;
use App\Entity\OrderItem;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class OrderItemFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 5; $i++) {
            for ($j = 1; $j <= mt_rand(1, 3); $j++) {
                $orderItem = new OrderItem();
                $orderItem->setProduct($this->getReference('product_' . mt_rand(0, 3), Product::class));    
                $orderItem->setOrder($this->getReference('order_' . $i, Order::class));
                $orderItem->setQuantity(mt_rand(1, 2));
                $orderItem->setProductPrice(19.99);
                $manager->persist($orderItem);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ProductFixtures::class,
            OrderFixtures::class,
        ];
    }
}
