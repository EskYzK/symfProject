<?php

namespace App\DataFixtures;

use App\Entity\Order;
use App\Entity\User;
use App\Enum\OrderStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class OrderFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 5; $i++) {
            $order = new Order();
            
            $order->setTotal(mt_rand(100, 1000));
            
            $order->setReference(uniqid('REF_'));
            $order->setCreatedAt(new \DateTimeImmutable());
            
            $statuses = OrderStatus::cases();
            $order->setStatus($statuses[array_rand($statuses)]->value);

            $order->setUser($this->getReference('user_' . $i, User::class));
            
            $manager->persist($order);
            $this->addReference('order_' . $i, $order);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}