<?php
declare(strict_types=1);

namespace Tests;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

final class Helper
{
  public static function createUser(string $password = 'admin123'): User
  {
    $user = new User();
    $user->name = 'najmiddin';
    $user->email = 'najmiddinyodgor@gmail.com';
    $user->password = Hash::make($password);
    $user->save();

    return $user;
  }

  public static function authHeader(string $token): string
  {
    return "Bearer $token";
  }
}
