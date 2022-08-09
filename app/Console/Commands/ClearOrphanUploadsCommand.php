<?php

namespace App\Console\Commands;

use App\Models\UniqueUpload;
use App\Models\UserUpload;
use Illuminate\Console\Command;
use Illuminate\Database\Query\JoinClause;

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
    $ids = UniqueUpload::query()
      ->whereNotIn('id', UserUpload::all()->pluck('upload_id'))
      ->get()
      ->pluck('id');

    UniqueUpload::destroy($ids);

    return 0;
  }
}
