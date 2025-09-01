<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusinessDay;

class BusinessDayController extends Controller
{
    /**
     * সব Business Day দেখানো
     */
    public function index()
    {
        $businessDay = BusinessDay::where('status', 'open')->latest()->first();
        $closedDay = BusinessDay::where('status', 'close')->latest()->first();

        return view('backend.business-day.index', compact('businessDay','closedDay'));
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
                    'opening_time' => now(),
                    'closing_time' => null,
                ]);

                return response()->json(['message' => 'Business day reopened!', 'day' => $existingDay]);
            }
            return response()->json(['error' => 'Business day already exists!'], 400);
        }

        $previousDay = BusinessDay::where('status', 'closed')
            ->orderBy('business_date', 'desc')
            ->first();

        $day = BusinessDay::create([
            'business_date'   => $today,
            'opening_time'    => now(),
            'status'          => 'open',

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

        return response()->json(['message' => 'Business day opened!', 'day' => $day]);
    }



    /**
     * Day Close করা
     */
    public function closeDay()
    {
        $day = BusinessDay::where('status', 'open')->latest()->first();
        if (!$day) {
            return response()->json(['error' => 'No open business day found!'], 400);
        }

        $day->update([
            'closing_time' => now(),
            'status'       => 'closed'
        ]);

        return response()->json(['message' => 'Business day closed!', 'day' => $day]);
    }
}
