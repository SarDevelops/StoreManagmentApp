<?php

namespace App\Helpers;

use App\Models\Log;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Auth;

class AuditHelper
{
    public static function log(
        string $action,
        string $status,
        ?string $message = null,
        mixed $model = null,
        array $payload = []
    ): void {

        $agent = new Agent();
        Log::create([
            'user_id' => Auth::id(),

            'action' => $action,
            'status' => $status,
            'message' => $message,
            'ip_address' => request()->ip() ?? 'unknown',
            'browser' => $agent->browser() ?? 'unknown',
            'platform' => $agent->platform() ?? 'unknown',
            'device' => $agent->device() ?? 'desktop',

            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model?->id,

            'payload' => $payload,
        ]);
    }
}
