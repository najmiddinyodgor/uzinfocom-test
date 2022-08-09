<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Enums\Role;
use App\Enums\Rule;
use App\Models\User;
use App\Models\UserUpload;
use Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
  /**
   * The model to policy mappings for the application.
   *
   * @var array<class-string, class-string>
   */
  protected $policies = [
    // 'App\Models\Model' => 'App\Policies\ModelPolicy',
  ];

  /**
   * Register any authentication / authorization services.
   *
   * @return void
   */
  public function boot()
  {
    $this->registerPolicies();

    Gate::before(function (User $user) {
      if ($user->hasRole(Role::ADMIN->value)) {
        return true;
      }
    });

    Gate::define(Rule::CAN_MANAGE_UPLOAD->value, function (User $user, UserUpload $upload) {
      if ($user->hasRole(Role::USER->value)) {
        return $user->id === $upload->user_id;
      }
      if ($user->hasRole(Role::MODERATOR->value)) {
        return $upload->user->hasRole(Role::USER->value);
      }

      return false;
    });

    Gate::define(Rule::CAN_UPLOAD->value, function (User $user) {
      return $user->hasAnyRole(
        Role::ADMIN->value,
        Role::USER->value
      );
    });
  }
}
