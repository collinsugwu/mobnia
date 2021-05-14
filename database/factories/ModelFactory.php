<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Auth\User::class, function (Faker\Generator $faker) {
    static $password;
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $faker->email,
        'phone' => $faker->phoneNumber,
        'password' => \Illuminate\Support\Facades\Hash::make($password ?: 'secret'),
        'api_token' => md5(uniqid()),
        'username' => $faker->userName,
        'last_seen' => \Illuminate\Support\Carbon::now()
    ];
});

$factory->define(\App\Models\File::class, function (\Faker\Generator $faker) {
    return [
        'url' => $faker->imageUrl(),
        'thumbnail' => null,
        'completed' => false,
        'type' => 'id-card',
    ];
});

$factory->define(\App\Models\Plan::class, function (\Faker\Generator $faker) {
    return [
        'name' => $faker->word,
        'duration' => 'monthly',
        'amount' => $faker->numberBetween(1000, 10000),
    ];
});

$factory->define(\App\Models\Subscription::class, function (\Faker\Generator $faker) {
    return [
        'reference' => $faker->word,
        'user_id' => null,
        'plan_id' => null,
        'is_active' => null,
        'amount' => null
    ];
});

$factory->define(\App\Models\Payment::class, function (\Faker\Generator $faker) {
    return [
        'subscription_id' => null,
        'start_at' => \Carbon\Carbon::now(),
        'paid_at' => \Carbon\Carbon::now(),
        'payment_ref' => null,
        'authorization' => null,
        'end_at' => \Carbon\Carbon::now()->addMonth(),
    ];
});
