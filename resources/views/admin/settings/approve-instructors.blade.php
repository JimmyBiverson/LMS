@if(session('success'))
<div class="mb-6 px-6 py-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm font-semibold">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="mb-6 px-6 py-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm font-semibold">{{ session('error') }}</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl shadow-sm">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-heading flex items-center gap-2"><i class="ri-time-line text-amber-500"></i> Pending Approval</h3>
            <span class="px-3 py-1 bg-amber-100 text-amber-700 text-xs font-bold rounded-full">{{ $pending->count() }}</span>
        </div>
        <div class="p-4 space-y-3">
            @forelse($pending as $instructor)
            <div class="flex items-center justify-between p-3 rounded-lg bg-amber-50 hover:bg-amber-100 transition-colors">
                <div class="flex items-center gap-3 flex-1">
                    <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center shrink-0">
                        <i class="ri-user-star-line text-amber-600"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="font-semibold text-heading text-sm">{{ $instructor->name }}</p>
                        <p class="text-xs text-heading/60 truncate">{{ $instructor->email }} | {{ $instructor->designation ?? 'Instructor' }}</p>
                        <p class="text-xs text-heading/40">Registered {{ $instructor->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <form method="POST" action="/admin/settings/instructors/{{ $instructor->id }}/approve" class="inline">
                        @csrf
                        <button class="px-3 py-1.5 text-xs font-semibold bg-green-500 text-white rounded-lg hover:bg-green-600 transition-all" title="Approve this instructor">
                            <i class="ri-check-line"></i> Approve
                        </button>
                    </form>
                    <button type="button" onclick="openSuperModal({{ $instructor->id }}, '{{ $instructor->name }}')" class="px-3 py-1.5 text-xs font-semibold {{ $instructor->is_super_instructor ? 'bg-violet-100 text-violet-700' : 'bg-slate-100 text-slate-700' }} rounded-lg hover:opacity-90 transition-all" title="Toggle super instructor status">
                        <i class="ri-shield-star-line"></i> {{ $instructor->is_super_instructor ? 'Super' : 'Standard' }}
                    </button>
                </div>
            </div>
            @empty
            <p class="text-sm text-heading/50 text-center py-8">No pending instructor approvals.</p>
            @endforelse
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-heading flex items-center gap-2"><i class="ri-user-star-line text-green-500"></i> Approved Instructors</h3>
            <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full">{{ $approved->count() }}</span>
        </div>
        <div class="p-4 space-y-3">
            @forelse($approved as $instructor)
            <div class="flex items-center justify-between p-3 rounded-lg bg-green-50 hover:bg-green-100 transition-colors">
                <div class="flex items-center gap-3 flex-1">
                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center shrink-0">
                        <i class="ri-user-star-fill text-green-600"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="font-semibold text-heading text-sm flex items-center gap-2">
                            {{ $instructor->name }}
                            @if($instructor->is_super_instructor)
                                <span class="px-2 py-0.5 bg-violet-100 text-violet-700 text-xs rounded-full font-bold">⭐ Super</span>
                            @endif
                        </p>
                        <p class="text-xs text-heading/60 truncate">{{ $instructor->email }} | {{ $instructor->designation ?? 'Instructor' }}</p>
                        <p class="text-xs text-green-600">Approved {{ $instructor->approved_at?->diffForHumans() ?? '' }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <button type="button" onclick="openSuperModal({{ $instructor->id }}, '{{ $instructor->name }}')" class="px-3 py-1.5 text-xs font-semibold {{ $instructor->is_super_instructor ? 'bg-violet-100 text-violet-700' : 'bg-slate-100 text-slate-700' }} rounded-lg hover:opacity-90 transition-all" title="Toggle super instructor status">
                        <i class="ri-shield-star-line"></i> {{ $instructor->is_super_instructor ? 'Super' : 'Standard' }}
                    </button>
                    <form method="POST" action="/admin/settings/instructors/{{ $instructor->id }}/disapprove" class="inline" x-data @submit.prevent="if(confirm('Revoke approval for {{ $instructor->name }}?')) $el.submit()">
                        @csrf
                        <button class="px-3 py-1.5 text-xs font-semibold bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-all" title="Revoke approval">
                            <i class="ri-close-line"></i> Revoke
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <p class="text-sm text-heading/50 text-center py-8">No approved instructors yet.</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Super Instructor Modal -->
<div id="superModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4" onclick="closeSuperModal(event)">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h3 class="text-xl font-bold text-heading flex items-center gap-2">
                <i class="ri-shield-star-line text-violet-600"></i>
                Super Instructor
            </h3>
            <button onclick="closeSuperModal()" class="text-heading/60 hover:text-heading transition-colors">
                <i class="ri-close-line text-2xl"></i>
            </button>
        </div>
        <form method="POST" id="superForm" action="" class="p-6 space-y-4">
            @csrf
            <div>
                <p class="text-sm font-semibold text-heading mb-2">Instructor:</p>
                <p id="instructorName" class="text-lg font-bold text-heading/80"></p>
            </div>
            <div>
                <label class="block text-sm font-semibold text-heading mb-2">
                    <i class="ri-message-2-line mr-1"></i>Reason for this change
                </label>
                <textarea name="super_reason" id="superReason" rows="4" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent" placeholder="e.g., Demonstrated strong teaching skills, experience with advanced course creation, etc." required></textarea>
                <p class="text-xs text-heading/60 mt-2">This helps document why this instructor was promoted to super status.</p>
            </div>
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                <p class="text-xs text-blue-700"><strong>ℹ️ Super Instructor Privileges:</strong></p>
                <ul class="text-xs text-blue-700 mt-1 space-y-1 ml-3">
                    <li>• Can create category-level courses</li>
                    <li>• Access to advanced course management</li>
                    <li>• Can manage instructor permissions</li>
                    <li>• Enhanced reporting capabilities</li>
                </ul>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeSuperModal()" class="flex-1 px-4 py-2.5 border border-gray-200 text-heading font-semibold rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button type="submit" class="flex-1 px-4 py-2.5 bg-violet-600 text-white font-semibold rounded-lg hover:bg-violet-700 transition-colors">
                    Confirm Change
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openSuperModal(instructorId, instructorName) {
    const modal = document.getElementById('superModal');
    const form = document.getElementById('superForm');
    const nameEl = document.getElementById('instructorName');
    const reasonEl = document.getElementById('superReason');
    
    nameEl.textContent = instructorName;
    reasonEl.value = '';
    form.action = `/admin/settings/instructors/${instructorId}/toggle-super`;
    modal.style.display = 'flex';
}

function closeSuperModal(event) {
    if (event && event.target.id !== 'superModal') return;
    document.getElementById('superModal').style.display = 'none';
}

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeSuperModal();
});
</script>
