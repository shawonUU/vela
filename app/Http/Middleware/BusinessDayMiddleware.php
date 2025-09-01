<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\BusinessDay;

class BusinessDayMiddleware
{
    
    protected $expectedRoutes = [
       'invoice.add',

    ];

    public function handle(Request $request, Closure $next): Response
    {

        $businessDay = BusinessDay::where('status', 'open')->latest()->first();
        if ($businessDay && $businessDay->business_date != now()->toDateString()) {

            $payments = Payment::whereDate('created_at', $businessDay->business_date)->get();

            $businessDay->update([
                'closing_time' => now(),
                'closing_balance'        => $payments->sum('paid_amount'),
                'closing_cash'           => $payments->sum('cash'),
                'closing_visa_card'      => $payments->sum('visa_card'),
                'closing_master_card'    => $payments->sum('master_card'),
                'closing_bkash'          => $payments->sum('bKash'),
                'closing_nagad'          => $payments->sum('Nagad'),
                'closing_rocket'         => $payments->sum('Rocket'),
                'closing_upay'           => $payments->sum('Upay'),
                'closing_surecash'       => $payments->sum('SureCash'),
                'closing_online'         => $payments->sum('online'),
                'status'                 => 'closed'
            ]);
            
            $businessDay = null;
        }

        if ($request->route() && in_array($request->route()->getName(), $this->expectedRoutes)) {
            if (!$businessDay) {
                return response()->json(['error' => 'No business day is open!'], 400);
            }
        }

        return $next($request);
    }
}
