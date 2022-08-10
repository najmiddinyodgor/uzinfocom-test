<?php

namespace App\Console\Commands;

use App\Models\UniqueUpload;
use App\Models\UserUpload;
use Illuminate\Console\Command;
use Illuminate\Database\Query\JoinClause;
use Ramsey\Collection\Collection;

class ClearOrphanUploadsCommand extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'clear:orphan-uploads';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Removes all orphaned uploads and clear database';

  /**
   * Execute the console command.
   *
   * @return int
   */
  public function handle()
  {
    UniqueUpload::chunk(25, function ($uploads) {
      /** @var UniqueUpload[] $uploads */
      foreach ($uploads as $upload) {
        if (!$upload->userUpload()->exists()) {
          $upload->delete();
        }
      }
    });

    return 0;
  }
}
