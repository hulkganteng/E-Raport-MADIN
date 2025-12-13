<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Periode;

class EnsureActivePeriod
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if there is an active period
        $activePeriod = Periode::where('is_active', true)->exists();

        if (!$activePeriod) {
            // If it's an AJAX request / expecting JSON
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Tidak ada periode akademik aktif.'], 403);
            }

            // Redirect back or to dashboard with error
            return redirect()->route('dashboard')->with('error', 'Akses ditolak. Tidak ada Periode Akademik (Tahun Ajaran) yang aktif. Hubungi Admin.');
        }

        return $next($request);
    }
}
