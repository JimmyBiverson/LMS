@extends('layouts.dashboard')
@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')
@section('user-name', auth()->user()->name ?? 'Admin')
@section('sidebar')@include('components.admin-sidebar')@stop

@section('content')
@php
$roleBg = 'bg-slate-50';
$roleSidebarBorder = 'border-slate-200';
$roleLogoBg = 'bg-slate-800';
$roleAccent = 'slate-700';
$roleHover = 'slate-100';
$roleHeaderBg = 'bg-white';
$roleAvatarBg = 'bg-slate-100';
$roleAvatarText = 'text-slate-700';
$notifUrl = url('admin/notification');
@endphp

@if($symlinkWarning)
<div class="mb-6 p-5 bg-amber-50 border border-amber-200 rounded-xl text-amber-900 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div class="flex items-start gap-3 flex-1">
        <i class="ri-error-warning-fill text-2xl text-amber-500 shrink-0 mt-0.5"></i>
        <div>
            <h4 class="font-bold text-sm text-amber-800">Media Storage Link Missing/Broken</h4>
            <p class="text-xs text-amber-700/90 mt-1">LMS media uploads (logos, thumbnails, profile images) will return 404 errors until the public symlink is connected.</p>
        </div>
    </div>
    <a href="{{ url('admin/storage-health') }}" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold rounded-lg transition-colors inline-flex items-center gap-1.5 shrink-0 self-start sm:self-auto shadow-sm shadow-amber-600/10">
        <i class="ri-tools-line"></i> Verify & Fix
    </a>
</div>
@endif

{{-- Stat Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
    <div class="bg-gradient-to-br from-slate-700 to-slate-900 rounded-xl p-5 shadow-lg text-white">
        <div class="flex items-center justify-between mb-2"><p class="text-slate-300 text-xs font-semibold uppercase tracking-wider">Students</p><i class="ri-group-line text-slate-400 text-lg"></i></div>
        <p class="text-3xl font-extrabold">{{ $totalStudents }}</p>
        <div class="mt-1 text-slate-400 text-xs">platform learners</div>
    </div>
    <div class="bg-gradient-to-br from-slate-600 to-slate-800 rounded-xl p-5 shadow-lg text-white">
        <div class="flex items-center justify-between mb-2"><p class="text-slate-300 text-xs font-semibold uppercase tracking-wider">Courses</p><i class="ri-book-open-line text-slate-400 text-lg"></i></div>
        <p class="text-3xl font-extrabold">{{ $totalCourses }}</p>
        <div class="mt-1 text-slate-400 text-xs">{{ $activeCourses }} active</div>
    </div>
    <div class="bg-gradient-to-br from-blue-600 to-blue-800 rounded-xl p-5 shadow-lg text-white">
        <div class="flex items-center justify-between mb-2"><p class="text-blue-200 text-xs font-semibold uppercase tracking-wider">Instructors</p><i class="ri-user-star-line text-blue-300 text-lg"></i></div>
        <p class="text-3xl font-extrabold">{{ $totalInstructors }}</p>
        <div class="mt-1 text-blue-300 text-xs">{{ $totalPendingInstructors }} pending approval</div>
    </div>
    <div class="bg-gradient-to-br from-emerald-600 to-emerald-800 rounded-xl p-5 shadow-lg text-white">
        <div class="flex items-center justify-between mb-2"><p class="text-emerald-200 text-xs font-semibold uppercase tracking-wider">Enrollments</p><i class="ri-user-add-line text-emerald-300 text-lg"></i></div>
        <p class="text-3xl font-extrabold">{{ $totalEnrollments }}</p>
        <div class="mt-1 text-emerald-300 text-xs">total enrollments</div>
    </div>
    <div class="bg-gradient-to-br from-amber-600 to-amber-800 rounded-xl p-5 shadow-lg text-white">
        <div class="flex items-center justify-between mb-2"><p class="text-amber-200 text-xs font-semibold uppercase tracking-wider">Revenue</p><i class="ri-money-dollar-circle-line text-amber-300 text-lg"></i></div>
        <p class="text-3xl font-extrabold">{{ \App\Helpers\CurrencyHelper::format($totalRevenue) }}</p>
        <div class="mt-1 text-amber-300 text-xs">total revenue</div>
    </div>
</div>

{{-- Pending Approvals --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
    <a href="{{ url('admin/settings/approve-instructors') }}" class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-amber-500 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-2"><p class="text-heading/50 text-xs font-semibold uppercase tracking-wider">Pending Instructors</p><i class="ri-user-star-line text-amber-500 text-lg"></i></div>
        <p class="text-3xl font-extrabold text-heading">{{ $totalPendingInstructors }}</p>
        <div class="mt-1 flex items-center gap-1 text-xs {{ $totalPendingInstructors > 0 ? 'text-amber-600' : 'text-green-600' }}">
            @if($totalPendingInstructors > 0)
            <i class="ri-time-line"></i> awaiting approval
            @else
            <i class="ri-check-line"></i> all approved
            @endif
        </div>
    </a>
    <a href="{{ url('admin/review') }}" class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-blue-500 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-2"><p class="text-heading/50 text-xs font-semibold uppercase tracking-wider">Pending Reviews</p><i class="ri-star-line text-blue-500 text-lg"></i></div>
        <p class="text-3xl font-extrabold text-heading">{{ $pendingReviews }}</p>
        <div class="mt-1 flex items-center gap-1 text-xs {{ $pendingReviews > 0 ? 'text-blue-600' : 'text-green-600' }}">
            @if($pendingReviews > 0)
            <i class="ri-time-line"></i> awaiting moderation
            @else
            <i class="ri-check-line"></i> all moderated
            @endif
        </div>
    </a>
    <a href="{{ url('admin/notification') }}" class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-purple-500 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-2"><p class="text-heading/50 text-xs font-semibold uppercase tracking-wider">System Notifications</p><i class="ri-notification-3-line text-purple-500 text-lg"></i></div>
        <p class="text-3xl font-extrabold text-heading">{{ \App\Models\NotificationLog::where('is_read', false)->count() }}</p>
        <div class="mt-1 text-xs text-heading/40">unread notifications</div>
    </a>
</div>

{{-- Charts Row 1: Pie Charts + Bar Chart --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="font-bold text-heading mb-4 text-sm flex items-center gap-2"><span class="w-1.5 h-5 bg-slate-700 rounded-full"></span> Users by Role</h3>
        <div style="position:relative;height:240px;">
            <canvas id="roleChart"></canvas>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="font-bold text-heading mb-4 text-sm flex items-center gap-2"><span class="w-1.5 h-5 bg-primary rounded-full"></span> Courses by Category</h3>
        <div style="position:relative;height:240px;">
            <canvas id="categoryChart"></canvas>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="font-bold text-heading mb-4 text-sm flex items-center gap-2"><span class="w-1.5 h-5 bg-emerald-500 rounded-full"></span> This Week Enrollments</h3>
        <div style="position:relative;height:240px;">
            <canvas id="weeklyChart"></canvas>
        </div>
    </div>
</div>

{{-- Secondary Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-slate-700">
        <p class="text-heading/50 text-xs font-semibold uppercase tracking-wider">Organizations</p>
        <p class="text-2xl font-extrabold text-heading mt-1">{{ $totalOrganizations }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-blue-500">
        <p class="text-heading/50 text-xs font-semibold uppercase tracking-wider">Active Courses</p>
        <p class="text-2xl font-extrabold text-heading mt-1">{{ $activeCourses }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-emerald-500">
        <p class="text-heading/50 text-xs font-semibold uppercase tracking-wider">Total Users</p>
        <p class="text-2xl font-extrabold text-heading mt-1">{{ $totalUsers }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-amber-500">
        <p class="text-heading/50 text-xs font-semibold uppercase tracking-wider">Certificates</p>
        <p class="text-2xl font-extrabold text-heading mt-1">{{ $totalCertificates }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-purple-500">
        <p class="text-heading/50 text-xs font-semibold uppercase tracking-wider">Pending Reviews</p>
        <p class="text-2xl font-extrabold text-heading mt-1">{{ $pendingReviews }}</p>
    </div>
</div>

{{-- Monthly Trends Chart --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="font-bold text-heading mb-6 text-sm flex items-center gap-2"><span class="w-1.5 h-5 bg-slate-700 rounded-full"></span> Monthly Trends ({{ now()->subMonths(6)->format('M Y') }} — {{ now()->format('M Y') }})</h3>
        <div style="position:relative;height:220px;">
            <canvas id="monthlyChart"></canvas>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="font-bold text-heading mb-4 text-sm flex items-center gap-2"><span class="w-1.5 h-5 bg-slate-700 rounded-full"></span> Platform Overview</h3>
        <div class="grid grid-cols-2 gap-4">
            <div class="text-center p-4 rounded-lg bg-slate-50"><p class="text-2xl font-extrabold text-slate-700">{{ number_format($totalUsers) }}</p><p class="text-xs text-heading/50 mt-1">Total Users</p></div>
            <div class="text-center p-4 rounded-lg bg-blue-50"><p class="text-2xl font-extrabold text-blue-700">{{ $activeCategories }}</p><p class="text-xs text-heading/50 mt-1">Categories</p></div>
            <div class="text-center p-4 rounded-lg bg-emerald-50"><p class="text-2xl font-extrabold text-emerald-700">{{ $publishedBlogs }}</p><p class="text-xs text-heading/50 mt-1">Published Blogs</p></div>
            <div class="text-center p-4 rounded-lg bg-amber-50"><p class="text-2xl font-extrabold text-amber-700">{{ \App\Helpers\CurrencyHelper::format($totalRevenue) }}</p><p class="text-xs text-heading/50 mt-1">Total Revenue</p></div>
        </div>
    </div>
</div>

{{-- Recent Enrollments + Recent Certificates --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl shadow-sm">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-heading flex items-center gap-2"><span class="w-1.5 h-5 bg-slate-700 rounded-full"></span>Recent Enrollments</h3>
            <a href="/admin/enrollment/all" class="text-sm text-slate-600 font-semibold hover:underline">View All</a>
        </div>
        <div class="p-4 space-y-3">
            @forelse($recent as $e)
            <div class="flex items-center gap-3 py-2 border-b border-gray-50 last:border-0">
                <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center"><i class="ri-user-smile-line text-slate-600"></i></div>
                <div class="flex-1"><p class="font-semibold text-heading text-sm">{{ $e->user?->name ?? 'User' }}</p><p class="text-xs text-heading/50">{{ $e->course?->title ?? 'Course' }}</p></div>
                <span class="text-xs text-heading/40">{{ $e->created_at->diffForHumans() }}</span>
            </div>
            @empty <p class="text-sm text-heading/50 p-4">No enrollments yet.</p> @endforelse
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-heading flex items-center gap-2"><span class="w-1.5 h-5 bg-amber-500 rounded-full"></span> Recent Certificates</h3>
            <a href="/admin/certificate" class="text-sm text-slate-600 font-semibold hover:underline">View All</a>
        </div>
        <div class="p-4 space-y-3">
            @forelse($recentCert as $c)
            <div class="flex items-center gap-3 py-2 border-b border-gray-50 last:border-0">
                <div class="w-10 h-10 rounded-full bg-amber-50 flex items-center justify-center"><i class="ri-award-line text-amber-500"></i></div>
                <div class="flex-1"><p class="font-semibold text-heading text-sm">{{ $c->title }}</p><p class="text-xs text-heading/50">{{ $c->course?->title ?? 'Course' }}</p></div>
                <span class="text-xs text-heading/40">{{ $c->created_at->diffForHumans() }}</span>
            </div>
            @empty <p class="text-sm text-heading/50 p-4">No certificates yet.</p> @endforelse
        </div>
    </div>
</div>

<div class="mt-6 bg-white rounded-xl shadow-sm p-6">
    <h3 class="font-bold text-heading mb-4 flex items-center gap-2"><span class="w-1.5 h-5 bg-slate-700 rounded-full"></span> Popular Courses</h3>
    <div class="space-y-3">
        @forelse($popular as $c)
        <div class="flex items-center justify-between">
            <span class="text-sm text-heading/80 truncate max-w-[250px]">{{ $c->title }}</span>
            <div class="flex items-center gap-2">
                <div class="w-32 h-2 bg-gray-100 rounded-full overflow-hidden"><div class="h-full bg-slate-700 rounded-full" style="width: {{ $c->enrollments_count > 0 ? min(100, ($c->enrollments_count / $popular->max('enrollments_count')) * 100) : 0 }}%"></div></div>
                <span class="text-xs font-bold text-heading/60">{{ $c->enrollments_count }} enrolled</span>
            </div>
        </div>
        @empty <p class="text-sm text-heading/50">No courses yet.</p> @endforelse
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
window.currencySymbol = '{{ $school->currency_symbol ?? '$' }}';
document.addEventListener('DOMContentLoaded', function () {
    const colors = ['#5F3EED', '#F4B826', '#1AEBC5', '#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#FFEAA7'];

    // Role Distribution Doughnut Chart
    const roleRaw = @json($roleDistribution);
    const roleLabels = roleRaw.map(r => r.label);
    const roleValues = roleRaw.map(r => r.value);
    const roleTotal = roleValues.reduce((a, b) => a + b, 0);

    if (roleTotal === 0) {
        // Show empty state message instead of broken chart
        document.getElementById('roleChart').closest('div[style]').innerHTML =
            '<div class="flex items-center justify-center h-full text-slate-400 text-sm">No user data yet</div>';
    } else {
        new Chart(document.getElementById('roleChart'), {
            type: 'doughnut',
            data: {
                labels: roleLabels,
                datasets: [{
                    data: roleValues,
                    backgroundColor: colors,
                    borderWidth: 2,
                    borderColor: '#fff',
                    hoverBorderWidth: 3,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { usePointStyle: true, padding: 10, font: { size: 11 } }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(ctx) {
                                const pct = roleTotal > 0 ? ((ctx.parsed / roleTotal) * 100).toFixed(1) : 0;
                                return ` ${ctx.label}: ${ctx.parsed} (${pct}%)`;
                            }
                        }
                    }
                },
                cutout: '65%',
                animation: { animateRotate: true, duration: 800 }
            }
        });
    }

    // Category Pie Chart
    const catRaw = @json($courseDistribution);
    const catLabels = catRaw.map(c => c.label);
    const catValues = catRaw.map(c => c.value);
    const catTotal = catValues.reduce((a, b) => a + b, 0);

    if (catTotal === 0) {
        document.getElementById('categoryChart').closest('div[style]').innerHTML =
            '<div class="flex items-center justify-center h-full text-slate-400 text-sm">No category data yet</div>';
    } else {
        new Chart(document.getElementById('categoryChart'), {
            type: 'pie',
            data: {
                labels: catLabels,
                datasets: [{
                    data: catValues,
                    backgroundColor: colors,
                    borderWidth: 2,
                    borderColor: '#fff',
                    hoverBorderWidth: 3,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { usePointStyle: true, padding: 10, font: { size: 11 } }
                    }
                },
                animation: { animateRotate: true, duration: 800 }
            }
        });
    }

    // Weekly Bar Chart
    const weeklyCtx = document.getElementById('weeklyChart').getContext('2d');
    new Chart(weeklyCtx, {
        type: 'bar',
        data: {
            labels: @json($weeklyLabels),
            datasets: [{
                label: 'Enrollments',
                data: @json($weeklyData),
                backgroundColor: 'rgba(5, 150, 105, 0.75)',
                borderColor: '#059669',
                borderWidth: 1,
                borderRadius: 5,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1, precision: 0 }, grid: { color: 'rgba(0,0,0,0.05)' } },
                x: { grid: { display: false } }
            },
            animation: { duration: 700 }
        }
    });

    // Monthly Trends Line Chart
    const monthsRaw = @json($monthlyData->pluck('month'));
    const enrollCounts = @json($monthlyData->pluck('count'));
    const revenues = @json($monthlyData->pluck('revenue'));
    const monthLabels = monthsRaw.map(m => {
        const d = new Date(m + '-01');
        return d.toLocaleString('default', { month: 'short', year: '2-digit' });
    });

    new Chart(document.getElementById('monthlyChart'), {
        type: 'line',
        data: {
            labels: monthLabels,
            datasets: [
                {
                    label: 'Enrollments',
                    data: enrollCounts,
                    borderColor: '#5F3EED',
                    backgroundColor: 'rgba(95, 62, 237, 0.08)',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#5F3EED',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                },
                {
                    label: 'Revenue ($)',
                    data: revenues,
                    borderColor: '#059669',
                    backgroundColor: 'rgba(5, 150, 105, 0.08)',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#059669',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    yAxisID: 'y1',
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { position: 'top', labels: { usePointStyle: true, font: { size: 11 } } }
            },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1, precision: 0 }, grid: { color: 'rgba(0,0,0,0.05)' } },
                y1: {
                    beginAtZero: true,
                    position: 'right',
                    grid: { drawOnChartArea: false },
                    ticks: { callback: v => window.currencySymbol + v }
                },
                x: { grid: { display: false } }
            },
            animation: { duration: 700 }
        }
    });
});
</script>
@endpush
