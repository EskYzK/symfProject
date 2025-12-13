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
        for ($i = 0; $i < 4; $i++) {
            $image = new Image();
            
            /** @var Product $product */
            $product = $this->getReference('product_' . $i, Product::class);
            
            $imageName = $product->getName() . '.jpg';
            $image->setUrl('images/products/' . $imageName);
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