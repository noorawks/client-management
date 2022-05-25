<?php

use Illuminate\Database\Seeder;

use App\Models\Person;

class PersonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Person::class, 30)->create();
    }
}
