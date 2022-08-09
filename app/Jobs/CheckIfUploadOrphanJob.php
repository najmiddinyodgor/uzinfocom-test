<?php

namespace App\Jobs;

use App\Models\UniqueUpload;
use App\Models\UserUpload;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckIfUploadOrphanJob implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct(
    private readonly int $uploadId
  )
  {
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    if (!UserUpload::query()->where('upload_id', $this->uploadId)->exists()) {
      UniqueUpload::destroy($this->uploadId);
    }
  }
}
