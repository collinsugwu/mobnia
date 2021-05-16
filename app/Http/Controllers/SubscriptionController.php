<?php


namespace App\Http\Controllers;

use App\Http\Resources\SubscriptionResource;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SubscriptionController extends Controller
{
    /**
     * @OA\Get(
     *     path="/plans",
     *     summary="Get all plans",
     *     tags={"Plan"},
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
     *     )
     * )
     * @return \Illuminate\Http\JsonResponse
     */
    public function index() {
       $plans = Plan::all();
       return $this->success($plans);
    }


    /**
     * @OA\Get(
     *     path="/plans/{plan_id}",
     *     summary="Choose a plan",
     *     tags={"Plan"},
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
     *     )
     * )
     * @param Request $request
     * @param $plan_id
     * @param SubscriptionResource $subscriptionResource
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function choosePlan(Request $request, $plan_id, SubscriptionResource $subscriptionResource)
    {
        $user = $request->user();
        //check if a user has an active plan
        abort_if($user->hasActiveSubscription(),
            Response::HTTP_UNPROCESSABLE_ENTITY, 'You have an active subscription');
        $plan = Plan::where('id', $plan_id)->first();

        //creates a subscription
        $subscription = $subscriptionResource->createSubscription($user, $plan);
        return $this->success($subscription);
    }


    /**
     * @OA\Post(
     *     path="/plans/payments/verify",
     *     summary="Verify Payment Made",
     *     tags={"Plan"},
     *     security={{ "apiAuth": {} }},
     *     @OA\Parameter(
     *         name="ref",
     *         in="query",
     *         description="Reference from paystack",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Successful",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response="403",
     *         description="Error: Forbidden",
     *         @OA\JsonContent()
     *     )
     * )
     * @param Request $request
     * @param SubscriptionResource $subscriptionResource
     * @return \Illuminate\Http\JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function verifyPayment(Request $request, SubscriptionResource $subscriptionResource)
    {
        $subscriptionResource->verifyPayment($request);
        return $this->success();
    }
}
