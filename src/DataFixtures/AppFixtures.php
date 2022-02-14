<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\ClientCustomer;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $datetimeImmutable = new \DateTimeImmutable();

        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            $client = new Client();
            $client
                ->setEmail("client$i@gmail.com")
                ->setPassword($this->passwordHasher->hashPassword($client, "client$i"))
                ->setName($faker->company)
                ->setAddress($faker->streetAddress)
                ->setZipCode($faker->postcode)
                ->setCountry($faker->country)
                ->setRoles([])
            ;
            $manager->persist($client);

            for ($j = 0; $j < 30; $j++) {
                $clientCustomer = (new ClientCustomer())
                    ->setEmail("customer$i@gmail.com")
                    ->setFirstName($faker->firstName)
                    ->setLastName($faker->lastName)
                    ->setClient($client)
                ;
                $manager->persist($clientCustomer);
            }
        }


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
            ->setCreatedAt($datetimeImmutable)
            ->setUpdatedAt($datetimeImmutable);
        $manager->persist($product);

        $manager->flush();
    }
}
