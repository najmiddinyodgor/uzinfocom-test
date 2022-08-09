<?php
declare(strict_types=1);

namespace App\Http;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

class Response extends JsonResponse
{
  public static function new(bool $success, $data, int $status): self
  {
    return new self([
      'success' => $success,
      'status' => $status,
      'data' => $data
    ], 200
    );
  }

  public static function success($data = null, int $status = 200): self
  {
    return self::new(true, $data ?? [], $status);
  }

  public static function error($data = null, int $status = 500): self
  {
    return self::new(false, $data ?? [], $status);
  }

  public static function paginate(LengthAwarePaginator $paginator, Closure $wrapper): self
  {
    return self::success([
      'items' => $wrapper($paginator->items()),
      'links' => [
        'prev' => $paginator->previousPageUrl(),
        'next' => $paginator->nextPageUrl()
      ]
    ]);
  }

  public function withMessages(array $messages): self
  {
    $this->addData([
      'messages' => $messages
    ]);

    return $this;
  }

  private function addData(array $data)
  {
    $this->setData(array_merge(
      $this->getData(true),
      $data
    ));
  }
}
