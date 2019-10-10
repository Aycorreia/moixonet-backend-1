<?php

namespace Tests\Feature;

use App\Channel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class ChannelsControllerTest
 * @package Tests\Feature
 * @covers \App\Http\Controllers\ChannelsController
 */
class ChannelsControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_returns_no_channels_if_database_is_void() {
        $response = $this->json('GET','/api/v1/channels');

        $result = $response->getContent();
        $result2 = json_decode($result);
        $response->assertSuccessful();
//        $response->assertStatus(200);
        $this->assertEmpty($result2);
        $this->assertEquals($result,"[]");
    }

    /**
     *
     * @test
     */
    public function it_returns_channels()
    {
        $this->withoutExceptionHandling();
        // 1 PREPARE
        // MIGRATIONS I SEEDS -> CREAR TAULES I OMPLIR-LES
        // Facades
        Channel::create([
            'name' => 'Canal 1'
        ]);

        Channel::create([
            'name' => 'Canal 2'
        ]);

        Channel::create([
            'name' => 'Canal 3'
        ]);


        // 2 EXECUTE
//        HTTP GET /api/v1/channels
        $response = $this->json('GET','/api/v1/channels');

        // 3 COMPROVAR -> ASSERT

        $result = $response->getContent();
        $channels = json_decode($result);
        $response->assertSuccessful();
        $this->assertCount(3,$channels);
        $this->assertEquals($channels[0]->name,'Canal 1');
        $this->assertEquals($channels[1]->name,'Canal 2');
        $this->assertEquals($channels[2]->name,'Canal 3');


    }

    // MP9 SEGURETAT -> LOGIN

    /** @test */
    public function guest_user_can_not_add_channels()
    {
        // 1 PREPARE
//        $this->withoutExceptionHandling();

        // 2 EXECUTE
        $response = $this->json('POST','/api/v1/channels');

        // 3 ASSERT
        $response->assertStatus(401);
    }

    /** @test */
    public function regular_user_can_add_channels_validation()
    {
        //1 PREPARE
        $this->login();
        // 422 Error de validació
        $response = $this->json('POST','/api/v1/channels');
        $response->assertStatus(422);
        $result = json_decode($response->getContent());

        $this->assertEquals($result->message,'The given data was invalid.');
        $this->assertEquals($result->errors->name[0],'The name field is required.');
    }

    /** @test */
    public function regular_user_can_add_channels()
    {
        // 1 PREPARE
        $this->login();

        $this->assertNull(Channel::first());

        // 2 EXECUTE
        $response = $this->json('POST','/api/v1/channels',[
            'name' => 'Canal 1'
        ]);
        $response->assertSuccessful();
        $channelResponse = json_decode($response->getContent());
        $this->assertNotNull($channel = Channel::first());
        $this->assertEquals($channel->name,'Canal 1');

        $this->assertEquals($channelResponse->name,'Canal 1');
        $this->assertNotNull($channelResponse->id);
        $this->assertNotNull($channelResponse->created_at);
        $this->assertNotNull($channelResponse->updated_at);

        // 3 ASSERT
        $response->assertSuccessful();

        // Ha d'existir un nou canala a la base de dades TODO
        // Channel::first()

//        $this->assertDatabaseHas('channels', [
//            'name' => 'Canal 1'
//        ]);

    }

    //    /** @test */
//    public function channels_manager_user_user_can_add_channels()
//    {
//
//    }

    // URL ENDPOINT API
    // DELETE /api/v1/channels/{id}
    // ROUTE MODEL BINDING
    // 200 --> OK
    // 401 --> NOT LOGGED


    /** @test */
    public function guest_user_cannot_remove_channels()
    {
        // PROTOCOL HTTP -> SEND HTTP REQUEST I OBTENIM HTTP RESPONSE
        $response = $this->json('DELETE','/api/v1/channels/1');
        $response->assertStatus(401);
    }

    /** @test */
    public function regular_user_can_remove_channels()
    {
        $this->login();
        $channel = Channel::create([
            'name' => 'Canal 1'
        ]);
        $response = $this->json('DELETE','/api/v1/channels/'  . $channel->id);
        $response->assertSuccessful();

        $this->assertNull(Channel::find($channel->id));
    }

    /** @test */
    public function guest_user_can_show_channel()
    {
        $channel = Channel::create([
            'name' => 'Canal 1'
        ]);
        $response = $this->json('GET','/api/v1/channels/'  . $channel->id);
        $response->assertSuccessful();
        $channelObj = json_decode($response->getContent());
        $this->assertEquals($channelObj->id,$channel->id);
        $this->assertEquals($channelObj->name,$channel->name);
    }

    /** @test */
    public function guest_user_cannot_update_channel()
    {
        $response = $this->json('PUT','/api/v1/channels/1');
        $response->assertStatus(401);
    }

    /** @test */
    public function regular_user_can_update_channel()
    {
        $this->login();
        $channel = Channel::create([
            'name' => 'Canal 1'
        ]);
        $response = $this->json('PUT','/api/v1/channels/'  . $channel->id,[
            'name' => 'Nou nom del canal 1'
        ]);
        $response->assertSuccessful();
        $newChannel = Channel::find($channel->id); // $channel->refresh();
        $this->assertEquals($newChannel->name, 'Nou nom del canal 1');
    }

    /** @test */
    public function regular_user_can_update_channel_validation()
    {
        $this->login();
        $channel = Channel::create([
            'name' => 'Canal 1'
        ]);
        $response = $this->json('PUT','/api/v1/channels/' . $channel->id,[]);
        $response->assertStatus(422);
    }
}
