<?php
declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\UniqueUpload;
use App\Models\UniqueUploadStorage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

final class UniqueUploadTest extends TestCase
{
  use RefreshDatabase;

  public function test_upload_success()
  {
    $file = UploadedFile::fake()->create('test');

    $upload = UniqueUpload::upload($file);

    $this->assertTrue(UniqueUploadStorage::exists($upload->hash));
  }

  public function test_file_should_be_deleted_with_model()
  {
    $file = UploadedFile::fake()->create('test');

    $upload = UniqueUpload::upload($file);

    $upload->delete();
    $this->assertFalse(UniqueUploadStorage::exists($upload->hash));
  }
}
