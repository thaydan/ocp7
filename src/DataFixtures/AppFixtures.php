<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\ClientCustomer;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $client = (new Client())
            ->setEmail('demo@gmail.com')
            ->setPassword('pass')
            ->setName('Demo 1')
            ->setAddress('3 rue des Plesses')
            ->setZipCode(85180)
            ->setCountry('France')
            ->setRoles([])
        ;
        $manager->persist($client);

        $clientCustomer = (new ClientCustomer())
            ->setEmail('customer1@gmail.com')
            ->setFirstName('Steve')
            ->setLastName('Joe')
            ->setClient($client)
        ;
        $manager->persist($clientCustomer);

        $product = (new Product())
            ->setBrand('Google')
            ->setName('Pixel 6 Pro')
            ->setOS('Android')
            ->setStorage(128)
            ->setRAM(12)
            ->setScreenSize(6.7)
            ->setWeight(210)
            ->setWidth(75.9)
            ->setHeight(163.9)
            ->setDepth(8.9)
            ->setBattery(5003)
            ->setConnectivity('5G')
            ->setMicroSD(false)
            ->setColor('Black')
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable());
        $manager->persist($product);

        $manager->flush();
    }
}
