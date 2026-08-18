<a href="/org" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('org') && request()->path() == 'org' ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
    <i class="ri-dashboard-line text-lg"></i><span>Dashboard</span>
</a>
<div class="pt-3 mt-3 border-t border-gray-100">
    <p class="px-3 text-xs font-semibold text-heading/40 uppercase tracking-wider mb-2">Course Manage</p>
    <a href="/org/courses" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('org/courses') && !request()->is('org/courses/create') && !request()->is('org/courses/bundle') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
        <i class="ri-book-open-line text-lg"></i><span>All Courses</span>
    </a>
    <a href="/org/courses/bundle" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('org/courses/bundle') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
        <i class="ri-price-tag-3-line text-lg"></i><span>Course Bundle</span>
    </a>
    <a href="/org/courses/create" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('org/courses/create') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
        <i class="ri-add-circle-line text-lg"></i><span>Create Course</span>
    </a>
</div>
<a href="/org/instructors" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('org/instructors') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
    <i class="ri-user-star-line text-lg"></i><span>Instructors</span>
</a>
<a href="/org/students" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('org/students') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
    <i class="ri-group-line text-lg"></i><span>Students</span>
</a>
<div class="pt-3 mt-3 border-t border-gray-100">
    <p class="px-3 text-xs font-semibold text-heading/40 uppercase tracking-wider mb-2">Financial</p>
    <a href="/org/financial" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('org/financial') && !request()->is('org/financial/payout') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
        <i class="ri-funds-line text-lg"></i><span>Sale History</span>
    </a>
    <a href="/org/financial/payout" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('org/financial/payout') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
        <i class="ri-hand-coin-line text-lg"></i><span>Payout</span>
    </a>
</div>
<a href="/org/reviews" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('org/reviews') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
    <i class="ri-star-line text-lg"></i><span>Review</span>
    @php $pendingReviewCount = \App\Models\Review::whereHas('course', fn($q) => $q->where('user_id', auth()->id()))->where('is_approved', false)->count(); @endphp
    @if($pendingReviewCount > 0)<span class="ml-auto w-5 h-5 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center">{{ min($pendingReviewCount, 99) }}</span>@endif
</a>
<div class="pt-3 mt-3 border-t border-gray-100">
    <p class="px-3 text-xs font-semibold text-heading/40 uppercase tracking-wider mb-2">Support</p>
    <a href="/org/supports" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('org/supports') && !request()->is('org/supports/create') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
        <i class="ri-question-answer-line text-lg"></i><span>Tickets</span>
    </a>
    <a href="/org/supports/create" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('org/supports/create') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
        <i class="ri-add-circle-line text-lg"></i><span>New Ticket</span>
    </a>
</div>
<a href="/org/noticeboard" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('org/noticeboard') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
    <i class="ri-megaphone-line text-lg"></i><span>Notice Board</span>
</a>
<a href="/org/notifications" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('org/notifications') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
    <i class="ri-notification-3-line text-lg"></i><span>Notification</span>
    @php $unreadNotif = \App\Models\NotificationLog::where('user_id', auth()->id())->where('is_read', false)->count(); @endphp
    @if($unreadNotif > 0)<span class="ml-auto w-5 h-5 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center">{{ min($unreadNotif, 99) }}</span>@endif
</a>
<a href="/notifications/send" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('notifications/send') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
    <i class="ri-send-plane-line text-lg"></i><span>Send Notification</span>
</a>
<a href="/org/wishlists" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('org/wishlists') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
    <i class="ri-heart-line text-lg"></i><span>Wishlist</span>
</a>
<a href="/org/profile" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('org/profile') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
    <i class="ri-user-line text-lg"></i><span>Profile</span>
</a>
<a href="/org/settings" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('org/settings') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
    <i class="ri-settings-3-line text-lg"></i><span>Settings</span>
</a>