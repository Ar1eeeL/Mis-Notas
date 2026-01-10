<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class NoteController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $query = Auth::user()->notes();

        $query->when($request->search, function ($q, $search) {
            $q->where(function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        });

        $query->when($request->category, fn($q, $cat) => $q->category($cat));
        $query->when($request->priority, fn($q, $prio) => $q->priority($prio));
        $query->when($request->status, fn($q, $status) => $q->completed($status === 'completed'));
        $query->when($request->date_from, fn($q, $date) => $q->whereDate('reminder_at', '>=', $date));
        $query->when($request->date_to, fn($q, $date) => $q->whereDate('reminder_at', '<=', $date));

        $notes = $query->orderBy('reminder_at', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('notes.partials.notes-grid', compact('notes'))->render(),
                'pagination' => $notes->links()->toHtml(),
            ]);
        }

        $categories = Auth::user()->notes()
            ->distinct()
            ->pluck('category')
            ->filter()
            ->values();

        return view('notes.index', compact('notes', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:1000',
            'category' => 'nullable|string|max:100',
            'priority' => 'required|in:low,normal,high,urgent',
            'reminder_at' => 'nullable|date',
        ]);

        $note = Auth::user()->notes()->create([
            ...$validated,
            'category' => $validated['category'] ?: 'general',
            'telegram_chat_id' => Auth::user()->telegram_chat_id,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => '¡Nota creada exitosamente!',
                'note' => $note,
            ]);
        }

        return redirect()->route('notes.index')->with('success', '¡Nota creada exitosamente!');
    }

    public function show(Note $note)
    {
        if ($note->user_id !== Auth::id()) {
            abort(403);
        }

        return response()->json($note);
    }

    public function update(Request $request, Note $note)
    {
        if ($note->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'nullable|string|max:100',
            'priority' => 'required|in:low,normal,high,urgent',
            'reminder_at' => 'nullable|date',
            'is_completed' => 'boolean',
        ]);

        if (isset($validated['reminder_at']) && $validated['reminder_at'] !== $note->reminder_at?->format('Y-m-d\TH:i')) {
            $validated['reminder_sent'] = false;
        }

        $note->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => '¡Nota actualizada exitosamente!',
                'note' => $note->fresh(),
            ]);
        }

        return redirect()->route('notes.index')->with('success', '¡Nota actualizada exitosamente!');
    }

    public function destroy(Note $note)
    {
        if ($note->user_id !== Auth::id()) {
            abort(403);
        }

        $note->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => '¡Nota eliminada exitosamente!']);
        }

        return redirect()->route('notes.index')->with('success', '¡Nota eliminada exitosamente!');
    }

    public function toggleComplete(Note $note)
    {
        if ($note->user_id !== Auth::id()) {
            abort(403);
        }

        $note->update(['is_completed' => !$note->is_completed]);

        return response()->json([
            'success' => true,
            'is_completed' => $note->is_completed,
            'message' => $note->is_completed ? '¡Nota completada!' : 'Nota marcada como pendiente',
        ]);
    }
}
