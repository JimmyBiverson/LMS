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
            <div class="flex items-center justify-between p-3 rounded-lg bg-amber-50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center">
                        <i class="ri-user-star-line text-amber-600"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-heading text-sm">{{ $instructor->name }}</p>
                        <p class="text-xs text-heading/60">{{ $instructor->email }} | {{ $instructor->designation ?? 'Instructor' }}</p>
                        <p class="text-xs text-heading/40">Registered {{ $instructor->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <form method="POST" action="/admin/settings/instructors/{{ $instructor->id }}/approve">
                        @csrf
                        <button class="px-3 py-1.5 text-xs font-semibold bg-green-500 text-white rounded-lg hover:bg-green-600 transition-all">
                            <i class="ri-check-line"></i> Approve
                        </button>
                    </form>
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
            <div class="flex items-center justify-between p-3 rounded-lg bg-green-50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                        <i class="ri-user-star-fill text-green-600"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-heading text-sm">{{ $instructor->name }}</p>
                        <p class="text-xs text-heading/60">{{ $instructor->email }} | {{ $instructor->designation ?? 'Instructor' }}</p>
                        <p class="text-xs text-green-600">Approved {{ $instructor->approved_at?->diffForHumans() ?? '' }}</p>
                    </div>
                </div>
                <form method="POST" action="/admin/settings/instructors/{{ $instructor->id }}/disapprove" x-data @submit.prevent="if(confirm('Revoke approval for {{ $instructor->name }}?')) $el.submit()">
                    @csrf
                    <button class="px-3 py-1.5 text-xs font-semibold bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-all">
                        <i class="ri-close-line"></i> Revoke
                    </button>
                </form>
            </div>
            @empty
            <p class="text-sm text-heading/50 text-center py-8">No approved instructors yet.</p>
            @endforelse
        </div>
    </div>
</div>
