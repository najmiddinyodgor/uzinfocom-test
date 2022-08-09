<?php
declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\UniqueUploadStorage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\Helper;
use Tests\TestCase;

final class UserUploadTest extends TestCase
{
  use RefreshDatabase;

  public function test_upload_success()
  {
    $user = Helper::createUser();
    $file = UploadedFile::fake()->create('file');

    $upload = $user->upload($file);

    $this->assertEquals($file->getClientOriginalName(), $upload->name);
    $this->assertTrue(UniqueUploadStorage::exists($upload->upload->hash));
  }

  public function test_should_be_same_upload_instance()
  {
    $user = Helper::createUser();
    $file1 = UploadedFile::fake()->create('file1');
    $upload1 = $user->upload($file1);

    $file2 = UploadedFile::fake()->create('file2');
    $upload2 = $user->upload($file2);

    $this->assertEquals($upload1->upload->id, $upload2->upload->id);
  }
}
