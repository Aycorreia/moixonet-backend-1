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
}
