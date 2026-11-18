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
            'postre' => $this->postre,
            'fecha_entrega' => $this->fecha_entrega,
            'estado' => $this->estado,
            'notas' => $this->notas,
            'user' => new UserResource($this->whenLoaded('user')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            
            // whenLoaded evita consultas innecesarias y carga la relación solo si se solicita
        
        ];
    }
}
