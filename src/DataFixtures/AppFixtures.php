<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\ClientCustomer;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

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
                ->setZipCode((int)$faker->postcode)
                ->setCountry($faker->country)
                ->setRoles([]);
            $manager->persist($client);

            for ($j = 0; $j < 30; $j++) {
                $clientCustomer = (new ClientCustomer())
                    ->setEmail("customer$i@gmail.com")
                    ->setFirstName($faker->firstName)
                    ->setLastName($faker->lastName)
                    ->setClient($client);
                $manager->persist($clientCustomer);
            }
        }

        $productOS = ['Android', 'iOS'];
        $productStorage = [64, 128, 256];
        $productColor = ['Black', 'Red', 'White', 'Gold'];
        $productRAM = [8, 12];
        $productsFixtures = [
            ['Google', 'Pixel 6 Pro', 0, 1, 1],
            ['Samsung', 'Galaxy S21', 0, 1, 1],
            ['Apple', 'Iphone 13', 1, 1, 0],
            ['Samsung', 'Galaxy S20', 0, 2, 1],
            ['Google', 'Pixel 5', 0, 0, 0],
            ['Oppo', 'Find X3', 0, 0, 0],
            ['Samsung', 'Galaxy Z Fold', 0, 1, 1],
            ['Xiaomi', 'Mi 11', 0, 1, 0],
            ['Samsung', 'Galaxy Note 20', 0, 2, 0],
            ['Sony', 'Xperia 5', 0, 0, 1],
            ['Apple', 'Iphone 12', 1, 1, 0]
        ];
        foreach ($productsFixtures as $product) {
            $product = (new Product())
                ->setBrand($product[0])
                ->setName($product[1])
                ->setOS($productOS[$product[2]])
                ->setStorage($productStorage[$product[3]])
                ->setRAM($productRAM[$product[4]])
                //
                ->setScreenSize(6.7)
                ->setWeight(210)
                ->setWidth(75.9)
                ->setHeight(163.9)
                ->setDepth(8.9)
                ->setBattery(5003)
                //
                //
                ->setConnectivity('5G')
                ->setMicroSD((rand(0, 1)))
                ->setColor($productColor[rand(0, 3)])
                ->setCreatedAt($datetimeImmutable)
                ->setUpdatedAt($datetimeImmutable);
            $manager->persist($product);
        }

        $manager->flush();
    }
}
