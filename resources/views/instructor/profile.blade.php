@extends('layouts.dashboard')
@section('title', 'My Profile')
@section('page-title', 'Profile')
@section('user-name', 'Instructor')
@section('sidebar')@include('components.instructor-sidebar')@stop
@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-blue-500 to-indigo-500 rounded-2xl p-6 text-white shadow-sm">
        <div class="flex items-center justify-between gap-4 flex-wrap">
            <div class="flex items-center gap-4">
                <div class="w-20 h-20 rounded-full bg-white/20 flex items-center justify-center border-2 border-white/40">
                    <i class="ri-user-3-line text-4xl text-white\"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-extrabold">{{ auth()->user()->full_name }}</h3>
                    <p class="text-blue-100 mt-1">{{ auth()->user()->designation ?? 'Instructor' }}</p>
                </div>
            </div>
            @if(auth()->user()->isSuperInstructor())
                <div class="px-4 py-2 bg-white/20 rounded-lg border border-white/40 backdrop-blur-sm\">
                    <div class=\"flex items-center gap-2 text-white font-semibold\">
                        <i class=\"ri-shield-star-fill text-lg\"></i>
                        Super Instructor
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class=\"grid lg:grid-cols-3 gap-6\">
        <!-- Profile Information -->
        <div class=\"lg:col-span-2 space-y-6\">
            <div class=\"bg-white rounded-2xl shadow-sm border border-gray-100 p-6\">
                <h4 class=\"text-lg font-bold text-heading mb-4 flex items-center gap-2\">
                    <i class=\"ri-user-line text-blue-600\"></i>
                    Personal Information
                </h4>
                <div class=\"grid md:grid-cols-2 gap-6\">
                    <div>
                        <p class=\"text-xs uppercase tracking-[0.25em] text-heading/60 font-bold\">Full Name</p>
                        <p class=\"mt-2 text-heading font-semibold\">{{ auth()->user()->full_name }}</p>
                    </div>
                    <div>
                        <p class=\"text-xs uppercase tracking-[0.25em] text-heading/60 font-bold\">Email</p>
                        <p class=\"mt-2 text-heading font-semibold\">{{ auth()->user()->email }}</p>
                    </div>
                    <div>
                        <p class=\"text-xs uppercase tracking-[0.25em] text-heading/60 font-bold\">Phone</p>
                        <p class=\"mt-2 text-heading font-semibold\">{{ auth()->user()->phone ?? 'Not provided' }}</p>
                    </div>
                    <div>
                        <p class=\"text-xs uppercase tracking-[0.25em] text-heading/60 font-bold\">Designation</p>
                        <p class=\"mt-2 text-heading font-semibold\">{{ auth()->user()->designation ?? 'Instructor' }}</p>
                    </div>
                    <div class=\"md:col-span-2\">
                        <p class=\"text-xs uppercase tracking-[0.25em] text-heading/60 font-bold\">Address</p>
                        <p class=\"mt-2 text-heading font-semibold\">{{ auth()->user()->address ?? 'Not provided' }}</p>
                    </div>
                </div>
            </div>

            <!-- Super Instructor Details -->
            @if(auth()->user()->isSuperInstructor())
                <div class=\"bg-gradient-to-br from-violet-50 to-indigo-50 rounded-2xl border-2 border-violet-200 p-6\">
                    <div class=\"flex items-start gap-4\">
                        <div class=\"w-12 h-12 rounded-full bg-violet-200 flex items-center justify-center shrink-0\">
                            <i class=\"ri-shield-star-fill text-xl text-violet-700\"></i>
                        </div>
                        <div class=\"flex-1\">
                            <h4 class=\"text-lg font-bold text-heading flex items-center gap-2\">
                                Super Instructor Status
                                <span class=\"px-2 py-1 bg-violet-600 text-white text-xs rounded-full font-bold\">ACTIVE</span>
                            </h4>
                            <p class=\"text-sm text-heading/70 mt-2\">You have been granted super instructor privileges. This status provides you with advanced capabilities and access to exclusive features.</p>
                            
                            @if(auth()->user()->super_reason_date)
                                <div class=\"mt-4 p-3 bg-white/60 rounded-lg border border-violet-100\">
                                    <p class=\"text-xs uppercase tracking-[0.25em] text-heading/60 font-bold\">Promotion Details</p>
                                    <p class=\"text-sm text-heading mt-1\"><strong>Date Granted:</strong> {{ auth()->user()->super_reason_date->format('M d, Y \\a\\t g:i A') }}</p>
                                    @if(auth()->user()->super_reason)
                                        <p class=\"text-sm text-heading mt-2\"><strong>Reason for Promotion:</strong></p>
                                        <p class=\"text-sm text-heading/80 italic mt-1 border-l-3 border-violet-300 pl-3\">{{ auth()->user()->super_reason }}</p>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Extended Permissions -->
                <div class=\"bg-white rounded-2xl shadow-sm border border-gray-100 p-6\">
                    <h4 class=\"text-lg font-bold text-heading mb-4 flex items-center gap-2\">
                        <i class=\"ri-shield-check-line text-emerald-600\"></i>
                        Super Instructor Permissions
                    </h4>
                    <div class=\"grid md:grid-cols-2 gap-4\">
                        <div class=\"flex items-start gap-3 p-3 bg-emerald-50 rounded-lg border border-emerald-100\">
                            <i class=\"ri-check-circle-fill text-emerald-600 mt-1 shrink-0\"></i>
                            <div class=\"min-w-0\">
                                <p class=\"font-semibold text-heading text-sm\">Category Courses</p>
                                <p class=\"text-xs text-heading/60 mt-1\">Create and manage courses at the category level</p>
                            </div>
                        </div>
                        <div class=\"flex items-start gap-3 p-3 bg-emerald-50 rounded-lg border border-emerald-100\">
                            <i class=\"ri-check-circle-fill text-emerald-600 mt-1 shrink-0\"></i>
                            <div class=\"min-w-0\">
                                <p class=\"font-semibold text-heading text-sm\">Advanced Management</p>
                                <p class=\"text-xs text-heading/60 mt-1\">Access to advanced course and student management tools</p>
                            </div>
                        </div>
                        <div class=\"flex items-start gap-3 p-3 bg-emerald-50 rounded-lg border border-emerald-100\">
                            <i class=\"ri-check-circle-fill text-emerald-600 mt-1 shrink-0\"></i>
                            <div class=\"min-w-0\">
                                <p class=\"font-semibold text-heading text-sm\">Instructor Management</p>
                                <p class=\"text-xs text-heading/60 mt-1\">Manage permissions for other instructors</p>
                            </div>
                        </div>
                        <div class=\"flex items-start gap-3 p-3 bg-emerald-50 rounded-lg border border-emerald-100\">
                            <i class=\"ri-check-circle-fill text-emerald-600 mt-1 shrink-0\"></i>
                            <div class=\"min-w-0\">
                                <p class=\"font-semibold text-heading text-sm\">Enhanced Reporting</p>
                                <p class=\"text-xs text-heading/60 mt-1\">Advanced analytics and detailed student performance reports</p>
                            </div>
                        </div>
                        <div class=\"flex items-start gap-3 p-3 bg-emerald-50 rounded-lg border border-emerald-100\">
                            <i class=\"ri-check-circle-fill text-emerald-600 mt-1 shrink-0\"></i>
                            <div class=\"min-w-0\">
                                <p class=\"font-semibold text-heading text-sm\">Batch Operations</p>
                                <p class=\"text-xs text-heading/60 mt-1\">Perform bulk actions on courses and student enrollments</p>
                            </div>
                        </div>
                        <div class=\"flex items-start gap-3 p-3 bg-emerald-50 rounded-lg border border-emerald-100\">
                            <i class=\"ri-check-circle-fill text-emerald-600 mt-1 shrink-0\"></i>
                            <div class=\"min-w-0\">
                                <p class=\"font-semibold text-heading text-sm\">Custom Settings</p>
                                <p class=\"text-xs text-heading/60 mt-1\">Customize course settings and learning environment</p>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class=\"bg-blue-50 rounded-2xl border border-blue-200 p-6\">
                    <div class=\"flex items-start gap-4\">
                        <div class=\"w-12 h-12 rounded-full bg-blue-200 flex items-center justify-center shrink-0\">
                            <i class=\"ri-information-line text-xl text-blue-700\"></i>
                        </div>
                        <div>
                            <h4 class=\"font-bold text-heading\">Standard Instructor</h4>
                            <p class=\"text-sm text-heading/70 mt-2\">You are currently a standard instructor. For advanced features and permissions, contact your administrator.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Quick Stats -->
        <div class=\"space-y-6\">
            <div class=\"bg-white rounded-2xl shadow-sm border border-gray-100 p-6\">
                <p class=\"text-xs uppercase tracking-[0.25em] text-heading/60 font-bold\">Account Status</p>
                <div class=\"mt-4 flex items-center gap-2\">
                    <div class=\"w-3 h-3 rounded-full {{ auth()->user()->status === 'active' ? 'bg-emerald-500' : 'bg-gray-400' }}\"></div>
                    <p class=\"text-heading font-semibold capitalize\">{{ auth()->user()->status }}</p>
                </div>
            </div>

            <div class=\"bg-white rounded-2xl shadow-sm border border-gray-100 p-6\">
                <p class=\"text-xs uppercase tracking-[0.25em] text-heading/60 font-bold\">Instructor Since</p>
                <p class=\"mt-4 text-heading font-semibold\">{{ auth()->user()->created_at->format('M d, Y') }}</p>
                <p class=\"text-xs text-heading/60 mt-1\">{{ auth()->user()->created_at->diffForHumans() }}</p>
            </div>

            <div class=\"bg-white rounded-2xl shadow-sm border border-gray-100 p-6\">
                <p class=\"text-xs uppercase tracking-[0.25em] text-heading/60 font-bold\">Last Activity</p>
                <p class=\"mt-4 text-heading font-semibold\">{{ auth()->user()->last_activity_at?->format('M d, Y') ?? 'N/A' }}</p>
                <p class=\"text-xs text-heading/60 mt-1\">{{ auth()->user()->last_activity_at?->diffForHumans() ?? 'Never' }}</p>
            </div>

            <a href=\"/instructor/edit-profile\" class=\"w-full px-5 py-3 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition-colors flex items-center justify-center gap-2\">
                <i class=\"ri-edit-line\"></i> Edit Profile
            </a>
        </div>
    </div>
</div>
@endsection
