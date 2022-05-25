<?php

use Illuminate\Database\Seeder;

use App\Models\Organization;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Organization::class, 10)->create();
    }
}
