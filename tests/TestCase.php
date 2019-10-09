<?php

namespace Tests;

use App\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\Feature\ChannelsControllerTest;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function login($email = null, $password = null, $name = null)
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

    /**
     * @param $user
     */
    public function loginAsUser($user): void
    {
        Auth::login($user);
    }
}
