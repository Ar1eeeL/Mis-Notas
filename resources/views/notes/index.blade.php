@extends('layouts.notes')

@section('title', 'Mis Notas')

@section('content')
<div class="min-h-screen py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <header class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-white">
                        ¡Hola, {{ Auth::user()->name }}! 👋
                    </h1>
                    <p class="mt-1 text-slate-400">
                        Gestiona tus notas y recordatorios
                    </p>
                </div>
                <button onclick="openModal()" id="btn-new-note"
                    class="btn-primary px-6 py-3 rounded-xl font-semibold text-white flex items-center gap-2 self-start sm:self-auto">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4v16m8-8H4" />
                    </svg>
                    Nueva Nota
                </button>
            </div>
        </header>

        <!-- Filters -->
        <section class="glass rounded-3xl p-6 mb-8" id="filters-section">
            <div class="flex items-center gap-3 mb-4">
                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                <h2 class="text-lg font-semibold text-white">Filtros</h2>
            </div>

            <form id="filter-form" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Search -->
                <div class="sm:col-span-2 lg:col-span-4 relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" name="search" id="filter-search" placeholder="Buscar notas..."
                        class="w-full input-glass rounded-xl pl-12 pr-4 py-3 text-white placeholder-slate-500">
                </div>

                <!-- Category -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                    </div>
                    <select name="category" id="filter-category"
                        class="w-full input-glass rounded-xl pl-12 pr-4 py-3 text-white cursor-pointer appearance-none">
                        <option value="">Categorías</option>
                        @foreach ($categories as $category)
                        <option value="{{ $category }}">{{ ucfirst($category) }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>

                <!-- Priority -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <select name="priority" id="filter-priority"
                        class="w-full input-glass rounded-xl pl-12 pr-4 py-3 text-white cursor-pointer appearance-none">
                        <option value="">Prioridades</option>
                        <option value="low">Baja</option>
                        <option value="normal">Normal</option>
                        <option value="high">Alta</option>
                        <option value="urgent">Urgente</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>

                <!-- Status -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                    <select name="status" id="filter-status"
                        class="w-full input-glass rounded-xl pl-12 pr-4 py-3 text-white cursor-pointer appearance-none">
                        <option value="">Estados</option>
                        <option value="pending">Pendientes</option>
                        <option value="completed">Completadas</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>

                <!-- Clear filters -->
                <div class="flex items-center">
                    <button type="button" onclick="clearFilters()" id="btn-clear-filters"
                        class="w-full px-4 py-3 rounded-xl border border-slate-600 text-slate-400 hover:text-white hover:border-indigo-500 hover:bg-indigo-500/10 transition-all flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Limpiar
                    </button>
                </div>
            </form>
        </section>

        <!-- Notes Grid -->
        <section id="notes-container">
            @include('notes.partials.notes-grid', ['notes' => $notes])
        </section>

        <!-- Pagination -->
        <div id="pagination-container" class="mt-8">
            {{ $notes->links('notes.partials.pagination') }}
        </div>
    </div>
</div>

<!-- Modal -->
@include('notes.partials.modal')
@endsection

@push('scripts')
<script>
    let currentNoteId = null;
    let isEditing = false;
    let filterTimeout = null;

    // Modal functions
    function openModal(noteId = null) {
        const modal = document.getElementById('note-modal');
        const modalTitle = document.getElementById('modal-title');
        const form = document.getElementById('note-form');

        currentNoteId = noteId;
        isEditing = !!noteId;

        if (isEditing) {
            modalTitle.textContent = 'Editar Nota';
            loadNoteData(noteId);
        } else {
            modalTitle.textContent = 'Nueva Nota';
            form.reset();
            document.getElementById('note-priority').value = 'normal';
            document.getElementById('char-count').textContent = '0/256';
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        const modal = document.getElementById('note-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
        currentNoteId = null;
        isEditing = false;
    }

    async function loadNoteData(noteId) {
        try {
            const response = await fetch(`/notes/${noteId}`);
            const note = await response.json();

            document.getElementById('note-title').value = note.title;
            const content = note.content || '';
            document.getElementById('note-content').value = content;
            document.getElementById('char-count').textContent = content.length + '/256';
            document.getElementById('note-category').value = note.category || '';
            document.getElementById('note-priority').value = note.priority;


            if (note.reminder_at) {
                const reminderDate = new Date(note.reminder_at);
                document.getElementById('note-reminder').value = reminderDate.toISOString().slice(0, 16);
            } else {
                document.getElementById('note-reminder').value = '';
            }
        } catch (error) {
            showToast('Error al cargar la nota', 'error');
        }
    }

    // Form submission
    document.getElementById('note-form').addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = {
            title: document.getElementById('note-title').value,
            content: document.getElementById('note-content').value,
            category: document.getElementById('note-category').value,
            priority: document.getElementById('note-priority').value,
            reminder_at: document.getElementById('note-reminder').value || null,

        };

        const url = isEditing ? `/notes/${currentNoteId}` : '/notes';
        const method = isEditing ? 'PUT' : 'POST';

        try {
            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify(formData),
            });

            // Check if response is a redirect to login (session expired)
            if (response.redirected || response.status === 401) {
                showToast('Sesión expirada. Recargando...', 'warning');
                setTimeout(() => location.reload(), 1000);
                return;
            }

            // Check content type
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                console.error('Server returned non-JSON response:', response.status);
                showToast('Error del servidor. Recargando...', 'error');
                setTimeout(() => location.reload(), 1500);
                return;
            }

            const data = await response.json();

            if (data.success) {
                showToast(data.message, 'success');
                closeModal();
                refreshNotes();
            } else {
                let errorMsg = data.message || 'Error al guardar la nota';
                if (data.errors) {
                    const firstError = Object.values(data.errors)[0];
                    errorMsg = Array.isArray(firstError) ? firstError[0] : firstError;
                }
                showToast(errorMsg, 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showToast('Error de conexión. Intenta recargar la página.', 'error');
        }
    });

    // Delete note
    async function deleteNote(noteId) {
        const result = await Swal.fire({
            title: '¿Estás seguro?',
            text: "No podrás revertir esta acción",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            background: '#1e293b',
            color: '#fff',
            customClass: {
                confirmButton: 'px-4 py-2 bg-gradient-to-br from-indigo-500 to-purple-600 hover:opacity-90 text-white rounded-xl transition-all font-medium',
                cancelButton: 'px-4 py-2 border border-slate-600 hover:bg-slate-700 text-slate-300 rounded-xl transition-colors font-medium',
                popup: 'border border-slate-700 rounded-2xl',
                actions: 'gap-3'
            },
            buttonsStyling: false
        });

        if (!result.isConfirmed) return;

        try {
            const response = await fetch(`/notes/${noteId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            const data = await response.json();

            if (data.success) {
                showToast(data.message, 'success');
                refreshNotes();
            } else {
                showToast('Error al eliminar la nota', 'error');
            }
        } catch (error) {
            showToast('Error de conexión', 'error');
        }
    }

    // Toggle complete
    async function toggleComplete(noteId) {
        try {
            const response = await fetch(`/notes/${noteId}/toggle`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
            });

            const data = await response.json();

            if (data.success) {
                showToast(data.message, 'success');
                refreshNotes();
            } else {
                showToast('Error al actualizar la nota', 'error');
            }
        } catch (error) {
            showToast('Error de conexión', 'error');
        }
    }

    // Refresh notes with current filters
    async function refreshNotes() {
        const form = document.getElementById('filter-form');
        const formData = new FormData(form);
        const params = new URLSearchParams(formData);

        try {
            const response = await fetch(`/notes?${params}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
            });

            const data = await response.json();
            document.getElementById('notes-container').innerHTML = data.html;
            document.getElementById('pagination-container').innerHTML = data.pagination;
        } catch (error) {
            console.error('Error refreshing notes:', error);
        }
    }

    // Filter handlers
    function clearFilters() {
        document.getElementById('filter-form').reset();
        refreshNotes();
    }

    // Debounced filter
    document.querySelectorAll('#filter-form input, #filter-form select').forEach(element => {
        element.addEventListener('change', () => {
            clearTimeout(filterTimeout);
            filterTimeout = setTimeout(refreshNotes, 300);
        });

        if (element.type === 'text') {
            element.addEventListener('input', () => {
                clearTimeout(filterTimeout);
                filterTimeout = setTimeout(refreshNotes, 500);
            });
        }
    });

    // Close modal on backdrop click
    document.getElementById('note-modal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });

    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });
</script>
@endpush