<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\Category;
use App\Enum\ProductStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $products = [
            [
                'name' => 'The Last of Us Part II',
                'description' => 'Un jeu d\'action-aventure se déroulant dans un monde post-apocalyptique.',
                'price' => 59.99,
                'category' => 'category_1'
            ],
            [
                'name' => 'Cyberpunk 2077',
                'description' => 'Un RPG d\'action en monde ouvert se déroulant dans la mégalopole de Night City.',
                'price' => 49.99,
                'category' => 'category_2'
            ],
            [
                'name' => 'Red Dead Redemption 2',
                'description' => 'Un jeu d\'action-aventure en monde ouvert se déroulant à l\'époque du Far West.',
                'price' => 39.99,
                'category' => 'category_6'
            ],
            [
                'name' => 'The Witcher 3: Wild Hunt',
                'description' => 'Un RPG en monde ouvert avec une histoire riche et des personnages mémorables.',
                'price' => 29.99,
                'category' => 'category_2'
            ],
        ];

        foreach ($products as $key => $productData) {
            $product = new Product();
            $product->setName($productData['name']);
            $product->setDescription($productData['description']);
            $product->setPrice($productData['price']);
            $stock = mt_rand(0, 100);
            $product->setStock($stock);
            $product->setStatus($stock > 0 ? ProductStatus::dispo : ProductStatus::rupture);
            $product->setCategory($this->getReference($productData['category'], Category::class));            
            $manager->persist($product);
            $this->addReference('product_' . $key, $product);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
        ];
    }
}
