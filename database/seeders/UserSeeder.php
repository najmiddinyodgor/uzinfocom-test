<?php
declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

final class UserSeeder extends Seeder
{
  public static $users = [
    [
      'name' => 'admin',
      'email' => 'admin@example.com',
      'role' => Role::ADMIN
    ],
    [
      'name' => 'moderator',
      'email' => 'moderator@example.com',
      'role' => Role::MODERATOR
    ],
    [
      'name' => 'user',
      'email' => 'user@example.com',
      'role' => Role::USER
    ]
  ];

  public function run()
  {
    foreach (self::$users as $userData) {
      $user = new User();
      $user->name = $userData['name'];
      $user->email = $userData['email'];
      $user->password = \Hash::make('admin123');
      $user->save();

      $user->assignRole(\Spatie\Permission\Models\Role::findByName($userData['role']->value));
    }
  }
}
