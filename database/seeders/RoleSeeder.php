<?php
declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Role;
use Illuminate\Database\Seeder;

final class RoleSeeder extends Seeder
{
  public function run()
  {
    foreach (Role::values() as $role) {
      \Spatie\Permission\Models\Role::firstOrCreate([
        'name' => $role
      ]);
    }
  }
}
