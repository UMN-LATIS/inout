<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class toggleTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function setUp() {
        parent::setUp();
        $this->seed('SeedInout');
    }

    public function test_anonymous_board()
    {

        $targetBoard = \App\Board::find(1);
        $this->browse(function (Browser $browser) use ($targetBoard) {
            $browser->visit('/' . $targetBoard->unit)
                    ->assertSee($targetBoard->public_title);
        });
    }

    public function test_anonymous_sockets()
    {

        $targetBoard = \App\Board::find(1);
        $this->browse(function ($first, $second) use ($targetBoard) {
            $first->visit('/' . $targetBoard->unit)
                    ->waitForText($targetBoard->public_title);
            $second->visit('/' . $targetBoard->unit)
                    ->waitForText($targetBoard->public_title)
                    ->click(".editIcon")
                    ->type(".messageEntry","test")
                    ->press("Save");
            $first->waitForText("test")->assertSee("test");

        });
    }

    public function test_private_board_requires_auth() {

        $targetBoard = \App\Board::find(2);
        $this->browse(function (Browser $browser) use ($targetBoard) {
            $browser->visit('/' . $targetBoard->unit)
                    ->assertSee("login");
        });

    }

    public function test_login_as_normal_user() {

        $targetBoard = \App\Board::find(2);
        $this->browse(function (Browser $browser) use ($targetBoard) {
            $user = \App\User::find(8);
            $browser->loginAs($user);
            $result = $browser->visit('/' . $targetBoard->unit);
            $elements = $result->elements(".editIcon");
            $this->assertCount(1, $elements);
            $browser->click(".userClick")->waitForText("Email:")->assertSee("Email:");
            $browser->clickLink($user->first_name)->pause(1000)->assertSee("Remove User");
        });

    }

    public function test_login_as_admin() {

        $targetBoard = \App\Board::find(2);
        $this->browse(function (Browser $browser) use ($targetBoard) {
            $user = \App\User::find(7);
            $browser->loginAs($user);
            $result = $browser->visit('/' . $targetBoard->unit);
            $elements = $result->elements(".editIcon");
            $this->assertCount(6, $elements);
            $browser->assertSee("Edit Board");
        });

    }
}
