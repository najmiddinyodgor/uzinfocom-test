<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

final class AuthController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth:api', ['except' => ['login']]);
  }

  public function login(Request $request): JsonResponse
  {
    $request->validate([
      'email' => ['required', 'email', Rule::exists('users', 'email')],
      'password' => ['required', 'string']
    ]);

    if (!$token = auth()->attempt($request->only(['email', 'password']))) {
      abort(401);
    }

    return Response::success([
      'token' => $token
    ]);
  }

  public function me(): JsonResponse
  {
    return Response::success(
      auth()->user()
    );
  }

  public function logout(): JsonResponse
  {
    auth()->logout();

    return Response::success(null, 204);
  }

  public function refresh(): JsonResponse
  {
    return Response::success([
      'token' => auth()->refresh()
    ]);
  }
}
