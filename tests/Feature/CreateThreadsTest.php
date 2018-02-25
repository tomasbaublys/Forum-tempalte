<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateThreadsTest extends TestCase
{
	use RefreshDatabase;
    /** @test */
    function quests_may_not_create_threads()
    {
    	$this->expectException('Illuminate\Auth\AuthenticationException');
    	$thread = create('App\Thread');
    	$this->post('/threads', $thread->toArray());
    }
    /** @test */
    public function test_guests_cannot_see_create_threads()
    {
        $this->withExceptionHandling()
            ->get('/threads/create')
            ->assertRedirect('/login');
    }
    /** @test */
    function an_authenticated_user_can_create_new_forum_threads()
    {
    	// Given we have a signed in user
        $this->signIn();
        // When we hit the endpoint to create a new thread
        $thread = make('App\Thread');

        $response = $this->post('/threads', $thread->toArray());
        // Then, when we visit the thread page and should see the new thread
        $this->get($response->headers->get('Location'))
        ->assertSee($thread->title)
        ->assertSee($thread->body);
    }
    /** @test */
    function a_thread_requires_a_title()
    {
        $this->publishThread(['title' => null])
            ->assertSessionHasErrors('title');
    }
    /** @test */
    function a_thread_requires_a_body()
    {
        $this->publishThread(['body' => null])
            ->assertSessionHasErrors('body');
    }
    /** @test */
    function a_thread_requires_a_valid_chanel()
    {
        factory('App\Channel', 2)->create();
        
        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id' => 999])
            ->assertSessionHasErrors('channel_id');
    }

    public function publishThread($overrides = [])
    {
        $this->withExceptionHandling()->signIn();

        $thread = make('App\Thread', $overrides);
        
        return $this->post('/threads', $thread->toArray());

    }

}
