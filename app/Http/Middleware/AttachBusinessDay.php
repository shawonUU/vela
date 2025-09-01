<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\BusinessDay;

class AttachBusinessDay
{
    
    protected $exceptRoutes = [
        // 'login',
        // 'register',
        // 'business-days.index',
        // 'business-days.open',
        // 'business-days.close',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        if ($request->route() && in_array($request->route()->getName(), $this->exceptRoutes)) {
            $businessDay = BusinessDay::where('status', 'open')->latest()->first();
            if (!$businessDay) {
                return response()->json(['error' => 'No business day is open!'], 400);
            }
            $request->merge(['business_day_id' => $businessDay->id]);
        }

        return $next($request);
    }
}
