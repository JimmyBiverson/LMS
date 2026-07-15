<a href="/instructor" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('instructor') && request()->path() == 'instructor' ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
    <i class="ri-dashboard-line text-lg"></i><span>Dashboard</span>
</a>
<div class="pt-3 mt-3 border-t border-gray-100">
    <p class="px-3 text-xs font-semibold text-heading/40 uppercase tracking-wider mb-2">Course Manage</p>
    <a href="/instructor/courses" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('instructor/courses') && !request()->is('instructor/courses/create') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
        <i class="ri-book-open-line text-lg"></i><span>All Courses</span>
    </a>
    <a href="/instructor/courses/create" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('instructor/courses/create') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
        <i class="ri-add-circle-line text-lg"></i><span>Create Course</span>
    </a>
</div>
<a href="/instructor/earnings" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('instructor/earnings') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
    <i class="ri-money-dollar-circle-line text-lg"></i><span>Earnings</span>
</a>
<a href="/instructor/pending-payments" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('instructor/pending-payments') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
    <i class="ri-bank-card-line text-lg"></i><span>Pending Payments</span>
</a>
<a href="/instructor/payouts" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('instructor/payouts') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
    <i class="ri-hand-coin-line text-lg"></i><span>Payouts</span>
</a>
<a href="/instructor/students" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('instructor/students') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
    <i class="ri-group-line text-lg"></i><span>Students</span>
</a>
<a href="/instructor/reviews" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('instructor/reviews') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
    <i class="ri-star-line text-lg"></i><span>Reviews</span>
    @php $pendingReviewCount = \App\Models\Review::whereHas('course', fn($q) => $q->where('user_id', auth()->id()))->where('is_approved', false)->count(); @endphp
    @if($pendingReviewCount > 0)<span class="ml-auto w-5 h-5 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center">{{ min($pendingReviewCount, 99) }}</span>@endif
</a>
<a href="/instructor/quiz" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('instructor/quiz') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
        <i class="ri-questionnaire-line text-lg"></i><span>Quizzes &amp; Exams</span>
</a>
<a href="/instructor/assignments" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('instructor/assignments') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
    <i class="ri-file-list-3-line text-lg"></i><span>Assignments</span>
</a>
<a href="/instructor/course-notes" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('instructor/course-notes*') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
    <i class="ri-file-paper-2-line text-lg"></i><span>Course Notes</span>
</a>
<div class="pt-3 mt-3 border-t border-gray-100">
    <p class="px-3 text-xs font-semibold text-heading/40 uppercase tracking-wider mb-2">Support</p>
    <a href="/instructor/supports" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('instructor/supports') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
        <i class="ri-question-answer-line text-lg"></i><span>Tickets</span>
    </a>
    <a href="/instructor/contact-messages" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('instructor/contact-messages') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
        <i class="ri-mail-open-line text-lg"></i><span>Contact Messages</span>
        @php $unreadContact = \App\Models\ContactMessage::where('instructor_id', auth()->id())->where('is_read', false)->count(); @endphp
        @if($unreadContact > 0)<span class="ml-auto w-5 h-5 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center">{{ min($unreadContact, 99) }}</span>@endif
    </a>
</div>
<a href="/instructor/notifications" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('instructor/notifications') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
    <i class="ri-notification-3-line text-lg"></i><span>Notifications</span>
    @php $unreadNotif = \App\Models\NotificationLog::where('user_id', auth()->id())->where('is_read', false)->count(); @endphp
    @if($unreadNotif > 0)<span class="ml-auto w-5 h-5 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center">{{ min($unreadNotif, 99) }}</span>@endif
</a>
<a href="/notifications/send" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('notifications/send') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
    <i class="ri-send-plane-line text-lg"></i><span>Send Notification</span>
</a>
<a href="/instructor/settings" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('instructor/settings') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
    <i class="ri-settings-3-line text-lg"></i><span>Settings</span>
</a>