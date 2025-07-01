<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Enums\EventStatus;
use App\Http\Resources\EventGameResource;
use App\Models\Event;
use Exception;
use Illuminate\Http\JsonResponse;

final class TellerConsoleController
{
    /**
     * @throws Exception
     */
    public function index(): JsonResponse
    {
        $event = Event::query()
            ->where('status', EventStatus::OPENED)->latest()->first();

        if (! $event) {
            return response()->json(['message' => 'No opened event found'], 404);
        }

        return response()->json([
            'event' => $event->only(['uuid', 'name', 'date']),
            'game' => EventGameResource::make($event->getCurrentGame()),
        ], 200);
    }
}
