@props(['komentar', 'laporanId', 'userRole'])

<div class="space-y-4">
    @forelse($komentar as $comment)
        <div class="bg-white rounded-lg border border-gray-200 p-4 {{ $comment->parent_id ? 'ml-8' : '' }}">
            <!-- Comment Header -->
            <div class="flex items-start gap-3 mb-3">
                <div class="shrink-0">
                    @if ($comment->isFromParent())
                        <div class="bg-purple-100 p-2 rounded-full">
                            <i class="fas fa-user text-purple-600"></i>
                        </div>
                    @else
                        <div class="bg-green-100 p-2 rounded-full">
                            <i class="fas fa-chalkboard-teacher text-green-600"></i>
                        </div>
                    @endif
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="font-bold text-gray-900">
                            {{ $comment->isFromParent() ? $comment->orangTua->nama : $comment->guru->nama }}
                        </span>
                        <span
                            class="px-2 py-1 text-xs font-medium rounded-full 
                            {{ $comment->isFromParent() ? 'bg-purple-100 text-purple-700' : 'bg-green-100 text-green-700' }}">
                            {{ $comment->isFromParent() ? 'Orang Tua' : 'Guru' }}
                        </span>
                        <span class="text-xs text-gray-500">
                            {{ $comment->created_at->diffForHumans() }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-700 mt-2 whitespace-pre-line">{{ $comment->komentar }}</p>

                    <!-- Reply Button -->
                    <button type="button" onclick="toggleReplyForm('{{ $comment->id_komentar }}')"
                        class="text-sm text-blue-600 hover:text-blue-800 mt-2">
                        <i class="fas fa-reply mr-1"></i>Balas
                    </button>
                </div>
            </div>

            <!-- Reply Form (Hidden by default) -->
            <div id="reply-form-{{ $comment->id_komentar }}" class="mt-4 ml-11 hidden">
                <form action="{{ route($userRole . '.laporan-lengkap.komentar.store', $laporanId) }}" method="POST">
                    @csrf
                    <input type="hidden" name="parent_id" value="{{ $comment->id_komentar }}">
                    <div class="flex gap-2">
                        <textarea name="komentar" rows="2" required
                            class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                            placeholder="Tulis balasan..."></textarea>
                        <div class="flex flex-col gap-2">
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm transition-colors">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                            <button type="button" onclick="toggleReplyForm('{{ $comment->id_komentar }}')"
                                class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg text-sm transition-colors">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Nested Replies -->
            @if ($comment->replies && $comment->replies->count() > 0)
                <div class="mt-4 space-y-4">
                    <x-komentar-list :komentar="$comment->replies" :laporanId="$laporanId" :userRole="$userRole" />
                </div>
            @endif
        </div>
    @empty
        <div class="text-center py-8 text-gray-500">
            <i class="fas fa-comments text-4xl mb-2 text-gray-300"></i>
            <p class="text-sm">Belum ada komentar</p>
        </div>
    @endforelse
</div>

<script>
    function toggleReplyForm(commentId) {
        const form = document.getElementById('reply-form-' + commentId);
        form.classList.toggle('hidden');
    }
</script>
