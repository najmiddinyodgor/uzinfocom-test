<?php

namespace App\Models;

use Database\Factories\UserFactory;
use DB;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\UploadedFile;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use Throwable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static UserFactory factory(...$parameters)
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User query()
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereEmailVerifiedAt($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereName($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @mixin Eloquent
 * @property-read Collection|Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read Collection|Role[] $roles
 * @property-read int|null $roles_count
 * @property-read Collection|UniqueUpload[] $uploads
 * @property-read int|null $uploads_count
 * @method static Builder|User permission($permissions)
 * @method static Builder|User role($roles, $guard = null)
 */
class User extends Authenticatable implements JWTSubject
{
  use HasFactory, Notifiable, HasRoles;

  protected $fillable = [
    'name',
    'email',
    'password',
  ];

  protected $hidden = [
    'password',
  ];

  protected $casts = [
    'email_verified_at' => 'datetime',
  ];

  // jwt
  public function getJWTIdentifier()
  {
    return $this->getKey();
  }

  public function getJWTCustomClaims()
  {
    return array_merge(
      $this->toArray(),
      [
        'roles' => $this->getRoleNames()
      ]
    );
  }
  // jwt

  // relations
  public function uploads(): BelongsToMany
  {
    return $this->belongsToMany(
      UniqueUpload::class,
      'user_uploads',
      'user_id',
      'upload_id'
    );
  }

  // relations

  public function upload(UploadedFile $file): UserUpload
  {
    $upload = UniqueUpload::upload($file);

    DB::beginTransaction();
    try {
      $userUpload = new UserUpload();
      $userUpload->name = $file->getClientOriginalName();
      $userUpload->user_id = $this->id;
      $userUpload->upload_id = $upload->id;
      $userUpload->save();

      DB::commit();

      return $userUpload;
    } catch (Throwable $e) {
      DB::rollBack();

      throw $e;
    }
  }
}
