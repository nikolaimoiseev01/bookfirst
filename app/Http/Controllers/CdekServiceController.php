<?php

namespace App\Http\Controllers;

use App\Services\Cdek\CdekMapService;
use Illuminate\Http\Request;

class CdekServiceController extends Controller
{
    public function __invoke(Request $request)
    {
        $service = new CdekMapService(
            config('services.cdek.client_id'),
            config('services.cdek.client_secret')
        );

        // передаём GET + JSON тело как раньше
        return $service->process(
            $request->query(),
            $request->getContent()
        );
    }
}
