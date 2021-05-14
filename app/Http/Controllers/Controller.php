<?php

namespace App\Http\Controllers;

use App\Traits\HandlesResponse;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use HandlesResponse;

    /**
     * @OA\Info(
     *   title="Template API",
     *   version="1.0",
     *   @OA\Contact(
     *     email="dev@invogeservices.com",
     *     name="Development Team"
     *   )
     * )
     * @OA\SecurityScheme(
     *     type="apiKey",
     *     description="Enter API token, prefixed with 'Bearer' and a space. e.g Bearer 77e1c83b-7bb0-437b-bc50-a7a58e5660ac",
     *     name="Authorization",
     *     in="header",
     *     scheme="bearer",
     *     bearerFormat="JWT",
     *     securityScheme="apiAuth",
     * )
     *
     * @OA\Server(url="https://api.template.com",description="Live OpenApi Server")
     * @OA\Server(url="http://localhost:9400",description="Local PMAT Server")
     *
     * @OA\Tag(name="Auth",description="Everything about authentication")
     * @OA\Tag(name="User",description="User details")
     * @OA\Tag(name="Notifications",description="User notifications")
     * @OA\Tag(name="Extras",description="Uncategorized")
     * @OA\Tag(name="Admin",description="Admin routes (Not in use)")
     */
}
