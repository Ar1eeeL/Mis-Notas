@if ($notes->isEmpty())
<div class="glass-card rounded-3xl p-12 text-center">
    <div class="w-24 h-24 mx-auto mb-6 rounded-full bg-gradient-to-br from-indigo-500/20 to-purple-500/20 flex items-center justify-center">
        <svg class="w-12 h-12 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
    </div>
    <h3 class="text-2xl font-bold text-white mb-2">No hay notas</h3>
    <p class="text-slate-400 mb-6">Comienza creando tu primera nota con recordatorio</p>
    <button onclick="openModal()" class="btn-primary px-6 py-3 rounded-xl font-semibold text-white inline-flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Crear Nota
    </button>
</div>
@else
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
    @foreach ($notes as $note)
    <article
        class="glass-card rounded-2xl p-6 priority-{{ $note->priority }} {{ $note->is_completed ? 'note-completed' : '' }}"
        data-note-id="{{ $note->id }}">
        <!-- Header -->
        <div class="flex items-start justify-between mb-4">
            <div class="flex items-center gap-3">
                <button onclick="toggleComplete({{ $note->id }})" id="toggle-{{ $note->id }}"
                    class="w-6 h-6 rounded-full border-2 flex items-center justify-center transition-all
                            {{ $note->is_completed ? 'bg-emerald-500 border-emerald-500' : 'border-slate-500 hover:border-emerald-400' }}">
                    @if ($note->is_completed)
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    @endif
                </button>
                <span class="px-3 py-1 rounded-full text-xs font-medium
                            {{ $note->priority === 'urgent' ? 'bg-rose-500/20 text-rose-300' : '' }}
                            {{ $note->priority === 'high' ? 'bg-amber-500/20 text-amber-300' : '' }}
                            {{ $note->priority === 'normal' ? 'bg-sky-500/20 text-sky-300' : '' }}
                            {{ $note->priority === 'low' ? 'bg-emerald-500/20 text-emerald-300' : '' }}">
                    @switch($note->priority)
                    @case('urgent')
                    🔴 Urgente
                    @break
                    @case('high')
                    🟠 Alta
                    @break
                    @case('normal')
                    🔵 Normal
                    @break
                    @default
                    🟢 Baja
                    @endswitch
                </span>
            </div>

            <!-- Actions dropdown -->
            <div class="relative group">
                <button class="p-2 rounded-lg hover:bg-white/10 transition-colors" id="menu-btn-{{ $note->id }}">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                    </svg>
                </button>
                <div class="absolute right-0 mt-2 w-44 bg-slate-800 border border-slate-700 rounded-xl shadow-2xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50 overflow-hidden">
                    <button onclick="openModal({{ $note->id }})" id="edit-{{ $note->id }}"
                        class="w-full px-4 py-3 text-left text-sm text-white hover:bg-indigo-600 flex items-center gap-3 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Editar
                    </button>
                    <button onclick="deleteNote({{ $note->id }})" id="delete-{{ $note->id }}"
                        class="w-full px-4 py-3 text-left text-sm text-rose-400 hover:bg-rose-600 hover:text-white flex items-center gap-3 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Eliminar
                    </button>
                </div>
            </div>
        </div>

        <!-- Content -->
        <h3 class="note-title text-xl font-bold text-white mb-2 line-clamp-2">{{ $note->title }}</h3>
        <p class="text-slate-400 text-sm mb-4 line-clamp-3">{{ $note->content }}</p>

        <!-- Category -->
        @if ($note->category)
        <div class="mb-4">
            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-lg bg-indigo-500/10 text-indigo-300 text-xs">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                </svg>
                {{ ucfirst($note->category) }}
            </span>
        </div>
        @endif

        <!-- Footer -->
        <div class="pt-4 border-t border-white/5 flex items-center justify-between">
            @if ($note->reminder_at)
            <div class="flex items-center gap-2 text-xs {{ $note->reminder_at->isPast() ? 'text-rose-400' : 'text-slate-400' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ $note->reminder_at->format('d/m/Y H:i') }}
                @if ($note->reminder_sent)
                <span class="text-emerald-400" title="Recordatorio enviado">✓</span>
                @endif
            </div>
            @else
            <span class="text-xs text-slate-500">Sin recordatorio</span>
            @endif

            @if ($note->telegram_chat_id)
            <div class="flex items-center gap-1 text-xs text-sky-400" title="Telegram configurado">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69a.2.2 0 00-.05-.18c-.06-.05-.14-.03-.21-.02-.09.02-1.49.95-4.22 2.79-.4.27-.76.41-1.08.4-.36-.01-1.04-.2-1.55-.37-.63-.2-1.12-.31-1.08-.66.02-.18.27-.36.74-.55 2.92-1.27 4.86-2.11 5.83-2.51 2.78-1.16 3.35-1.36 3.73-1.36.08 0 .27.02.39.12.1.08.13.19.14.27-.01.06.01.24 0 .37z" />
                </svg>
            </div>
            @endif
        </div>
    </article>
    @endforeach
</div>
@endif