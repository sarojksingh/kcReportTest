<?php

namespace Migration;

use Cycle\Migrations\Migration;

class FixOrdersTable extends Migration
{
    public function up(): void
    {

        //Drop foreign_keys from duplicate columns
        $this->table('orders')->dropForeignKey(['store_storeId']);
        $this->table('orders')->dropForeignKey(['product_productId']);

        //remove duplicate store and product id
        $this->table('orders')
            ->dropColumn('store_storeId')
            ->dropColumn('product_productId');

        //Add foreign key to existing id
        $this->table('orders')
            ->addForeignKey(['store_id'], 'stores', ['store_id'], [
                'name' => 'orders_storeId_fk',
                'delete' => 'CASCADE',
                'update' => 'CASCADE',
                'indexCreate' => true,
            ]);

        $this->table('orders')
            ->addForeignKey(['product_id'], 'products', ['product_id'], [
                'name' => 'orders_productId_fk',
                'delete' => 'CASCADE',
                'update' => 'CASCADE',
                'indexCreate' => true,
            ]);
    }

    public function down(): void
    {
        /*$this->table('orders')
            ->addColumn('store_id', 'bigInteger', ['nullable' => false, 'defaultValue' => null])
            ->addColumn('product_id', 'bigInteger', ['nullable' => false, 'defaultValue' => null]);

        $this->table('orders')->dropForeignKey(['store_id']);
        $this->table('orders')->dropForeignKey(['product_id']);*/

        //Drop foreign_keys
        $this->table('orders')->dropForeignKey(['store_id']);
        $this->table('orders')->dropForeignKey(['product_id']);

        //re-add duplicate columns
        $this->table('orders')
            ->addColumn('store_storeId', 'bigInteger', ['nullable' => false, 'defaultValue' => null])
            ->addColumn('product_productId', 'bigInteger', ['nullable' => false, 'defaultValue' => null]);

        //Add foreign key to duplicate columns
        $this->table('orders')
            ->addForeignKey(['store_storeId'], 'stores', ['store_id'], [
                'name' => 'orders_store_storeId_fk',
                'delete' => 'CASCADE',
                'update' => 'CASCADE',
                'indexCreate' => true,
            ]);

        $this->table('orders')
            ->addForeignKey(['product_productId'], 'products', ['product_id'], [
                'name' => 'orders_product_productId_fk',
                'delete' => 'CASCADE',
                'update' => 'CASCADE',
                'indexCreate' => true,
            ]);
    }
}
