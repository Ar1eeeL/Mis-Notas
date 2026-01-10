<!-- Note Modal -->
<div id="note-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 modal-backdrop">
    <div class="glass w-full max-w-xl rounded-3xl p-8 transform transition-all" onclick="event.stopPropagation()">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <h2 id="modal-title" class="text-2xl font-bold text-white">Nueva Nota</h2>
            <button onclick="closeModal()" id="modal-close-btn"
                class="p-2 rounded-lg hover:bg-white/10 transition-colors">
                <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Form -->
        <form id="note-form" class="space-y-5">
            <!-- Title -->
            <div>
                <label for="note-title" class="block text-sm font-medium text-slate-300 mb-2">
                    Título <span class="text-rose-400">*</span>
                </label>
                <input type="text" id="note-title" name="title" required maxlength="32"
                    oninput="this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1)"
                    class="w-full input-glass rounded-xl px-4 py-3 text-white placeholder-slate-500"
                    placeholder="Título de la nota">
            </div>

            <!-- Content -->
            <div>
                <label for="note-content" class="block text-sm font-medium text-slate-300 mb-2">
                    Contenido <span class="text-rose-400">*</span>
                </label>
                <textarea id="note-content" name="content" required rows="4" maxlength="256"
                    oninput="this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1); document.getElementById('char-count').textContent = this.value.length + '/256'"
                    class="w-full input-glass rounded-xl px-4 py-3 text-white placeholder-slate-500 resize-none"
                    placeholder="Escribe el contenido de tu nota..."></textarea>
                <p id="char-count" class="text-xs text-slate-500 text-right mt-1">0/256</p>
            </div>

            <!-- Category & Priority -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="note-category" class="block text-sm font-medium text-slate-300 mb-2">
                        Categoría
                    </label>
                    <input type="text" id="note-category" name="category" maxlength="256"
                        oninput="this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1)"
                        class="w-full input-glass rounded-xl px-4 py-3 text-white placeholder-slate-500"
                        placeholder="ej: trabajo, personal">
                </div>
                <div>
                    <label for="note-priority" class="block text-sm font-medium text-slate-300 mb-2">
                        Prioridad <span class="text-rose-400">*</span>
                    </label>
                    <select id="note-priority" name="priority" required
                        class="w-full input-glass rounded-xl px-4 py-3 text-white">
                        <option value="low">🟢 Baja</option>
                        <option value="normal" selected>🔵 Normal</option>
                        <option value="high">🟠 Alta</option>
                        <option value="urgent">🔴 Urgente</option>
                    </select>
                </div>
            </div>

            <!-- Reminder -->
            <div>
                <label for="note-reminder" class="block text-sm font-medium text-slate-300 mb-2">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Recordatorio
                    </span>
                </label>
                <input type="datetime-local" id="note-reminder" name="reminder_at"
                    class="w-full input-glass rounded-xl px-4 py-3 text-white">
                <p class="mt-1 text-xs text-slate-500">Recibirás una notificación en Telegram en esta fecha</p>
            </div>

            <!-- Telegram Status -->
            @if(Auth::user()->telegram_chat_id)
            <div class="flex items-center gap-2 p-3 bg-emerald-500/10 border border-emerald-500/30 rounded-xl">
                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm text-emerald-300">Telegram vinculado - Recibirás recordatorios</span>
            </div>
            @else
            <div class="flex items-center gap-2 p-3 bg-amber-500/10 border border-amber-500/30 rounded-xl">
                <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span class="text-sm text-amber-300">Vincula Telegram en tu <a href="{{ route('profile.edit') }}" class="underline hover:text-white">perfil</a> para recibir recordatorios</span>
            </div>
            @endif

            <!-- Actions -->
            <div class="flex gap-3 pt-4">
                <button type="button" onclick="closeModal()" id="modal-cancel-btn"
                    class="flex-1 px-6 py-3 rounded-xl border border-slate-600 text-slate-300 font-medium hover:bg-white/5 transition-all">
                    Cancelar
                </button>
                <button type="submit" id="modal-submit-btn"
                    class="flex-1 btn-primary px-6 py-3 rounded-xl text-white font-semibold">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>