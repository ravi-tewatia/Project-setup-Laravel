<?php

namespace App\Http\Resources\Api\V1;

use App\Http\Resources\Api\BaseResource;
use Illuminate\Http\Request;

class UserResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return array_merge(parent::toArray($request), [
            'name' => $this->name,
            'email' => $this->email,
        ]);
    }

    /**
     * Get the message for the response.
     *
     * @return string
     */
    protected function getMessage(): string
    {
        return 'User retrieved successfully';
    }
} 