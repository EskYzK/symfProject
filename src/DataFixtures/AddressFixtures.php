<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AddressFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 5; $i++) {
            $address = new Address();
            $address->setStreet($i . ' rue de la Paix');
            $address->setCity('Paris');
            $address->setPostalCode('7500' . $i);
            $address->setCountry('FR');

            // ⚠️ ICI : on utilise les références créées dans UserFixtures
            // et on passe bien 2 arguments à getReference(...)
            /** @var User $user */
            $user = $this->getReference('user_'.$i, User::class);
            $address->setUser($user);

            $manager->persist($address);
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
