<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MessageControllerTest extends TestCase
{
    use RefreshDatabase;
    // CAL ESTAR LOGAT?
    // URL /api/v1/messages GET/POST/PUT/DELETE

    /** @test */
    public function guest_user_can_list_messages()
    {
        // 1 PREPARE
        $user = factory(User::class)->create();
        $message1 = Message::create([
            'title' => 'Ja podeu recollir notes 1r Trimestre',
            'description' => 'Bla bla bla bla...',
            'created_by' => $user->id
        ]);

        $message2 = Message::create([
            'title' => 'Ja podeu recollir notes 2n Trimestre',
            'description' => 'Bla bla bla bla...',
            'created_by' => $user->id
        ]);

        // 2 EXECUCIó HTTP REQUEST AND GET HTTP RESPONSE
        $response = $this->json('GET','/api/v1/messages');
//        dump($response);
        // 3 ASSERT
        $response->assertSuccessful();
        $messages = json_decode($response->getContent());

//        $messages
        $this->assertCount(2,$messages);
        $this->assertIsArray($messages);
        $this->assertEquals($messages[0]->name,$message1->name);
        $this->assertEquals($messages[0]->description,$message1->description);
        $this->assertEquals($messages[1]->name,$message2->name);
        $this->assertEquals($messages[1]->description,$message2->description);
    }

    /** @test */
    public function zero_messages()
    {
        // 2 EXECUCIó HTTP REQUEST AND GET HTTP RESPONSE
        $response = $this->json('GET','/api/v1/messages');
//        dump($response);
        // 3 ASSERT
        $response->assertSuccessful();
        $messages = json_decode($response->getContent());

        $this->assertCount(0,$messages);
        $this->assertIsArray($messages);
    }
}
