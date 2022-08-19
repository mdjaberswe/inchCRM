<?php

use Illuminate\Database\Seeder;

class CartItemsTableSeeder extends Seeder
{
	public function run()
	{
		\DB::table('cart_items')->truncate();

		$cart_items = [
			['item_id' => 1, 'linked_id' => 1, 'linked_type' => 'lead', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 25700],
			['item_id' => 1, 'linked_id' => 2, 'linked_type' => 'lead', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 25700],
			['item_id' => 1, 'linked_id' => 3, 'linked_type' => 'lead', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 25700],
			['item_id' => 1, 'linked_id' => 4, 'linked_type' => 'lead', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 25700],
			['item_id' => 1, 'linked_id' => 5, 'linked_type' => 'lead', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 25700],
			['item_id' => 1, 'linked_id' => 6, 'linked_type' => 'lead', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 25700],
			['item_id' => 1, 'linked_id' => 7, 'linked_type' => 'lead', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 25700],
			['item_id' => 1, 'linked_id' => 8, 'linked_type' => 'lead', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 25700],
			['item_id' => 1, 'linked_id' => 9, 'linked_type' => 'lead', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 25700],
			['item_id' => 1, 'linked_id' =>10, 'linked_type' => 'lead', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 25700],

			['item_id' => 1, 'linked_id' => 1, 'linked_type' => 'contact', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 25700],
			['item_id' => 1, 'linked_id' => 2, 'linked_type' => 'contact', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 25700],
			['item_id' => 1, 'linked_id' => 3, 'linked_type' => 'contact', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 25700],
			['item_id' => 1, 'linked_id' => 4, 'linked_type' => 'contact', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 25700],
			['item_id' => 1, 'linked_id' => 5, 'linked_type' => 'contact', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 25700],

			['item_id' => 1, 'linked_id' => 1, 'linked_type' => 'account', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 25700],
			['item_id' => 1, 'linked_id' => 2, 'linked_type' => 'account', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 25700],
			['item_id' => 1, 'linked_id' => 3, 'linked_type' => 'account', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 25700],
			['item_id' => 1, 'linked_id' => 4, 'linked_type' => 'account', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 25700],
			['item_id' => 1, 'linked_id' => 5, 'linked_type' => 'account', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 25700],

			['item_id' => 1, 'linked_id' => 1, 'linked_type' => 'deal', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 25700],
			['item_id' => 1, 'linked_id' => 2, 'linked_type' => 'deal', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 25700],
			['item_id' => 1, 'linked_id' => 3, 'linked_type' => 'deal', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 25700],
			['item_id' => 1, 'linked_id' => 4, 'linked_type' => 'deal', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 25700],
			['item_id' => 1, 'linked_id' => 5, 'linked_type' => 'deal', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 25700],

			['item_id' => 2, 'linked_id' => 1, 'linked_type' => 'lead', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 33000],
			['item_id' => 2, 'linked_id' => 2, 'linked_type' => 'lead', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 33000],
			['item_id' => 2, 'linked_id' => 3, 'linked_type' => 'lead', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 33000],
			['item_id' => 2, 'linked_id' => 4, 'linked_type' => 'lead', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 33000],
			['item_id' => 2, 'linked_id' => 5, 'linked_type' => 'lead', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 33000],
			['item_id' => 2, 'linked_id' => 6, 'linked_type' => 'lead', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 33000],
			['item_id' => 2, 'linked_id' => 7, 'linked_type' => 'lead', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 33000],
			['item_id' => 2, 'linked_id' => 8, 'linked_type' => 'lead', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 33000],
			['item_id' => 2, 'linked_id' => 9, 'linked_type' => 'lead', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 33000],
			['item_id' => 2, 'linked_id' =>10, 'linked_type' => 'lead', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 33000],

			['item_id' => 2, 'linked_id' => 1, 'linked_type' => 'contact', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 33000],
			['item_id' => 2, 'linked_id' => 2, 'linked_type' => 'contact', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 33000],
			['item_id' => 2, 'linked_id' => 3, 'linked_type' => 'contact', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 33000],
			['item_id' => 2, 'linked_id' => 4, 'linked_type' => 'contact', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 33000],
			['item_id' => 2, 'linked_id' => 5, 'linked_type' => 'contact', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 33000],

			['item_id' => 2, 'linked_id' => 1, 'linked_type' => 'account', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 33000],
			['item_id' => 2, 'linked_id' => 2, 'linked_type' => 'account', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 33000],
			['item_id' => 2, 'linked_id' => 3, 'linked_type' => 'account', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 33000],
			['item_id' => 2, 'linked_id' => 4, 'linked_type' => 'account', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 33000],
			['item_id' => 2, 'linked_id' => 5, 'linked_type' => 'account', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 33000],

			['item_id' => 2, 'linked_id' => 1, 'linked_type' => 'deal', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 33000],
			['item_id' => 2, 'linked_id' => 2, 'linked_type' => 'deal', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 33000],
			['item_id' => 2, 'linked_id' => 3, 'linked_type' => 'deal', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 33000],
			['item_id' => 2, 'linked_id' => 4, 'linked_type' => 'deal', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 33000],
			['item_id' => 2, 'linked_id' => 5, 'linked_type' => 'deal', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 33000],

			['item_id' => 3, 'linked_id' => 1, 'linked_type' => 'lead', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 21700],
			['item_id' => 3, 'linked_id' => 2, 'linked_type' => 'lead', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 21700],
			['item_id' => 3, 'linked_id' => 3, 'linked_type' => 'lead', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 21700],
			['item_id' => 3, 'linked_id' => 4, 'linked_type' => 'lead', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 21700],
			['item_id' => 3, 'linked_id' => 5, 'linked_type' => 'lead', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 21700],
			['item_id' => 3, 'linked_id' => 6, 'linked_type' => 'lead', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 21700],
			['item_id' => 3, 'linked_id' => 7, 'linked_type' => 'lead', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 21700],
			['item_id' => 3, 'linked_id' => 8, 'linked_type' => 'lead', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 21700],
			['item_id' => 3, 'linked_id' => 9, 'linked_type' => 'lead', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 21700],
			['item_id' => 3, 'linked_id' =>10, 'linked_type' => 'lead', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 21700],

			['item_id' => 3, 'linked_id' => 1, 'linked_type' => 'contact', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 21700],
			['item_id' => 3, 'linked_id' => 2, 'linked_type' => 'contact', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 21700],
			['item_id' => 3, 'linked_id' => 3, 'linked_type' => 'contact', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 21700],
			['item_id' => 3, 'linked_id' => 4, 'linked_type' => 'contact', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 21700],
			['item_id' => 3, 'linked_id' => 5, 'linked_type' => 'contact', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 21700],

			['item_id' => 3, 'linked_id' => 1, 'linked_type' => 'account', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 21700],
			['item_id' => 3, 'linked_id' => 2, 'linked_type' => 'account', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 21700],
			['item_id' => 3, 'linked_id' => 3, 'linked_type' => 'account', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 21700],
			['item_id' => 3, 'linked_id' => 4, 'linked_type' => 'account', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 21700],
			['item_id' => 3, 'linked_id' => 5, 'linked_type' => 'account', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 21700],

			['item_id' => 3, 'linked_id' => 1, 'linked_type' => 'deal', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 21700],
			['item_id' => 3, 'linked_id' => 2, 'linked_type' => 'deal', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 21700],
			['item_id' => 3, 'linked_id' => 3, 'linked_type' => 'deal', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 21700],
			['item_id' => 3, 'linked_id' => 4, 'linked_type' => 'deal', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 21700],
			['item_id' => 3, 'linked_id' => 5, 'linked_type' => 'deal', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 21700],

			['item_id' => 4, 'linked_id' => 1, 'linked_type' => 'lead', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 17700],
			['item_id' => 4, 'linked_id' => 2, 'linked_type' => 'lead', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 17700],
			['item_id' => 4, 'linked_id' => 3, 'linked_type' => 'lead', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 17700],
			['item_id' => 4, 'linked_id' => 4, 'linked_type' => 'lead', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 17700],
			['item_id' => 4, 'linked_id' => 5, 'linked_type' => 'lead', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 17700],
			['item_id' => 4, 'linked_id' => 6, 'linked_type' => 'lead', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 17700],
			['item_id' => 4, 'linked_id' => 7, 'linked_type' => 'lead', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 17700],
			['item_id' => 4, 'linked_id' => 8, 'linked_type' => 'lead', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 17700],
			['item_id' => 4, 'linked_id' => 9, 'linked_type' => 'lead', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 17700],
			['item_id' => 4, 'linked_id' =>10, 'linked_type' => 'lead', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 17700],

			['item_id' => 4, 'linked_id' => 1, 'linked_type' => 'contact', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 17700],
			['item_id' => 4, 'linked_id' => 2, 'linked_type' => 'contact', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 17700],
			['item_id' => 4, 'linked_id' => 3, 'linked_type' => 'contact', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 17700],
			['item_id' => 4, 'linked_id' => 4, 'linked_type' => 'contact', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 17700],
			['item_id' => 4, 'linked_id' => 5, 'linked_type' => 'contact', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 17700],

			['item_id' => 4, 'linked_id' => 1, 'linked_type' => 'account', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 17700],
			['item_id' => 4, 'linked_id' => 2, 'linked_type' => 'account', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 17700],
			['item_id' => 4, 'linked_id' => 3, 'linked_type' => 'account', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 17700],
			['item_id' => 4, 'linked_id' => 4, 'linked_type' => 'account', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 17700],
			['item_id' => 4, 'linked_id' => 5, 'linked_type' => 'account', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 17700],

			['item_id' => 4, 'linked_id' => 1, 'linked_type' => 'deal', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 17700],
			['item_id' => 4, 'linked_id' => 2, 'linked_type' => 'deal', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 17700],
			['item_id' => 4, 'linked_id' => 3, 'linked_type' => 'deal', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 17700],
			['item_id' => 4, 'linked_id' => 4, 'linked_type' => 'deal', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 17700],
			['item_id' => 4, 'linked_id' => 5, 'linked_type' => 'deal', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 17700],

			['item_id' => 5, 'linked_id' => 1, 'linked_type' => 'lead', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 19700],
			['item_id' => 5, 'linked_id' => 2, 'linked_type' => 'lead', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 19700],
			['item_id' => 5, 'linked_id' => 3, 'linked_type' => 'lead', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 19700],
			['item_id' => 5, 'linked_id' => 4, 'linked_type' => 'lead', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 19700],
			['item_id' => 5, 'linked_id' => 5, 'linked_type' => 'lead', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 19700],
			['item_id' => 5, 'linked_id' => 6, 'linked_type' => 'lead', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 19700],
			['item_id' => 5, 'linked_id' => 7, 'linked_type' => 'lead', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 19700],
			['item_id' => 5, 'linked_id' => 8, 'linked_type' => 'lead', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 19700],
			['item_id' => 5, 'linked_id' => 9, 'linked_type' => 'lead', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 19700],
			['item_id' => 5, 'linked_id' =>10, 'linked_type' => 'lead', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 19700],
		
			['item_id' => 5, 'linked_id' => 1, 'linked_type' => 'contact', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 19700],
			['item_id' => 5, 'linked_id' => 2, 'linked_type' => 'contact', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 19700],
			['item_id' => 5, 'linked_id' => 3, 'linked_type' => 'contact', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 19700],
			['item_id' => 5, 'linked_id' => 4, 'linked_type' => 'contact', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 19700],
			['item_id' => 5, 'linked_id' => 5, 'linked_type' => 'contact', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 19700],

			['item_id' => 5, 'linked_id' => 1, 'linked_type' => 'account', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 19700],
			['item_id' => 5, 'linked_id' => 2, 'linked_type' => 'account', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 19700],
			['item_id' => 5, 'linked_id' => 3, 'linked_type' => 'account', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 19700],
			['item_id' => 5, 'linked_id' => 4, 'linked_type' => 'account', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 19700],
			['item_id' => 5, 'linked_id' => 5, 'linked_type' => 'account', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 19700],
		
			['item_id' => 5, 'linked_id' => 1, 'linked_type' => 'deal', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 19700],
			['item_id' => 5, 'linked_id' => 2, 'linked_type' => 'deal', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 19700],
			['item_id' => 5, 'linked_id' => 3, 'linked_type' => 'deal', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 19700],
			['item_id' => 5, 'linked_id' => 4, 'linked_type' => 'deal', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 19700],
			['item_id' => 5, 'linked_id' => 5, 'linked_type' => 'deal', 'quantity' => rand(1, 10), 'unit' => 'Unit', 'rate' => 19700]
		];

		\DB::table('cart_items')->insert($cart_items);
	}
}