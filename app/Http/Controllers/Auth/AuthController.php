<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;


class AuthController extends Controller
{

    /**
     * @OA\Post(
     *     path="/login",
     *     summary="Login a user and obtain token",
     *     tags={"Auth"},
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="User's email or username",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="User's password",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Successful",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Error: Page not found",
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Error: Bad request",
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Error: Unauthorized request",
     *     ),
     * )
     *
     * Handles login
     * @param Request $request
     * @param UserResource $userResource
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Exception
     */
    public function login(Request $request, UserResource $userResource)
    {
        $this->validate($request, [
            'email' => 'required|string|max:255',
            'password' => 'required|string',
        ]);
        //Handles Login
        $user = $userResource->loginUser($request);
        return $this->success([
            'token' => $user->api_token,
            'user' => $user
        ]);
    }


    /**
     * @OA\Post(
     *     path="/register",
     *     summary="Registers a user",
     *     tags={"Auth"},
     *     @OA\Parameter(
     *         name="first_name",
     *         in="query",
     *         description="First name of the user",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="last_name",
     *         in="query",
     *         description="Last Name of the User",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="Email of User",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="Password",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="password_confirmation",
     *         in="query",
     *         description="Password Confirmation",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Successful",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Validation Error",
     *     ),
     * )
     *
     * Handles Registration
     * @param Request $request
     * @param UserResource $userResource
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function registerUser(Request $request, UserResource $userResource)
    {
        $userNameRegex = usernameRegex();
        $this->validate($request, [
            'first_name' => 'required|string|min:1',
            'last_name' => 'required|string|min:1',
            'other_names' => 'string|min:1',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Handles creating a user
        $user = $userResource->create($request);
        return $this->success([
            'token' => $user->api_token
        ]);
    }


    /**
     * @OA\Post(
     *     path="/logout",
     *     summary="Destroys user token",
     *     tags={"Auth"},
     *     security={{ "apiAuth": {} }},
     *     @OA\Response(
     *         response="200",
     *         description="Successful",
     *         @OA\JsonContent()
     *     )
     * )
     * Logout a User
     * @param Request $request
     * @param UserResource $userResource
     * @return \Illuminate\Http\JsonResponse
     */

    public function logout(Request $request, UserResource $userResource)
    {
        $userResource->logout($request);
        return $this->success();
    }
}
