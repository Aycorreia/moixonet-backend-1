<?php

namespace Tests\Feature;

use App\Channel;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserChannelsControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function regular_user_can_see_owned_channels()
    {
        $user = factory(User::class)->create();

        Channel::create([
            'name' => 'Channel 1',
            'owner' => $user->id
        ]);
        Channel::create([
            'name' => 'Channel 2',
            'owner' => $user->id
        ]);

        $this->loginAsUser($user);
        $response = $this->json('GET','/api/v1/user/channels');
        $response->assertSuccessful();
        $channels = json_decode($response->getContent());
        $this->assertCount(2,$channels);
        $this->assertEquals($channels[0]->name,'Channel 1');
        $this->assertEquals($channels[0]->owner,$user->id);
        $this->assertEquals($channels[1]->name,'Channel 2');
        $this->assertEquals($channels[1]->owner,$user->id);
    }
}
