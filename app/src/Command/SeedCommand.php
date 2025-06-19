<?php

namespace App\Command;

use Cycle\ORM\EntityManagerInterface;
use Spiral\Console\Attribute\AsCommand;
use Spiral\Console\Command;
use App\Domain\User\Entity\User;
use App\Entity\Store;
use App\Entity\Product;
use App\Entity\Order;
use Faker\Factory;

#[AsCommand(name: 'seed:data', description: 'Seed the database with demo data')]
class SeedCommand extends Command
{
    public function perform(EntityManagerInterface $em): void
    {
        $faker = Factory::create();
        $regions = range(1, 5);
        $categories = range(1, 10);

        // --- Users
        for ($i = 0; $i < 100; $i++) {
            $user = new User();
            $user->username = $faker->userName();
            $user->email = $faker->email();
            $em->persist($user);
        }

        // --- Stores
        $stores = [];
        for ($i = 0; $i < 10; $i++) {
            $store = new Store();
            $store->regionId = $faker->randomElement($regions);
            $store->storeName = $faker->company();
            $em->persist($store);
            $stores[] = $store;
        }

        // --- Products
        $products = [];
        for ($i = 0; $i < 50; $i++) {
            $product = new Product();
            $product->categoryId = $faker->randomElement($categories);
            $product->productName = $faker->word();
            $em->persist($product);
            $products[] = $product;
        }

        $em->run(); // Persist users, stores, products first


        // --- Orders (100K+)
        $orderBatch = 0;
        for ($i = 0; $i < 100000; $i++) {
            $order = new Order();
            $order->customerId = $faker->numberBetween(1, 100);
            $order->storeId = $store = $faker->randomElement($stores)->storeId;
            $order->store_storeId = $store;
            $order->productId = $product = $faker->randomElement($products)->productId;
            $order->product_productId = $product;
            $order->quantity = $faker->numberBetween(1, 10);
            $order->unitPrice = $faker->randomFloat(2, 10, 200);
            $order->orderDate = $faker->dateTimeBetween('-18 months', 'now')->format('Y-m-d');
            //$order->orderDate = new \DateTime(sprintf('%d-%02d-%02d', $year, $month, rand(1, 28)));
            $em->persist($order);

            // Flush every 500 inserts
            if (++$orderBatch % 10000 === 0) {
                $this->write("Inserted {$orderBatch} orders...");
                $em->run();
            }
        }

        $em->run(); // Final flush

        $this->writeln('<info>✅ Seeding complete.</info>');
    }
}
