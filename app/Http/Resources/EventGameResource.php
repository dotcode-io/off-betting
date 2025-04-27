<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Override;

final class EventGameResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    #[Override]
    public function toArray(Request $request): array
    {

        $showOdds = $this->meron_bets > 0 && $this->wala_bets > 0;

        return [
            'id' => $this->id,
            'game_number' => $this->game_number,
            'status' => $this->status->label(),
            'status_color' => $this->status->color(),
            'meron_name' => $this->meron_entry ?? '-',
            'wala_name' => $this->wala_entry ?? '-',
            'meron_odds' => $showOdds ? number_format((float) $this->meron_odds, 2).'%': '-',
            'wala_odds' => $showOdds ? number_format((float) $this->wala_odds, 2).'%': '-',
            'meron_bets' => number_format((float) $this->meron_bets, 2),
            'wala_bets' => number_format((float) $this->wala_bets, 2),
            'draw_bets' => number_format((float) $this->draw_bets, 2),
            'meron_bettors' => $this->meron_bettors,
            'wala_bettors' => $this->wala_bettors,
            'draw_bettors' => $this->draw_bettors,
            'result' => $this->result->label(),
            'result_color' => $this->result->color(),
            'result_value' => $this->result->value,
            'wala_charge' => number_format((float) $this->wala_charge,2),
            'meron_charge' =>  number_format((float) $this->meron_charge,2),
            'meron_open' =>(bool) $this->meron_charge > 0,
            'wala_open' => (bool) $this->wala_charge > 0,

        ];
    }
}
