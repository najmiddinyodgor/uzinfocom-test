<?php
declare(strict_types=1);

namespace App\Enums;

use Cerbero\Enum\Concerns\Enumerates;

enum Role: string
{
  use Enumerates;

  case ADMIN = 'admin';
  case MODERATOR = 'moderator';
  case USER = 'user';
}
