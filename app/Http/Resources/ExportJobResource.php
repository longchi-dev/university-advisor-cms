<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ExportJobResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'job_id' => $this->job_id,
            'status' => $this->status,
            'total' => $this->total,
            'percent_process' => $this->total > 0 ? ($this->processed / $this->total * 100) : 0,
            'processed' => $this->processed,
            'file_path' => $this->file_path,
            'file_url' => url('/storage/' .$this->file_path),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
