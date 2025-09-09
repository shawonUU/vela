<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusinessDay;
use App\Models\Payment;
use App\Models\Expense;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

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


    public function reprocessBusinessDays()
    {

        DB::beginTransaction();

        try {
            $firstDay = BusinessDay::orderBy('business_date', 'asc')->first();
            $openingBalanceCutoffDate = $firstDay ? $firstDay->business_date : today()->toDateString();

            $prevPayments = Payment::whereDate('created_at', '<', $openingBalanceCutoffDate)->get();
            $prevExpenses = Expense::whereDate('date', '<', $openingBalanceCutoffDate)->get();

            $openingData = [
                'opening_balance' => array_sum([
                    $prevPayments->sum('cash'),
                    $prevPayments->sum('visa_card'),
                    $prevPayments->sum('master_card'),
                    $prevPayments->sum('bKash'),
                    $prevPayments->sum('Nagad'),
                    $prevPayments->sum('Rocket'),
                    $prevPayments->sum('Upay'),
                    $prevPayments->sum('SureCash'),
                    $prevPayments->sum('online'),
                ]) - $prevExpenses->sum('amount'),
                'opening_cash'        => $prevPayments->sum('cash')        - $prevExpenses->where('payment_method','cash')->sum('amount'),
                'opening_visa_card'   => $prevPayments->sum('visa_card')   - $prevExpenses->where('payment_method','visa_card')->sum('amount'),
                'opening_master_card' => $prevPayments->sum('master_card') - $prevExpenses->where('payment_method','master_card')->sum('amount'),
                'opening_bkash'       => $prevPayments->sum('bKash')       - $prevExpenses->where('payment_method','bkash')->sum('amount'),
                'opening_nagad'       => $prevPayments->sum('Nagad')       - $prevExpenses->where('payment_method','nagad')->sum('amount'),
                'opening_rocket'      => $prevPayments->sum('Rocket')      - $prevExpenses->where('payment_method','rocket')->sum('amount'),
                'opening_upay'        => $prevPayments->sum('Upay')        - $prevExpenses->where('payment_method','upay')->sum('amount'),
                'opening_surecash'    => $prevPayments->sum('SureCash')    - $prevExpenses->where('payment_method','surecash')->sum('amount'),
                'opening_online'      => $prevPayments->sum('online')      - $prevExpenses->where('payment_method','online')->sum('amount'),
            ];

            if (! $firstDay) {
                $firstDay = BusinessDay::create(array_merge($openingData, [
                    'business_date'  => $openingBalanceCutoffDate,
                    'opening_time'   => now(),
                    'closing_balance'=> $openingData['opening_balance'],
                    'status'         => 'open',
                ]));
            } else {
                $firstDay->update($openingData);
            }

            $businessDays = BusinessDay::orderBy('business_date', 'asc')->get();
            $previousClosing = null;

            foreach ($businessDays as $day) {
                if ($previousClosing) {
                    $day->update([
                        'opening_balance'     => $previousClosing['balance'],
                        'opening_cash'        => $previousClosing['cash'],
                        'opening_visa_card'   => $previousClosing['visa_card'],
                        'opening_master_card' => $previousClosing['master_card'],
                        'opening_bkash'       => $previousClosing['bkash'],
                        'opening_nagad'       => $previousClosing['nagad'],
                        'opening_rocket'      => $previousClosing['rocket'],
                        'opening_upay'        => $previousClosing['upay'],
                        'opening_surecash'    => $previousClosing['surecash'],
                        'opening_online'      => $previousClosing['online'],
                    ]);
                }

                $payments = Payment::whereDate('created_at', $day->business_date)->get();
                $expenses = Expense::whereDate('date', $day->business_date)->get();

                $closing = [
                    'balance'     => $day->opening_balance + 
                    array_sum([
                        $payments->sum('cash'),
                        $payments->sum('visa_card'),
                        $payments->sum('master_card'),
                        $payments->sum('bKash'),
                        $payments->sum('Nagad'),
                        $payments->sum('Rocket'),
                        $payments->sum('Upay'),
                        $payments->sum('SureCash'),
                        $payments->sum('online'),
                    ])
                    - $expenses->sum('amount'),
                    'cash'        => $day->opening_cash + $payments->sum('cash') - $expenses->where('payment_method','cash')->sum('amount'),
                    'visa_card'   => $day->opening_visa_card + $payments->sum('visa_card') - $expenses->where('payment_method','visa_card')->sum('amount'),
                    'master_card' => $day->opening_master_card + $payments->sum('master_card') - $expenses->where('payment_method','master_card')->sum('amount'),
                    'bkash'       => $day->opening_bkash + $payments->sum('bKash') - $expenses->where('payment_method','bkash')->sum('amount'),
                    'nagad'       => $day->opening_nagad + $payments->sum('Nagad') - $expenses->where('payment_method','nagad')->sum('amount'),
                    'rocket'      => $day->opening_rocket + $payments->sum('Rocket') - $expenses->where('payment_method','rocket')->sum('amount'),
                    'upay'        => $day->opening_upay + $payments->sum('Upay') - $expenses->where('payment_method','upay')->sum('amount'),
                    'surecash'    => $day->opening_surecash + $payments->sum('SureCash') - $expenses->where('payment_method','surecash')->sum('amount'),
                    'online'      => $day->opening_online + $payments->sum('online') - $expenses->where('payment_method','online')->sum('amount'),
                ];

                $day->update([
                    'closing_balance'     => $closing['balance'],
                    'closing_cash'        => $closing['cash'],
                    'closing_visa_card'   => $closing['visa_card'],
                    'closing_master_card' => $closing['master_card'],
                    'closing_bkash'       => $closing['bkash'],
                    'closing_nagad'       => $closing['nagad'],
                    'closing_rocket'      => $closing['rocket'],
                    'closing_upay'        => $closing['upay'],
                    'closing_surecash'    => $closing['surecash'],
                    'closing_online'      => $closing['online'],
                ]);

                $previousClosing = $closing;
            }

            $notification = array(
                'message' => 'All business days recalculated successfully!',
                'alert-type' => 'success'
            );

            DB::commit();
            return redirect()->back()->with($notification);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with([
                'message'    => 'Error during recalculation: ' . $e->getMessage(),
                'alert-type' => 'error',
            ]);
        }

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
