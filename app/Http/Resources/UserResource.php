<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $cpfLimpo = preg_replace('/\D/', '', $this->cpf);

        $cpfMascarado = $cpfLimpo
            ? substr($cpfLimpo, 0, 3).'.'.substr($cpfLimpo, 3, 1).'**.***-**'
            : null;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'avatar' => $this->avatar,
            'birth_date' => $this->birth_date,
            'created_at' => $this->created_at,
            'cpf' => $cpfMascarado,
        ];
    }
}
