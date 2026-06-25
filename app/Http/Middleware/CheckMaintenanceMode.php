<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;

class CheckMaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // İstek yapılan sayfa login sayfası veya login post rotası ise direkt pas geç
        if ($request->is('login*')) {
            return $next($request);
        }

        // Bakım modu açık mı kontrol et
        $isMaintenance = Setting::getVal('maintenance_mode', false);

        // Eğer bakım modundaysa ve kullanıcı giriş yapmamışsa 503 döndür
        if ($isMaintenance && !Auth::check()) {
            $message = Setting::getVal('maintenance_message', __('Our site is currently being updated. Please visit again later.'));
            abort(503, $message);
        }

        return $next($request);
    }
}