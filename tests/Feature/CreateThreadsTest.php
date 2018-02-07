<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateThreadsTest extends TestCase
{
	use RefreshDatabase;
    /**
     * @test
     */ 
    function quests_may_not_create_threads()
    {
    	$this->expectException('Illuminate\Auth\AuthenticationException');
    	$thread = factory('App\Thread')->make();
    	$this->post('/threads', $thread->toArray())->assertRedirect('/login');
    }
    /**
     * @test
     */
    function an_authenticated_user_can_create_new_forum_threads()
    {
    	// Given we have a signed in user
        $this->actingAs(factory('App\User')->create());
        // When we hit the endpoint to create a new thread
        $thread = factory('App\Thread')->make();

        $this->post('/threads', $thread->toArray());
        // Then, when we visit the thread page and should see the new thread
        $this->get($thread->path())->assertSee($thread->title)->assertSee($thread->body);
    }
}
