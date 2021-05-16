<?php

namespace App\Http\Resources;

use App\Models\Auth\User;
use App\Notifications\Welcome;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;


class UserResource
{
    /**
     * Logs a User in
     * @param Request $request
     * @return User
     * @throws \Exception
     */
    public function loginUser(Request $request)
    {
        $email= $request->input('email');
        /** @var User $user */
        $user = User::where(function ($builder) use ($email) {
            $builder->Where('email', $email);
        })->first();
        abort_unless(is_object($user), Response::HTTP_UNAUTHORIZED, 'Unauthorized');
        $verify = Hash::check($request->password, $user->password);
        abort_unless($verify, Response::HTTP_UNAUTHORIZED, 'Unauthorized');

        if (is_null($user->api_token) || !$user->isTokenValid()) {
            $user->refreshToken(false);
        }
        $user->last_seen = Carbon::now();
        $user->save();

        return $user;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function create(Request $request)
    {
        $user = DB::transaction(function () use ($request) {
            // Create User
            $user = new User($request->all());
            $user->password = Hash::make($request->password);
            $user->refreshToken(false);
            $user->last_seen = Carbon::now();
            $user->save();

            return $user;
        });

        try {
            $user->notify(new Welcome);
        } catch (\Exception $e) {
            Log::error($e);
        }
        return $user;
    }

    /** Logs out a User
     * @param Request $request
     */
    public function logout(Request $request)
    {
        $user = $request->user();
        $user->api_token = null;
        $user->save();
    }

    /**
     * update a users password
     * @param Request $request
     */
    public function updatePassword(Request $request)
    {
        $user = $request->user();
        $user->password = Hash::make($request->password);
        $user->save();
    }
}
