<?php

namespace Tests\Feature;

use App\Message;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessageControllerTest extends TestCase
{
    use RefreshDatabase;
    // CAL ESTAR LOGAT?
    // URL /api/v1/messages GET/POST/PUT/DELETE

    /** @test */
    public function guest_user_can_list_messages()
    {
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

        // 3 ASSERT
        $response->assertSuccessful();
        $messages = json_decode($response->getContent());
        $this->assertCount(2,$messages);
        $this->assertIsArray($messages);
        $this->assertEquals($messages[0]->title,$message1->title);
        $this->assertEquals($messages[0]->description,$message1->description);
        $this->assertEquals($messages[1]->title,$message2->title);
        $this->assertEquals($messages[1]->description,$message2->description);
    }

    /** @test */
    public function guest_user_can_list_message()
    {
        $user = factory(User::class)->create();
        $message1 = Message::create([
            'title' => 'Ja podeu recollir notes 1r Trimestre',
            'description' => 'Bla bla bla bla...',
            'created_by' => $user->id
        ]);

        // 2 EXECUCIó HTTP REQUEST AND GET HTTP RESPONSE
        $response = $this->json('GET','/api/v1/messages/' . $message1->id);
        // OCO a la URL /api/v1/messages/{id} /api/v1/messages/1
        // return Message::find($request->id)
        // MessagesController::show -> Objecte request

        // 3 ASSERT
        $response->assertSuccessful();
        $message = json_decode($response->getContent());
        $this->assertEquals($message->title,$message1->title);
        $this->assertEquals($message->description,$message1->description);
    }



    /** @test */
    public function zero_messages()
    {
        $response = $this->json('GET','/api/v1/messages');
        $response->assertSuccessful();
        $messages = json_decode($response->getContent());

        $this->assertCount(0,$messages);
        $this->assertIsArray($messages);
    }

    // 401 -> Login 422 -> Validació 403 -> Autorització

    // 401
    /** @test */
    public function guest_user_cannot_post_messages()
    {
        $response = $this->json('POST','/api/v1/messages');
        $response->assertStatus(401);
    }

    /** @test */
    public function regular_user_can_post_messages_validation()
    {
        $this->login();
        $response = $this->json('POST','/api/v1/messages',[]);
        $response->assertStatus(422);
    }

    /** @test */
    public function regular_user_can_post_messages()
    {
        $user = $this->login();

        $this->assertNull(Message::first());
        $response = $this->json('POST','/api/v1/messages',[
            'title' => 'Ja podeu recollir notes 1r Trimestre',
            'description' => 'Bla bla bla',
            // $request->user->id
        ]);
        $response->assertSuccessful();
        $messageResponse = json_decode($response->getContent());
        $this->assertEquals($messageResponse->title, 'Ja podeu recollir notes 1r Trimestre');
        $this->assertEquals($messageResponse->description, 'Bla bla bla');
        $this->assertNotNull($messageResponse->id);
        $this->assertNotNull($messageResponse->created_at);
        $this->assertNotNull($messageResponse->updated_at);
        $this->assertEquals($messageResponse->created_by, $user->id);

        // BASE DADES ARA ESTA PLENA
        $this->assertNotNull($message = Message::first());
        $this->assertEquals($message->title, 'Ja podeu recollir notes 1r Trimestre');
        $this->assertEquals($message->description, 'Bla bla bla');
        $this->assertNotNull($message->id);
        $this->assertNotNull($message->created_at);
        $this->assertNotNull($message->updated_at);
        $this->assertEquals($message->created_by, $user->id);
    }
}
