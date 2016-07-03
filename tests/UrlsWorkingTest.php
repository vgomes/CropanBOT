<?php

use Cropan\User;

class UrlsWorkingTest extends TestCase
{
    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->user = User::where('nickname', 'Himliano')->first();
    }

    /**
     * Testing homepage for non logged in users
     */
    public function testNonLoggedInHomepage()
    {
        $this->visit(route('pages.index'))
            ->see('CropanBOT')
            ->see("Login with twitter")
            ->dontSee("Últimas enviadas a Tumblr");
    }

    public function testLoggedInHomepage()
    {
        $this->actingAs($this->user)
            ->visit(route('pages.index'))
            ->see('CropanBOT')
            ->see("Últimas enviadas a Tumblr")
            ->dontSee("Login with twitter");
    }
}
