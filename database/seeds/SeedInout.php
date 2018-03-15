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
        factory(App\Board::class, 2)->create()->each(function($foo) {
		    $foo->users()->save(factory(App\User::class)->create());
		    $foo->users()->save(factory(App\User::class)->create());
		    $foo->users()->save(factory(App\User::class)->create());
		    $foo->users()->save(factory(App\User::class)->create());
		    $foo->users()->save(factory(App\User::class)->create());
		    $foo->users()->save(factory(App\User::class)->create());
		    
		});

        $board = App\Board::find(1);
        $board->public = 1;
        $board->anyone_can_edit = 1;
        $board->save();

        $board = App\Board::find(2);
        $user = $board->users->first();
        $user->pivot->is_admin = 1;
        $user->pivot->save();

    }
}
