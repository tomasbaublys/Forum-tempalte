<?php
namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ThreadTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    function a_thread_has_replies()
    {
        $thread= factory('App\Thread')->create();
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $thread->replies);
    }

    function a_thread_has_creator()
    {
    	$thread = factory('App\Thread')->create();
    	$this->assertInstanceOf('App\User', $thread->creator);
    }
}
