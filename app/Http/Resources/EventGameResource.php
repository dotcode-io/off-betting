<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class EventGameResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'game_number' => $this->game_number,
            'status' => $this->status->label(),
            'meron_name' => $this->meron_entry ?? '-',
            'wala_name' => $this->wala_entry ?? '-',
            'meron_odds' => $this->meron_odds.'%',
            'wala_odds' => $this->wala_odds.'%',
            'meron_bets' => $this->meron_bets,
            'wala_bets' => $this->wala_bets,
            'draw_bets' => $this->draw_bets,
            'meron_bettors' => $this->meron_bettors,
            'wala_bettors' => $this->wala_bettors,
            'draw_bettors' => $this->draw_bettors,
            'result' => $this->result->label(),
            'result_color' => $this->result->color(),
            'result_value' => $this->result->value,

        ];
    }
}
