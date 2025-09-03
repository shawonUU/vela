<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusinessDay;
use App\Models\Payment;
use App\Models\Expense;

class BusinessDayController extends Controller
{
    /**
     * সব Business Day দেখানো
     */
    public function index()
    {
        $businessDay = BusinessDay::where('status', 'open')->latest()->first();
        $previousDay = BusinessDay::where('status', 'closed')
            ->orderBy('business_date', 'desc')
            ->first();

        $closing = [];
        if($businessDay){
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

           $closing = [
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
            ];
        }

        return view('backend.business-day.index', compact('businessDay','previousDay','closing'));
    }

    /**
     * নতুন Day Open করা
     */
    public function openDay(Request $request)
    {
        $openDay = BusinessDay::where('status', 'open')->first();
        if ($openDay) {
            return response()->json(['error' => 'Another business day is already open!'], 400);
        }

        $today = now()->toDateString();
        $existingDay = BusinessDay::where('business_date', $today)->first();

        if ($existingDay) {
            if ($existingDay->status === 'closed') {
                $existingDay->update([
                    'status'       => 'open',
                    'closing_time' => null,
                ]);

                return response()->json(['message' => 'Business day reopened!', 'day' => $existingDay]);
            }


            return response()->json(['error' => 'Business day already exists!'], 400);
        }

        $previousDay = BusinessDay::where('status', 'closed')
            ->orderBy('business_date', 'desc')
            ->first();

        $businessDay = BusinessDay::create([
            'business_date'   => $today,
            'opening_time'    => now(),
            'status'          => 'open',

            'opening_balance'        => $previousDay?->closing_balance ?? 0,
            'opening_cash'        => $previousDay?->closing_cash ?? 0,
            'opening_visa_card'   => $previousDay?->closing_visa_card ?? 0,
            'opening_master_card' => $previousDay?->closing_master_card ?? 0,
            'opening_bkash'       => $previousDay?->closing_bkash ?? 0,
            'opening_nagad'       => $previousDay?->closing_nagad ?? 0,
            'opening_rocket'      => $previousDay?->closing_rocket ?? 0,
            'opening_upay'        => $previousDay?->closing_upay ?? 0,
            'opening_surecash'    => $previousDay?->closing_surecash ?? 0,
            'opening_online'      => $previousDay?->closing_online ?? 0,
        ]);

        return response()->json(['message' => 'Business day opened!', 'businessDay' => $businessDay]);
    }



    /**
     * Day Close করা
     */
    public function closeDay()
    {
        $businessDay = BusinessDay::where('status', 'open')->latest()->first();
        if (!$businessDay) {
            return response()->json(['error' => 'No open business day found!'], 400);
        }
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
        

        return response()->json(['message' => 'Business day closed!', 'businessDay' => $businessDay]);
    }


    public function report(Request $request){
        $today = now()->toDateString();
        $businessDay = BusinessDay::where('business_date', $today)->first();

        if(!$businessDay) abort(404);

        $payments = Payment::whereDate('created_at', $businessDay->business_date)->get();


        $payment = [
            'balance'     => $payments->sum('paid_amount'),
            'cash'        => $payments->sum('cash'),
            'visa_card'   => $payments->sum('visa_card'),
            'master_card' => $payments->sum('master_card'),
            'bkash'       => $payments->sum('bKash'),
            'nagad'       => $payments->sum('Nagad'),
            'rocket'      => $payments->sum('Rocket'),
            'upay'        => $payments->sum('Upay'),
            'surecash'    => $payments->sum('SureCash'),
            'online'      => $payments->sum('online'),
        ];


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


        $closing = [
            'closing_cash'        => $businessDay->opening_cash + $payment['cash'] - $expense['cash'],
            'closing_visa_card'   => $businessDay->opening_visa_card + $payment['visa_card'] - $expense['visa_card'],
            'closing_master_card' => $businessDay->opening_master_card + $payment['master_card'] - $expense['master_card'],
            'closing_bkash'       => $businessDay->opening_bkash + $payment['bkash'] - $expense['bkash'],
            'closing_nagad'       => $businessDay->opening_nagad + $payment['nagad'] - $expense['nagad'],
            'closing_rocket'      => $businessDay->opening_rocket + $payment['rocket'] - $expense['Rocket'],
            'closing_upay'        => $businessDay->opening_upay + $payment['upay'] - $expense['Upay'],
            'closing_surecash'    => $businessDay->opening_surecash + $payment['surecash'] - $expense['SureCash'],
            'closing_online'      => $businessDay->opening_online + $payment['online'] - $expense['online'],
        ];
        $closing['closing_balance'] = array_sum($closing);


        return view('backend.business-day.report', compact('businessDay','payment','expense','closing'));


    }
}
