<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExampleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * @OA\Get(
     *     path="/",
     *     tags={"Extras"},
     *     summary="Ping",
     *     description="For Heath check: perfect for testing service health in production",
     *     @OA\Response(
     *         response="200",
     *         description="Service is active",
     *         @OA\JsonContent()
     *     ),
     *      @OA\Response(
     *         response="503",
     *         description="Service unavailable",
     *         @OA\JsonContent()
     *     )
     * )
     */
    public function ping(): JsonResponse
    {
        return $this->success(['name' => config('app.name')]);
    }
}
