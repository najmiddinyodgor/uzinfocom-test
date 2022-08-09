<?php
declare(strict_types=1);

namespace App\Http\Services\UserUpload;

use App\Enums\Role;
use App\Models\User;
use App\Models\UserUpload;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

final class ListingService
{
  public static function paginate(User $user): LengthAwarePaginator
  {
    $query = UserUpload::query();

    if ($user->hasRole(Role::USER->value)) {
      $query = $query->where('user_id', $user->id);
    }

    if ($user->hasRole(Role::MODERATOR->value)) {
      $query = $query->whereIn(
        'user_id',
        User::query()
          ->whereHas('roles', function (Builder $builder) {
            return $builder->where('name', Role::USER->value);
          })
          ->get()
          ->pluck('id')
      );
    }

    return $query
      ->with(UserUpload::RELATION_USER)
      ->paginate(15);
  }
}
