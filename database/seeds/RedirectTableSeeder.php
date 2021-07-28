<?php

use Illuminate\Database\Seeder;
use App\Models\Redirect;

class RedirectTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Redirect::class, 10)->create();
    }
}
