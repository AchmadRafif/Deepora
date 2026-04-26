<?php
namespace App\Http\Controllers;

use App\Models\RoomChat;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index(Request $request, $roomId)
    {
        $chats = RoomChat::where('room_id', $roomId)
            ->with('user:id,name,avatar_color,avatar_style')
            ->latest()
            ->take(50)
            ->get()
            ->reverse()
            ->values();

        return response()->json($chats);
    }

    public function store(Request $request, $roomId)
    {
        $request->validate([
            'message' => 'required|string|max:200',
        ]);

        $chat = RoomChat::create([
            'room_id' => $roomId,
            'user_id' => $request->user()->id,
            'message' => $request->message,
        ]);

        $chat->load('user:id,name,avatar_color,avatar_style');

        return response()->json($chat);
    }
}