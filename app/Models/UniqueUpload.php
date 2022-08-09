<?php

namespace App\Models;

use DB;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Throwable;

/**
 * App\Models\UniqueUpload
 *
 * @property int $id
 * @property string $hash
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|User[] $users
 * @property-read int|null $users_count
 * @method static Builder|UniqueUpload newModelQuery()
 * @method static Builder|UniqueUpload newQuery()
 * @method static Builder|UniqueUpload query()
 * @method static Builder|UniqueUpload whereCreatedAt($value)
 * @method static Builder|UniqueUpload whereHash($value)
 * @method static Builder|UniqueUpload whereId($value)
 * @method static Builder|UniqueUpload whereUpdatedAt($value)
 * @mixin Eloquent
 */
class UniqueUpload extends Model
{
  protected $guarded = [];

  // relations
  public function users(): BelongsToMany
  {
    return $this->belongsToMany(
      User::class,
      'user_uploads',
      'upload_id',
      'user_id'
    );
  }

  // relations

  public static function upload(UploadedFile $file): self
  {
    $hash = hash_file('md5', $file->getRealPath());

    if (!$upload = self::where('hash', $hash)->first()) {
      DB::beginTransaction();

      try {
        $upload = new self();
        $upload->hash = $hash;
        $upload->save();

        UniqueUploadStorage::put($file, $hash);

        DB::commit();
      } catch (Throwable $e) {
        DB::rollBack();

        UniqueUploadStorage::delete($hash);

        throw $e;
      }
    }

    return $upload;
  }

  public function delete()
  {
    UniqueUploadStorage::delete($this->hash);

    parent::delete();
  }
}
