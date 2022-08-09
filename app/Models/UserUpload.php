<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\UserUpload
 *
 * @property int $id
 * @property string $name
 * @property int $user_id
 * @property int $upload_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read UniqueUpload $upload
 * @property-read User $user
 * @method static Builder|UserUpload newModelQuery()
 * @method static Builder|UserUpload newQuery()
 * @method static Builder|UserUpload query()
 * @method static Builder|UserUpload whereCreatedAt($value)
 * @method static Builder|UserUpload whereId($value)
 * @method static Builder|UserUpload whereName($value)
 * @method static Builder|UserUpload whereUpdatedAt($value)
 * @method static Builder|UserUpload whereUploadId($value)
 * @method static Builder|UserUpload whereUserId($value)
 * @mixin Eloquent
 */
class UserUpload extends Model
{
  public const RELATION_USER = 'user';
  public const RELATION_UPLOAD = 'upload';

  protected $guarded = [];

  protected $with = [
    self::RELATION_UPLOAD
  ];

  // relations
  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  public function upload(): BelongsTo
  {
    return $this->belongsTo(
      UniqueUpload::class,
      'upload_id'
    );
  }
  // relations
}
