<?php
declare(strict_types=1);

namespace App\Enums;

enum Rule: string
{
  case CAN_UPLOAD = 'can-upload';
  case CAN_MANAGE_UPLOAD = 'can-manage-upload';
}
