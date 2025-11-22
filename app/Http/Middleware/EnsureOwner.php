<?php

namespace App\Http\Middleware;

use App\Models\Collection\Participation;
use App\Models\ExtPromotion\ExtPromotion;
use App\Models\OwnBook\OwnBook;
use App\Models\Work\Work;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Карта параметров роута -> модели
        $map = [
            'participation_id'   => Participation::class,
            'own_book_id'        => OwnBook::class,
            'ext_promotion_id'   => ExtPromotion::class,
            'work_id'            => Work::class,
        ];

        foreach ($map as $param => $modelClass) {
            if ($id = $request->route($param)) {
                $record = $modelClass::find($id);

                // если модель не найдена — 404
                if (! $record) {
                    abort(404);
                }

                // если владелец не совпадает — 403
                if ($record->user_id !== $user->id) {
                    abort(403, 'У вас нет доступа к этой странице.');
                }
            }
        }

        return $next($request);
    }
}
