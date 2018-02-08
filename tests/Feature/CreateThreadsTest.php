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
    	$thread = make('App\Thread');
    	$this->post('/threads', $thread->toArray());
    }
     /**
     * @test
     */ 
    function quests_cannot_see_the_create_threads_page()
    {

        $this->withExceptionHandling()
        ->get('/threads/create')
        ->assertRedirect('/login');
    }
    /**
     * @test
     */
    function an_authenticated_user_can_create_new_forum_threads()
    {
    	// Given we have a signed in user
        $this->signIn();
        // When we hit the endpoint to create a new thread
        $thread = make('App\Thread');

        $this->post('/threads', $thread->toArray());
        // Then, when we visit the thread page and should see the new thread
        $this->get($thread->path())->assertSee($thread->title)->assertSee($thread->body);
    }
}
