<?php

use Illuminate\Database\Seeder;

use Faker\Factory as Faker;

class DetailsTableSeeder extends Seeder
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
	        DB::table('details')->insert([
	            'home_id' => factory(App\Model\home::class)->create()->id,
	            'spouse' => $faker->name,
	            'email' => $faker->unique()->email,
	            'gender' => $faker->randomElement($array = array ('m','f')),
	            'created_at' => $faker->date('Y-m-d h:m:s', 'now'),
	            'updated_at' => $faker->date('Y-m-d h:m:s', 'now')
	        ]);
	    }
    }
}
