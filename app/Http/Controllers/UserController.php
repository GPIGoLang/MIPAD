<?php
namespace App\Http\Controllers;
use App\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    protected function buildFailedValidationResponse(Request $request, array $errors)
    {
        return new JsonResponse($errors, 422);
    }
    public function getProfile($username)
    {
        $user = User::with('profile')
            ->where('username', $username)
            ->first();
        if($user)
        {
            return response()->json([
                'found'=>true,
                'data'=>$user
            ]);
        }
        return response()->json([
            'found'=>false
        ]);
    }
}