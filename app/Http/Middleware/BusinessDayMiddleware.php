<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\BusinessDay;
use App\Models\Payment;
use App\Models\Expense;

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
            $expense = Expense::whereDate('date', $businessDay->business_date)->get();

            $expense = [
                'total'       => $expense->sum('amount'),
                'cash'        => $expense->where('payment_method', 'cash')->sum('amount'),
                'bkash'       => $expense->where('payment_method', 'bkash')->sum('amount'),
                'nagad'       => $expense->where('payment_method', 'nagad')->sum('amount'),
                'master_card' => $expense->where('payment_method', 'master_card')->sum('amount'),
                'visa_card'   => $expense->where('payment_method', 'visa_card')->sum('amount'),
                'Rocket'      => $expense->where('payment_method', 'Rocket')->sum('amount'),
                'Upay'        => $expense->where('payment_method', 'Upay')->sum('amount'),
                'SureCash'    => $expense->where('payment_method', 'SureCash')->sum('amount'),
                'online'      => $expense->where('payment_method', 'online')->sum('amount'),
            ];

            $businessDay->update([
                'closing_time' => now(),
                'closing_balance'     => $businessDay->opening_balance + $payments->sum('paid_amount') - ($expense['total'] ?? 0),
                'closing_cash'        => $businessDay->opening_cash + $payments->sum('cash') - ($expense['cash'] ?? 0),
                'closing_visa_card'   => $businessDay->opening_visa_card + $payments->sum('visa_card') - ($expense['visa_card'] ?? 0),
                'closing_master_card' => $businessDay->opening_master_card + $payments->sum('master_card') - ($expense['master_card'] ?? 0),
                'closing_bkash'       => $businessDay->opening_bkash + $payments->sum('bKash') - ($expense['bkash'] ?? 0),
                'closing_nagad'       => $businessDay->opening_nagad + $payments->sum('Nagad') - ($expense['nagad'] ?? 0),
                'closing_rocket'      => $businessDay->opening_rocket + $payments->sum('Rocket') - ($expense['Rocket'] ?? 0),
                'closing_upay'        => $businessDay->opening_upay + $payments->sum('Upay') - ($expense['Upay'] ?? 0),
                'closing_surecash'    => $businessDay->opening_sureCash + $payments->sum('SureCash') - ($expense['SureCash'] ?? 0),
                'closing_online'      => $businessDay->opening_online + $payments->sum('online') - ($expense['online'] ?? 0),
                'status'                 => 'closed'
            ]);

            $businessDay = null;
        }

        if ($request->route() && in_array($request->route()->getName(), $this->expectedRoutes)) {
            if (!$businessDay) {
                $notification = array(
                    'message' => 'Please Open a Business Day!',
                    'alert-type' => 'error'
                );
                return redirect()->route('business-days.index')->with($notification);
            }
        }

        return $next($request);
    }
}
