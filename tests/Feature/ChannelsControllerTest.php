<?php

namespace Tests\Feature;

use App\Channel;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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

    public function login($email=null,$password=null,$name = null)
    {
        if ($email === null) $email = 'sergiturbadenas@gmail.com';
        if ($password === null) $password = '12345678';
        if ($name === null) $name = 'Sergi';
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password)
        ]);
        $this->loginAsUser($user);
    }

//    /** @test */
//    public function channels_manager_user_user_can_add_channels()
//    {
//
//    }
    /**
     * @param $user
     */
    public function loginAsUser($user): void
    {
        Auth::login($user);
    }
}
