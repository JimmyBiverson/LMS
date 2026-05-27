<a href="/instructor/dashboard" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('instructor/dashboard') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
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
<a href="/instructor/students" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('instructor/students') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
    <i class="ri-group-line text-lg"></i><span>Students</span>
</a>
<a href="/instructor/reviews" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('instructor/reviews') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
    <i class="ri-star-line text-lg"></i><span>Reviews</span>
</a>
<a href="/instructor/quiz" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('instructor/quiz') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
    <i class="ri-questionnaire-line text-lg"></i><span>Quiz</span>
</a>
<a href="/instructor/assignments" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('instructor/assignments') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
    <i class="ri-file-list-3-line text-lg"></i><span>Assignments</span>
</a>
<div class="pt-3 mt-3 border-t border-gray-100">
    <p class="px-3 text-xs font-semibold text-heading/40 uppercase tracking-wider mb-2">Support</p>
    <a href="/instructor/supports" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('instructor/supports') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
        <i class="ri-question-answer-line text-lg"></i><span>Tickets</span>
    </a>
</div>
<a href="/instructor/notification" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('instructor/notification') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
    <i class="ri-notification-3-line text-lg"></i><span>Notification</span>
</a>
<a href="/instructor/setting" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('instructor/setting') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
    <i class="ri-settings-3-line text-lg"></i><span>Settings</span>
</a>