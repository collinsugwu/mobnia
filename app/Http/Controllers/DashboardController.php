<?php


namespace App\Http\Controllers;


class DashboardController extends Controller
{
    /**
     * @OA\Get(
     *     path="/dashboard",
     *     summary="Dashboard details",
     *     tags={"Dashboard"},
     *     security={{ "apiAuth": {} }},
     *     @OA\Response(
     *         response="200",
     *         description="Successful",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response="403",
     *         description="Error: Forbidden",
     *         @OA\JsonContent()
     *     ),
     *    @OA\Response(
     *         response="401",
     *         description="Error: Expired or No Subscription",
     *         @OA\JsonContent()
     *     )
     * )
     */
    public function index()
    {
        return $this->success('you don try');
    }
}
