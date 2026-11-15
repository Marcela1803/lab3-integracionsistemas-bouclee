<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PedidoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'cliente' => $this->cliente,
            'estado' => $this->estado,
            'total' => $this->total,
            'fecha_entrega' => $this->fecha_entrega,
            // whenLoaded evita consultas innecesarias y carga la relación solo si se solicita
            'user' => new UserResource($this->whenLoaded('user')),
            'detalles' => DetallePedidoResource::collection($this->whenLoaded('detalles')),
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}
