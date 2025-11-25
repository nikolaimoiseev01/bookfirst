<?php

namespace App\Http\Controllers;

use App\Services\CdekService;
use Illuminate\Http\Request;

class CdekServiceController extends Controller
{
    public function __invoke(Request $request)
    {
        $service = new CdekService(
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
