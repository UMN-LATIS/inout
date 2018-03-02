<?php

use Illuminate\Database\Seeder;

class SeedInout extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Board::class, 1)->create()->each(function($foo) {
		    $foo->users()->save(factory(App\User::class)->create());
		    $foo->users()->save(factory(App\User::class)->create());
		    $foo->users()->save(factory(App\User::class)->create());
		    $foo->users()->save(factory(App\User::class)->create());
		    $foo->users()->save(factory(App\User::class)->create());
		    $foo->users()->save(factory(App\User::class)->create());
		    
		});
    }
}
