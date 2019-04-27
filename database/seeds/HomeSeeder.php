<?php

use Illuminate\Database\Seeder;

use Faker\Factory as Faker;

class HomeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
    	foreach (range(1,10000) as $index) {
	        DB::table('homes')->insert([
	            'name' => $faker->name,
	            'address' => $faker->city,
	            'contactno' => $faker->numberBetween(10000000,99999999),
	            'annualincome' => $faker->randomFloat(2, 0, 999999),
	            'age' => $faker->numberBetween(18,100),
	            'created_at' => $faker->date('Y-m-d h:m:s', 'now'),
	            'updated_at' => $faker->date('Y-m-d h:m:s', 'now')
	        ]);
	    }
    }
}
