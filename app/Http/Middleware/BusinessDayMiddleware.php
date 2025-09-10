<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\BusinessDay;
use App\Models\PaymentDetail;
use App\Models\Expense;

class BusinessDayMiddleware
{
    
    protected $expectedRoutes = [
       'invoice.add',
       'expenses.create',

    ];

    public function handle(Request $request, Closure $next): Response
    {

        $businessDay = BusinessDay::where('status', 'open')->latest()->first();
        if ($businessDay && $businessDay->business_date != now()->toDateString()) {

            $payments = PaymentDetail::whereDate('date', $businessDay->business_date)->get();
            $expense = Expense::whereDate('date', $businessDay->business_date)->get();

            $expense = [
                'total'       => $expense->sum('amount'),
                'cash'        => $expense->where('payment_method', 'cash')->sum('amount'),
                'bkash'       => $expense->where('payment_method', 'bkash')->sum('amount'),
                'nagad'       => $expense->where('payment_method', 'nagad')->sum('amount'),
                'master_card' => $expense->where('payment_method', 'master_card')->sum('amount'),
                'visa_card'   => $expense->where('payment_method', 'visa_card')->sum('amount'),
                'rocket'      => $expense->where('payment_method', 'rocket')->sum('amount'),
                'upay'        => $expense->where('payment_method', 'upay')->sum('amount'),
                'surecash'    => $expense->where('payment_method', 'surecash')->sum('amount'),
                'online'      => $expense->where('payment_method', 'online')->sum('amount'),
            ];

            $businessDay->update([
                'closing_time' => now(),
                'closing_balance'     => $businessDay->opening_balance + $payments->sum('current_paid_amount') - ($expense['total'] ?? 0),
                'closing_cash'        => $businessDay->opening_cash + $payments->where('payment_type', 'cash')->sum('current_paid_amount') - ($expense['cash'] ?? 0),
                'closing_visa_card'   => $businessDay->opening_visa_card + $payments->where('payment_type', 'visa_card')->sum('current_paid_amount') - ($expense['visa_card'] ?? 0),
                'closing_master_card' => $businessDay->opening_master_card + $payments->where('payment_type', 'master_card')->sum('current_paid_amount') - ($expense['master_card'] ?? 0),
                'closing_bkash'       => $businessDay->opening_bkash + $payments->where('payment_type', 'bkash')->sum('current_paid_amount') - ($expense['bkash'] ?? 0),
                'closing_nagad'       => $businessDay->opening_nagad + $payments->where('payment_type', 'nagad')->sum('current_paid_amount') - ($expense['nagad'] ?? 0),
                'closing_rocket'      => $businessDay->opening_rocket + $payments->where('payment_type', 'rocket')->sum('current_paid_amount') - ($expense['rocket'] ?? 0),
                'closing_upay'        => $businessDay->opening_upay + $payments->where('payment_type', 'upay')->sum('current_paid_amount') - ($expense['upay'] ?? 0),
                'closing_surecash'    => $businessDay->opening_sureCash + $payments->where('payment_type', 'surecash')->sum('current_paid_amount') - ($expense['surecash'] ?? 0),
                'closing_online'      => $businessDay->opening_online + $payments->where('payment_type', 'online')->sum('current_paid_amount') - ($expense['online'] ?? 0),
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
