<?php

declare(strict_types=1);

namespace App\Entity;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\HasMany;

#[Entity(table: 'products')]
class Product
{
    #[Column(type: 'bigPrimary')]
    public int $productId;

    #[Column(type: 'bigInteger')]
    public int $categoryId;

    #[Column(type: 'string', length: 200)]
    public string $productName;

    #[HasMany(target: Order::class)]
    public array $orders;
}
