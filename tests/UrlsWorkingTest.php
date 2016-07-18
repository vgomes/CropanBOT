<?php

use Cropan\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UrlsWorkingTest extends TestCase
{
    protected $user;

    use DatabaseMigrations;
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        Artisan::call('migrate');
        $this->user = User::where('nickname', 'Himliano')->first();
    }

    /** @test */
    public function nonLoggedInHomepage()
    {
        $this->visit(route('pages.index'))
            ->see('Cropan Gourmet')
            ->see("Entrar")
            ->see('El club de caballeros más selecto de todo el internec')
            ->dontSee("Últimas enviadas a Tumblr")
            ->dontSee('Ranking')
            ->dontSee('Pendientes');
    }

    /** @test */
    public function loggedInHomepage()
    {
        $this->actingAs($this->user)
            ->visit(route('pages.index'))
            ->see('Cropan Gourmet')
            ->see("Últimas enviadas a Tumblr")

            ->dontSee("Entrar")
            ->dontSee('El club de caballeros más selecto de todo el internec');
    }

    /** test */
    public function nonLoggedHistory()
    {
        $this->visit(route('pages.history'))
            ->assertRedirectedToRoute(route('login'));
    }
}
