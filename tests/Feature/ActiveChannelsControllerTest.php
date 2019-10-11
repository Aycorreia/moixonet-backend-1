<?php

namespace Tests\Feature;

use App\Channel;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActiveChannelsControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function manager_can_active_a_channel()
    {
        $user = factory(User::class)->create();
        $manager = factory(User::class)->create([
            'is_super_admin' => true
        ]);
        $channel = Channel::create([
            'name' => 'Canal 1',
            'owner' => $user->id,
            'active' => false
        ]);
        $this->loginAsUser($manager);
        $response = $this->json('POST','/api/v1/active_channels/' . $channel->id);
        $response->assertSuccessful();
        $channel = $channel->refresh();
//        $channel = Channel::find($channel->id);
        $this->assertEquals($channel->name, 'Canal 1');
        $this->assertEquals($channel->owner, $user->id);
        $this->assertEquals($channel->active,1);
    }

    /** @test */
    public function regular_user_can_active_an_owned_channel()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->create();
        $channel = Channel::create([
            'name' => 'Canal 1',
            'owner' => $user->id,
            'active' => false
        ]);
        $this->loginAsUser($user);
        $response = $this->json('POST','/api/v1/active_channels/' . $channel->id);
        $response->assertSuccessful();
        $channel = $channel->refresh();
//        $channel = Channel::find($channel->id);
        $this->assertEquals($channel->name, 'Canal 1');
        $this->assertEquals($channel->owner, $user->id);
        $this->assertEquals($channel->active, 1);
    }

    /** @test */
    public function regular_user_cannot_active_not_owned_channel()
    {
        $user = factory(User::class)->create();
        $otherUser = factory(User::class)->create();
        $channel = Channel::create([
            'name' => 'Canal 1',
            'owner' => $otherUser->id,
            'active' => false
        ]);
        $this->loginAsUser($user);
        $response = $this->json('POST','/api/v1/active_channels/' . $channel->id);
        $response->assertStatus(403);
    }

    /** @test */
    public function guest_user_cannot_active_a_channel()
    {
        $channel = Channel::create([
            'name' => 'Canal 1',
            'owner' => 1,
            'active' => false
        ]);
        $response = $this->json('POST','/api/v1/active_channels/' . $channel->id);
        $response->assertStatus(401);
    }

    /** @test */
    public function manager_can_disable_a_channel()
    {
        $user = factory(User::class)->create();
        $manager = factory(User::class)->create([
            'is_super_admin' => true
        ]);        $channel = Channel::create([
            'name' => 'Canal 1',
            'owner' => $user->id,
            'active' => true
        ]);
        $this->loginAsUser($manager);
        $response = $this->json('DELETE','/api/v1/active_channels/' . $channel->id);
        $response->assertSuccessful();
        $channel = $channel->refresh();
        $this->assertEquals($channel->name, 'Canal 1');
        $this->assertEquals($channel->owner, $user->id);
        $this->assertEquals($channel->active,0);
    }

    /** @test */
    public function regular_user_can_disable_an_owned_channel()
    {
        $user = factory(User::class)->create();
        $channel = Channel::create([
            'name' => 'Canal 1',
            'owner' => $user->id,
            'active' => true
        ]);
        $this->loginAsUser($user);
        $response = $this->json('DELETE','/api/v1/active_channels/' . $channel->id);
        $response->assertSuccessful();
        $channel = $channel->refresh();
        $this->assertEquals($channel->name, 'Canal 1');
        $this->assertEquals($channel->owner, $user->id);
        $this->assertEquals($channel->active,0);    }

    /** @test */
    public function regular_user_cannot_disable_not_owned_channel()
    {
        $user = factory(User::class)->create();
        $otherUser = factory(User::class)->create();
        $channel = Channel::create([
            'name' => 'Canal 1',
            'owner' => $otherUser->id,
            'active' => true
        ]);
        $this->loginAsUser($user);
        $response = $this->json('POST','/api/v1/active_channels/' . $channel->id);
        $response->assertStatus(403);
    }

    /** @test */
    public function guest_user_cannot_disable_a_channel()
    {
        $channel = Channel::create([
            'name' => 'Canal 1',
            'owner' => 1,
            'active' => true
        ]);
        $response = $this->json('POST','/api/v1/active_channels/' . $channel->id);
        $response->assertStatus(401);
    }
}
