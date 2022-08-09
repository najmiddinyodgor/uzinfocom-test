<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\Rule;
use App\Http\Resources\UserUploadResource;
use App\Http\Response;
use App\Http\Services\UserUpload\ListingService;
use App\Models\UniqueUploadStorage;
use App\Models\UserUpload;
use Gate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class UploadController extends Controller
{
  public function index(): JsonResponse
  {
    return Response::paginate(
      ListingService::paginate(auth()->user()),
      fn(Collection $collection) => UserUploadResource::collection($collection)
    );
  }

  public function store(Request $request): JsonResponse
  {
    $request->validate([
      'file' => ['required', 'file']
    ]);

    $user = auth()->user();

    Gate::authorize(Rule::CAN_UPLOAD->value);

    return Response::success(
      UserUploadResource::make(
        $user
          ->upload($request->file('file'))
          ->load(UserUpload::RELATION_USER)
      )
    );
  }

  public function show(UserUpload $upload): StreamedResponse
  {
    Gate::authorize(Rule::CAN_MANAGE_UPLOAD->value, $upload);

    return UniqueUploadStorage::get($upload->upload->hash);
  }

  public function destroy(UserUpload $upload): JsonResponse
  {
    Gate::authorize(Rule::CAN_MANAGE_UPLOAD->value, $upload);

    $upload->delete();

    return Response::success(null, 204);
  }
}
