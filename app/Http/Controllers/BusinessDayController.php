<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusinessDay;
use App\Models\Payment;
use App\Models\Expense;
use Barryvdh\DomPDF\Facade\Pdf;

class BusinessDayController extends Controller
{
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
            $notification = array(
                'message' => 'Another business day is already open!',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }

        $today = now()->toDateString();
        $existingDay = BusinessDay::where('business_date', $today)->first();

        if ($existingDay) {
            if ($existingDay->status === 'closed') {
                $existingDay->update([
                    'status'       => 'open',
                    'closing_time' => null,
                ]);

                $notification = array(
                    'message' => 'Business day reopened!',
                    'alert-type' => 'success'
                );
                return redirect()->back()->with($notification);
            }

            $notification = array(
                'message' => 'Business day already exists!',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }

        $previousDay = BusinessDay::where('status', 'closed')
            ->orderBy('business_date', 'desc')
            ->first();

        $businessDay = BusinessDay::create([
            'business_date'   => $today,
            'opening_time'    => now(),
            'status'          => 'open',

            'opening_balance'     => $previousDay?->closing_balance ?? 0,
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

        $notification = array(
            'message' => 'Business day opened!',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }



    /**
     * Day Close করা
     */
    public function closeDay()
    {
        $businessDay = BusinessDay::where('status', 'open')->latest()->first();
        if (!$businessDay) {
            $notification = array(
                'message' => 'No open business day found!',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }
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
        
        $notification = array(
            'message' => 'Business day closed!',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function dayList(Request $request)
    {
        $query = BusinessDay::query();

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('business_date', [
                $request->from_date,
                $request->to_date
            ]);
        } else {
            $query->whereMonth('business_date', now()->month)
                ->whereYear('business_date', now()->year);
        }

        $businessDays = $query->orderBy('business_date', 'desc')->get();

        return view('backend.business-day.day-list', compact('businessDays'));
    }


    public function report(Request $request){
        $businessDay = BusinessDay::where('id', $request->id)->first();

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

    public function generatePDF($id){
        $businessDay = BusinessDay::where('id', $id)->first();
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

        $pdf = Pdf::loadView('backend.business-day.report-pdf', compact('businessDay','payment','expense','closing'));
        return $pdf->download('business_day_report_'.$businessDay->business_date.'.pdf');
    }
}
