<?php

namespace App\Http\Controllers;

use App\Models\UserMessage;
use Illuminate\Http\Request;

class UserMessageController extends Controller
{
    // Add user message
    public function addMessage(Request $request)
    {
        $validatedData = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|max:255',
            'phone_number' => 'required|string|max:20',
            'subject'      => 'required|string|max:255',
            'message'      => 'required|string',
        ]);

        try {
            $userMessage = UserMessage::create($validatedData);

            return response()->json([
                'status'  => true,
                'message' => 'Message sent successfully',
                'data'    => $userMessage,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Failed to send message',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    // View all user messages
    public function viewMessage()
    {
        $messages = UserMessage::latest()->get(); // latest first

        return response()->json([
            'status'  => true,
            'message' => 'Messages fetched successfully',
            'data'    => $messages,
        ]);
    }
}
