<?php

namespace App\DataFixtures;

use App\Entity\Image;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ImageFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Crée une image pour chaque produit
        for ($i = 0; $i < 4; $i++) {
            $image = new Image();
            $image->setUrl('https://via.placeholder.com/640x480.png/0000ff/ffffff?Text=Image+' . $i);
            
            // Récupère la référence du produit et l'associe à l'image
            /** @var Product $product */
            $product = $this->getReference('product_' . $i,Product::class);
            $image->setProduct($product);
            
            $manager->persist($image);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ProductFixtures::class,
        ];
    }
}
