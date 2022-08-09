<?php

namespace App\Http\Resources;

use App\Models\UserUpload;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property UserUpload $resource
 */
class UserUploadResource extends JsonResource
{
  public function toArray($request)
  {
    return [
      'id' => $this->resource->id,
      'name' => $this->resource->name,
      'user_id' => $this->resource->user_id,
      'url' => url(route('uploads.show', ['upload' => $this->resource->id])),
      'user' => UserResource::make($this->whenLoaded(UserUpload::RELATION_USER))
    ];
  }
}
