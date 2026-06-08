<a href="/dashboard" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('dashboard') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
    <i class="ri-dashboard-line text-lg"></i><span>Dashboard</span>
</a>
<a href="/dashboard/my-enrolled-course" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('dashboard/my-enrolled-course') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
    <i class="ri-book-open-line text-lg"></i><span>My Enrolled Course</span>
</a>
<a href="/dashboard/purchase-course" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('dashboard/purchase-course') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
    <i class="ri-shopping-cart-line text-lg"></i><span>Course Purchase</span>
</a>
<a href="/dashboard/bundle-course" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('dashboard/bundle-course') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
    <i class="ri-price-tag-3-line text-lg"></i><span>Bundle Purchase</span>
</a>
<a href="/dashboard/certificate" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('dashboard/certificate') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
    <i class="ri-award-line text-lg"></i><span>Certificate</span>
</a>
<a href="/dashboard/quizzes/my-result" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('dashboard/quizzes/*') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
    <i class="ri-questionnaire-line text-lg"></i><span>My Result</span>
</a>
<a href="/dashboard/assignments" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('dashboard/assignments') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
    <i class="ri-file-list-3-line text-lg"></i><span>Assignment</span>
</a>
<a href="/dashboard/course-review" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('dashboard/course-review') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
    <i class="ri-star-line text-lg"></i><span>Review</span>
</a>
<a href="/dashboard/offline-payment" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('dashboard/offline-payment') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
    <i class="ri-bank-card-line text-lg"></i><span>Offline Payment</span>
</a>
<div class="pt-3 mt-3 border-t border-gray-100">
    <p class="px-3 text-xs font-semibold text-heading/40 uppercase tracking-wider mb-2">Support</p>
    <a href="/dashboard/supports/create" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('dashboard/supports/create') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
        <i class="ri-add-circle-line text-lg"></i><span>New Ticket</span>
    </a>
    <a href="/dashboard/supports" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('dashboard/supports') && !request()->is('dashboard/supports/create') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
        <i class="ri-question-answer-line text-lg"></i><span>Ticket</span>
    </a>
    <a href="/dashboard/course-support" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('dashboard/course-support') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
        <i class="ri-customer-service-line text-lg"></i><span>Course Support</span>
    </a>
</div>
<a href="/dashboard/notifications" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('dashboard/notifications') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
    <i class="ri-notification-3-line text-lg"></i><span>Notification</span>
</a>
<a href="/notifications/preferences" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('notifications/preferences') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
    <i class="ri-settings-3-line text-lg"></i><span>Notif. Preferences</span>
</a>
<a href="/dashboard/wishlists" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('dashboard/wishlists') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
    <i class="ri-heart-line text-lg"></i><span>Wishlist</span>
</a>
<div class="pt-3 mt-3 border-t border-gray-100">
    <p class="px-3 text-xs font-semibold text-heading/40 uppercase tracking-wider mb-2">Account</p>
    <a href="/dashboard/profile" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('dashboard/profile') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
        <i class="ri-user-line text-lg"></i><span>Profile</span>
    </a>
    <a href="/dashboard/settings" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('dashboard/settings') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
        <i class="ri-settings-3-line text-lg"></i><span>Settings</span>
    </a>
</div>