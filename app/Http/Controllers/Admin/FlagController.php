<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Flag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FlagController extends Controller
{
    public function index(): View
    {
        $flags = Flag::with(['user', 'flaggable', 'resolver'])
            ->orderBy('status')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.flags.index', compact('flags'));
    }

    public function resolve(Request $request, Flag $flag): RedirectResponse
    {
        $data = $request->validate([
            'action' => 'required|string|in:dismiss,delete',
        ]);

        if ($flag->status !== 'pending') {
            return back()->with('error', 'Flag is already resolved.');
        }

        if ($data['action'] === 'delete' && $flag->flaggable) {
            $flag->flaggable->delete();
        }

        $flag->update([
            'status' => 'resolved',
            'resolved_by' => auth()->id(),
            'resolved_at' => now(),
        ]);

        $msg = $data['action'] === 'delete'
            ? 'Content deleted and flag resolved.'
            : 'Flag dismissed.';

        return redirect()->route('admin.flags.index')->with('success', $msg);
    }

    public function apiIndex(): JsonResponse
    {
        $flags = Flag::with(['user', 'flaggable', 'resolver'])
            ->orderBy('status')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json($flags);
    }

    public function apiResolve(Request $request, Flag $flag): JsonResponse
    {
        $data = $request->validate([
            'action' => 'required|string|in:dismiss,delete',
        ]);

        if ($flag->status !== 'pending') {
            return response()->json(['message' => 'Flag is already resolved.'], 409);
        }

        if ($data['action'] === 'delete' && $flag->flaggable) {
            $flag->flaggable->delete();
        }

        $flag->update([
            'status' => 'resolved',
            'resolved_by' => auth()->id(),
            'resolved_at' => now(),
        ]);

        return response()->json([
            'message' => $data['action'] === 'delete'
                ? 'Content deleted and flag resolved.'
                : 'Flag dismissed.',
            'flag' => $flag->load(['user', 'resolver']),
        ]);
    }
}
