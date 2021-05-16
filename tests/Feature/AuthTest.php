<?php

namespace Tests\Feature;

use App\Models\Auth\User;
use App\Notifications\Welcome;
use Faker\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use DatabaseMigrations;

    public function testUserRegistration()
    {
        $faker = Factory::create();
        $data['first_name'] = $faker->firstName;
        $data['last_name'] = $faker->lastName;
        $data['other_names'] = $faker->firstName;
        $data['email'] = $faker->email;
        $data['phone'] = $faker->phoneNumber;
        $data['password'] = $password = $faker->password;
        $data['password_confirmation'] = $password;
        $data['terms'] = 'on';

        Notification::fake();
        $this->sendPost('register', $data);
        $this->assertSuccessResponse();

        $user = User::where(['email' => $data['email']])->first();
        $this->assertNotNull($user);
        Notification::assertSentTo($user, Welcome::class);
    }

    /**
     * @return mixed
     */
    public function testLogin()
    {
        $password = 'test-password';
        /** @var User $user */
        $user = \factory(User::class)->create(['password' => Hash::make($password)]);
        //With email
        $data['email'] = $user->email;
        $data['password'] = $password;
        $this->sendPost('login', $data);
        $this->assertSuccessResponse();
        $this->assertResponseStructure(['data' => ['token', 'user']]);
    }

    public function testLogout()
    {
        /** @var User $user */
        $user = \factory(User::class)->create();
        $this->loginAs($user);

        $this->sendPost('/logout');
        $this->assertSuccessResponse();

        //Assert token was cleared
        $user->refresh();
        $this->assertNull($user->api_token);
    }
}
