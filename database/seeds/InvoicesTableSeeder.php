<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Invoice;

class InvoicesTableSeeder extends Seeder
{
	public function run()
	{
		Invoice::truncate();

		$faker = Faker::create();
		$save_date = date('Y-m-d H:i:s');

		$invoices = [
			['account_id' => 1, 'contact_id' => 1, 'deal_id' => 1, 'project_id' => 1, 'sale_agent' => 1, 'number' => 1, 'reference' => strtoupper(str_random(2)) . '-' . rand(1000, 5000), 'subject' => 'January 2018 1st Invoice', 'status' => 'draft', 'invoice_date' => date('Y-m-d H:i:s'), 'date_pay_before' => $faker->dateTimeInInterval('+ 2 days', '+ 5 days', null), 'currency_id' => 1, 'discount_type' => 'post', 'sub_total' => 25700.00, 'total_tax' => 514.00, 'total_discount' => 1310.70, 'adjustment' => 100, 'grand_total' => 25003.30, 'payment' => 25003.30, 'recurring' => 0, 'term_condition' => 'PIA - Payment In Advance', 'note' => 'Statement discount is applicable if paid within 7 days', 'created_at' => $save_date, 'updated_at' => $save_date],
			['account_id' => 2, 'contact_id' => 2, 'deal_id' => 2, 'project_id' => 2, 'sale_agent' => 1, 'number' => 2, 'reference' => strtoupper(str_random(2)) . '-' . rand(1000, 5000), 'subject' => 'January 2018 2nd Invoice', 'status' => 'unpaid', 'invoice_date' => date('Y-m-d H:i:s'), 'date_pay_before' => $faker->dateTimeInInterval('+ 2 days', '+ 5 days', null), 'currency_id' => 1, 'discount_type' => 'post', 'sub_total' => 33000.00, 'total_tax' => 660.00, 'total_discount' => 1683.00, 'adjustment' => 100, 'grand_total' => 32077.00, 'payment' => 32077.00, 'recurring' => 0, 'term_condition' => 'PIA - Payment In Advance', 'note' => 'Statement discount is applicable if paid within 7 days', 'created_at' => $save_date, 'updated_at' => $save_date],
			['account_id' => 3, 'contact_id' => 3, 'deal_id' => 3, 'project_id' => 3, 'sale_agent' => 1, 'number' => 3, 'reference' => strtoupper(str_random(2)) . '-' . rand(1000, 5000), 'subject' => 'January 2018 3rd Invoice', 'status' => 'partially_paid', 'invoice_date' => date('Y-m-d H:i:s'), 'date_pay_before' => $faker->dateTimeInInterval('+ 2 days', '+ 5 days', null), 'currency_id' => 1, 'discount_type' => 'post', 'sub_total' => 21700.00, 'total_tax' => 434.00, 'total_discount' => 1106.70, 'adjustment' => 100, 'grand_total' => 21127.30, 'payment' => 21127.30, 'recurring' => 0, 'term_condition' => 'PIA - Payment In Advance', 'note' => 'Statement discount is applicable if paid within 7 days', 'created_at' => $save_date, 'updated_at' => $save_date],
			['account_id' => 4, 'contact_id' => 4, 'deal_id' => 4, 'project_id' => 4, 'sale_agent' => 1, 'number' => 4, 'reference' => strtoupper(str_random(2)) . '-' . rand(1000, 5000), 'subject' => 'January 2018 4th Invoice', 'status' => 'partially_paid', 'invoice_date' => date('Y-m-d H:i:s'), 'date_pay_before' => $faker->dateTimeInInterval('+ 2 days', '+ 5 days', null), 'currency_id' => 1, 'discount_type' => 'post', 'sub_total' => 17700.00, 'total_tax' => 354.00, 'total_discount' => 902.70, 'adjustment' => 100, 'grand_total' => 17251.30, 'payment' => 17251.30, 'recurring' => 0, 'term_condition' => 'PIA - Payment In Advance', 'note' => 'Statement discount is applicable if paid within 7 days', 'created_at' => $save_date, 'updated_at' => $save_date],
			['account_id' => 5, 'contact_id' => 5, 'deal_id' => 5, 'project_id' => 5, 'sale_agent' => 1, 'number' => 5, 'reference' => strtoupper(str_random(2)) . '-' . rand(1000, 5000), 'subject' => 'January 2018 5th Invoice', 'status' => 'paid', 'invoice_date' => date('Y-m-d H:i:s'), 'date_pay_before' => $faker->dateTimeInInterval('+ 2 days', '+ 5 days', null), 'currency_id' => 1, 'discount_type' => 'post', 'sub_total' => 19700.00, 'total_tax' => 394.00, 'total_discount' => 1004.70, 'adjustment' => 100, 'grand_total' => 19189.30, 'payment' => 19189.30, 'recurring' => 1, 'term_condition' => 'PIA - Payment In Advance', 'note' => 'Statement discount is applicable if paid within 7 days', 'created_at' => $save_date, 'updated_at' => $save_date]
		];

		Invoice::insert($invoices);
	}
}