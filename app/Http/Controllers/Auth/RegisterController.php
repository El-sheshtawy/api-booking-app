<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Services\RegistrationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

/**
 * @group Authentication
 */
class RegisterController extends Controller
{
    /**
     * Register a new user.
     *
     * [Creates a new user and returns a token for authentication.]
     *
     * @response {"access_token":"1|a9ZcYzIrLURVGx6Xe41HKj1CrNsxRxe4pLA2oISo"}
     * @response 422 {"message":"The selected role id is invalid.","errors":{"role_id":["The selected role id is invalid."]}}
     */
    public function __invoke(RegisterRequest $request, RegistrationService $registrationService): JsonResponse
    {
        $userData = $request->validated();

        $user = $registrationService->createNewUser($userData);

        return response()->json([
            'access_token' => $registrationService->createUserToken($user),
        ], Response::HTTP_CREATED);
    }
}
