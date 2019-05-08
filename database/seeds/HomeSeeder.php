<?php

use Illuminate\Database\Seeder;

class HomeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {		
		$homes = factory(App\Model\home::class, 1000)->create();
        $this->command->info('Home Table Seeding Done!');
    }
}
