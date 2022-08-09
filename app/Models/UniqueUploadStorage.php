<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class UniqueUploadStorage
{
  public const UPLOAD_FOLDER = 'unique-uploads';

  public static function path(string $hash): string
  {
    return self::UPLOAD_FOLDER . '/' . $hash;
  }

  public static function exists(string $hash): bool
  {
    return self::storage()->exists(self::path($hash));
  }

  public static function put(UploadedFile $file, string $hash): void
  {
    self::storage()->putFileAs(self::UPLOAD_FOLDER, $file, $hash);
  }

  public static function delete(string $hash): void
  {
    if (self::exists($hash)) {
      self::storage()->delete(self::path($hash));
    }
  }

  public static function get(string $hash): StreamedResponse
  {
    return self::storage()->download(self::path($hash));
  }

  private static function storage(): FilesystemAdapter
  {
    return Storage::disk('local');
  }
}
