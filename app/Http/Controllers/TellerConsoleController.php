<?php

declare(strict_types=1);

namespace App\Http\Controllers;

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
        $event = Event::query()->where('status', EventStatus::OPENED)->find();

        if (! $event) {
            return response()->json(['message' => 'No opened event found'], 404);
        }

        return response()->json([
            'event' => $event,
            'game' => EventGameResource::make($event->getCurrentGame())->resolve(),
        ], 200);
    }
}
