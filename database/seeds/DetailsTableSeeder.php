<?php

use Illuminate\Database\Seeder;

class DetailsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$details = factory(App\Model\Detail::class, 1000)->create();
        $this->command->info('Detail Table Seeding Done!');
    }
}
