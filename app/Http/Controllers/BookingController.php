<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    //add booking
    public function addBooking(Request $request)
    {
        $booking = $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:100',
            'pickup_date' => 'required|date',
            'pickup_time' => 'required',
            'return_date' => 'required|date|after_or_equal:pickup_date',
            'return_time' => 'required',
            'pickup_location' => 'required|string|max:255',
            'dropoff_location' => 'required|string|max:255',
            'car_type' => 'required|string|max:100',
            'number_of_passengers' => 'required|integer|min:1',
            'price' => 'required',
        ]);

        $insert = Booking::create($booking);

        if ($insert) {
            return response()->json([
                'status' => 200,
                'message' => 'Add Booking Success',
                'data' => $booking,
            ]);
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Fail to Add Booking',
            ]);
        }
    }

    // get booking
    public function getBooking()
    {
        $booking = Booking::all();
        if ($booking) {
            return response()->json([
                'status' => 200,
                'message' => 'View Booking Success',
                'data' => $booking,
            ]);
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Fail to View Booking',
            ]);
        }
    }

    // getBookingDashbord
    public function getBookingDashbord()
    {
        $booking = Booking::all();
        if ($booking) {
            return response()->json([
                'status' => 200,
                'message' => 'view Booking Success',
                'data' => $booking,
            ]);
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Fail to view Booking',
            ]);
        }
    }

    // deleteBooking
    public function deleteBooking($id)
    {
        $delete = Booking::query()->where('id', $id)->delete();
        if ($delete) {
            return response()->json([
                'status' => 200,
                'message' => 'delete Booking Success',
            ]);
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Fail to delete Booking',
            ]);
        }
    }
}
