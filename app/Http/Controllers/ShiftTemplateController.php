<?php

namespace App\Http\Controllers;

use App\Models\ShiftTemplate;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ShiftTemplateController extends Controller
{
    public function index()
    {
        $shifts = ShiftTemplate::latest()->get();
        return view('shift_templates.index', compact('shifts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'start_time' => 'required',
            'end_time'   => 'required',
        ]);

        $start = Carbon::parse($request->start_time);
        $end   = Carbon::parse($request->end_time);
        if ($end->lt($start)) {
            $end->addDay();
        }
        $durationHours = $start->diffInHours($end);

        ShiftTemplate::create([
            'name'           => $request->name,
            'start_time'     => $request->start_time,
            'end_time'       => $request->end_time,
            'duration_hours' => $durationHours,
            'is_active'      => true,
        ]);

        return redirect()->back()->with('success', 'Master Shift berhasil dibuat!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'start_time' => 'required',
            'end_time'   => 'required',
        ]);

        $shift = ShiftTemplate::findOrFail($id);

        $start = Carbon::parse($request->start_time);
        $end   = Carbon::parse($request->end_time);
        if ($end->lt($start)) {
            $end->addDay();
        }
        $durationHours = $start->diffInHours($end);

        $shift->update([
            'name'           => $request->name,
            'start_time'     => $request->start_time,
            'end_time'       => $request->end_time,
            'duration_hours' => $durationHours,
        ]);

        return redirect()->back()->with('success', 'Jam shift berhasil diperbarui!');
    }

    public function destroy($id)
    {
        ShiftTemplate::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Master Shift dihapus!');
    }
}
