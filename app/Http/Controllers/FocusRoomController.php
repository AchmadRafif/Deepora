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
            'music_type' => 'required|in:lofi,jazz,nature,classical,custom',
            'youtube_url' => 'nullable|string',
            'preset_url' => 'nullable|string',
        ]);

        $user = $request->user();

        // Pakai custom URL kalau ada, kalau tidak pakai preset
        $youtubeUrl = $request->music_type === 'custom'
            ? $request->youtube_url
            : $request->preset_url;

        FocusRoom::create([
            'name' => $request->name,
            'music_type' => $request->music_type,
            'youtube_url' => $youtubeUrl,
            'created_by' => $user->id,
        ]);

        return redirect()->route('rooms.index')->with('success', 'Room berhasil dibuat!');
    }

    public function show($id)
    {
        $room = FocusRoom::findOrFail($id);
        return view('rooms.show', compact('room'));
    }

    public function destroy(Request $request, FocusRoom $room)
    {
        if ($request->user()->id !== $room->created_by) {
            return redirect()->route('rooms.index')->with('error', 'Kamu tidak bisa menghapus room ini!');
        }

        $room->sessions()->delete();
        $room->delete();

        return redirect()->route('rooms.index')->with('success', 'Room berhasil dihapus!');
    }
}
