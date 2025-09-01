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
        $days = BusinessDay::orderBy('id', 'desc')->get();
        return response()->json($days);
    }

    /**
     * নতুন Day Open করা
     */
    public function openDay(Request $request)
    {
        // আগে কোনো open day আছে কিনা চেক করি
        $openDay = BusinessDay::where('status', 'open')->first();
        if ($openDay) {
            return response()->json(['error' => 'Another business day is already open!'], 400);
        }

        $day = BusinessDay::create([
            'business_date' => now()->toDateString(),
            'opening_time'  => now(),
            'status'        => 'open'
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
