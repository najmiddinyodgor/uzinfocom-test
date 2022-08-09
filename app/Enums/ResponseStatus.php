<?php
declare(strict_types=1);

namespace App\Enums;

enum ResponseStatus: int
{
  case AUTH_REQUIRED = 401;
}
