<?php
namespace App\Http\Controllers;

use App\Models\FocusRoom;
use Illuminate\Http\Request;

class FocusRoomController extends Controller
{
    public function index()
    {
        $rooms = FocusRoom::where('is_active', true)->with('creator')->get();
        return view('rooms.index', compact('rooms'));
    }

    public function create()
    {
        return view('rooms.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'music_type' => 'required|in:lofi,jazz,nature',
            'youtube_url' => 'nullable|url',
        ]);

        FocusRoom::create([
            'name' => $request->name,
            'music_type' => $request->music_type,
            'youtube_url' => $request->youtube_url,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('rooms.index')->with('success', 'Room berhasil dibuat!');
    }

    public function show(FocusRoom $room)
    {
        return view('rooms.show', compact('room'));
    }
}