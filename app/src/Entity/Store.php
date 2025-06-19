<?php

declare(strict_types=1);

namespace App\Entity;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\HasMany;

#[Entity(table: 'stores')]
class Store
{
    #[Column(type: 'bigPrimary')]
    public int $storeId;

    #[Column(type: 'bigInteger')]
    public int $regionId;

    #[Column(type: 'string', length: 200)]
    public string $storeName;

    #[HasMany(target: Order::class)]
    public array $orders;
}
