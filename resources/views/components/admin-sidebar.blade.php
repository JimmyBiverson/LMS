<a href="/admin" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('admin') && request()->path() == 'admin' ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
    <i class="ri-dashboard-line text-lg"></i><span>Dashboard</span>
</a>
<div class="pt-3 mt-3 border-t border-gray-100">
    <p class="px-3 text-xs font-semibold text-heading/40 uppercase tracking-wider mb-2">Course Manage</p>
    <a href="/admin/course" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->is('admin/course') && !request()->is('admin/course/bundle') && !request()->is('admin/course/level') && !request()->is('admin/course/tag') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 text-sm font-semibold">
        <i class="ri-book-open-line text-lg"></i><span>All Courses</span>
    </a>
    <a href="/admin/course/bundle" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->is('admin/course/bundle') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 text-sm font-semibold">
        <i class="ri-price-tag-3-line text-lg"></i><span>Bundle Course</span>
    </a>
    <a href="/admin/course/level" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->is('admin/course/level') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 text-sm font-semibold">
        <i class="ri-bar-chart-line text-lg"></i><span>Course Level</span>
    </a>
    <a href="/admin/course/tag" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->is('admin/course/tag') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 text-sm font-semibold">
        <i class="ri-price-tag-3-line text-lg"></i><span>Course Tag</span>
    </a>
    <a href="/admin/category" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->is('admin/category') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 text-sm font-semibold">
        <i class="ri-bookmark-line text-lg"></i><span>Category</span>
    </a>
    <a href="/admin/subject" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->is('admin/subject') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 text-sm font-semibold">
        <i class="ri-book-mark-line text-lg"></i><span>Subject</span>
    </a>
</div>
<a href="/admin/instructors" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('admin/instructors') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
    <i class="ri-user-star-line text-lg"></i><span>Instructor</span>
</a>
<a href="/admin/students" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('admin/students') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
    <i class="ri-group-line text-lg"></i><span>Student</span>
</a>
<a href="/admin/organizations" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('admin/organizations') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
    <i class="ri-building-line text-lg"></i><span>Organization</span>
</a>
<a href="/admin/staff" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('admin/staff') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
    <i class="ri-user-settings-line text-lg"></i><span>Staff Manage</span>
</a>
<div class="pt-3 mt-3 border-t border-gray-100">
    <p class="px-3 text-xs font-semibold text-heading/40 uppercase tracking-wider mb-2">Blog Manage</p>
    <a href="/admin/blog" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->is('admin/blog') && !request()->is('admin/blog/category') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 text-sm font-semibold">
        <i class="ri-file-text-line text-lg"></i><span>All Blog</span>
    </a>
    <a href="/admin/blog/category" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->is('admin/blog/category') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 text-sm font-semibold">
        <i class="ri-bookmark-3-line text-lg"></i><span>Blog Category</span>
    </a>
</div>
<a href="/admin/faq" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('admin/faq') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
    <i class="ri-question-line text-lg"></i><span>Faq Manage</span>
</a>
<a href="/admin/page" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('admin/page') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
    <i class="ri-file-copy-line text-lg"></i><span>Page Manage</span>
</a>
<a href="/admin/slider" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('admin/slider') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
    <i class="ri-slideshow-line text-lg"></i><span>Slider</span>
</a>
<a href="/admin/hero" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('admin/hero') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
    <i class="ri-image-line text-lg"></i><span>Hero</span>
</a>
<a href="/admin/testimonial" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('admin/testimonial') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
    <i class="ri-quote-text text-lg"></i><span>Testimonial</span>
</a>
<a href="/admin/contact" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('admin/contact') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
    <i class="ri-mail-open-line text-lg"></i><span>Contact</span>
</a>
<a href="/admin/payment-method" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('admin/payment-method') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
    <i class="ri-bank-card-line text-lg"></i><span>Payment Method</span>
</a>
<div class="pt-3 mt-3 border-t border-gray-100">
    <p class="px-3 text-xs font-semibold text-heading/40 uppercase tracking-wider mb-2">Financial</p>
    <a href="/admin/financial/sale" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->is('admin/financial/sale') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 text-sm font-semibold">
        <i class="ri-funds-line text-lg"></i><span>Sale History</span>
    </a>
    <a href="/admin/financial/offline" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->is('admin/financial/offline') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 text-sm font-semibold">
        <i class="ri-bank-line text-lg"></i><span>Offline Payment</span>
    </a>
    <a href="/admin/financial/payouts" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->is('admin/financial/payouts') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 text-sm font-semibold">
        <i class="ri-hand-coin-line text-lg"></i><span>Payouts</span>
    </a>
</div>
<a href="/admin/certificate/create" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('admin/certificate/*') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
    <i class="ri-award-line text-lg"></i><span>Certificate Manage</span>
</a>
<div class="pt-3 mt-3 border-t border-gray-100">
    <p class="px-3 text-xs font-semibold text-heading/40 uppercase tracking-wider mb-2">Enrollment</p>
    <a href="/admin/enrollment/all" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->is('admin/enrollment/all') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 text-sm font-semibold">
        <i class="ri-file-list-3-line text-lg"></i><span>All Enrollments</span>
    </a>
    <a href="/admin/enrollment/new-create" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->is('admin/enrollment/new-create') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 text-sm font-semibold">
        <i class="ri-add-circle-line text-lg"></i><span>New Enrollment</span>
    </a>
</div>
<div class="pt-3 mt-3 border-t border-gray-100">
    <p class="px-3 text-xs font-semibold text-heading/40 uppercase tracking-wider mb-2">Marketing</p>
    <a href="/admin/marketing/coupon" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->is('admin/marketing/coupon') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 text-sm font-semibold">
        <i class="ri-coupon-line text-lg"></i><span>Coupon</span>
    </a>
</div>
<a href="/admin/review/course-review" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('admin/review/*') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
    <i class="ri-star-line text-lg"></i><span>Review</span>
</a>
<div class="pt-3 mt-3 border-t border-gray-100">
    <p class="px-3 text-xs font-semibold text-heading/40 uppercase tracking-wider mb-2">Notification</p>
    <a href="/admin/notification" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->is('admin/notification') && !request()->is('admin/notification/history') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 text-sm font-semibold">
        <i class="ri-mail-send-line text-lg"></i><span>Templates</span>
    </a>
    <a href="/admin/notification/history" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->is('admin/notification/history') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 text-sm font-semibold">
        <i class="ri-history-line text-lg"></i><span>History</span>
    </a>
</div>
<div class="pt-3 mt-3 border-t border-gray-100">
    <p class="px-3 text-xs font-semibold text-heading/40 uppercase tracking-wider mb-2">Ticket & Support</p>
    <a href="/admin/support-ticket/category" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->is('admin/support-ticket/category') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 text-sm font-semibold">
        <i class="ri-bookmark-line text-lg"></i><span>Support Category</span>
    </a>
    <a href="/admin/support-ticket/ticket" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->is('admin/support-ticket/ticket') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 text-sm font-semibold">
        <i class="ri-question-answer-line text-lg"></i><span>Tickets</span>
    </a>
</div>
<a href="/admin/meet-provider" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('admin/meet-provider') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
    <i class="ri-video-on-line text-lg"></i><span>Meet Provider</span>
</a>
<a href="/admin/lms-module/subscription" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('admin/lms-module/*') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
    <i class="ri-refund-line text-lg"></i><span>Subscription</span>
</a>
<div class="pt-3 mt-3 border-t border-gray-100">
    <p class="px-3 text-xs font-semibold text-heading/40 uppercase tracking-wider mb-2">Settings</p>
    <a href="/admin/theme-setting" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->is('admin/theme-setting') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 text-sm font-semibold">
        <i class="ri-palette-line text-lg"></i><span>Frontend Settings</span>
    </a>
    <a href="/admin/site-language" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->is('admin/site-language') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 text-sm font-semibold">
        <i class="ri-global-line text-lg"></i><span>Site Language</span>
    </a>
    <a href="/admin/language" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->is('admin/language') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 text-sm font-semibold">
        <i class="ri-translate-line text-lg"></i><span>Language</span>
    </a>
    <a href="/admin/theme" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->is('admin/theme') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 text-sm font-semibold">
        <i class="ri-paint-brush-line text-lg"></i><span>Theme</span>
    </a>
    <a href="/admin/currency" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->is('admin/currency') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 text-sm font-semibold">
        <i class="ri-money-dollar-circle-line text-lg"></i><span>Currency</span>
    </a>
    <a href="/admin/email-template" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->is('admin/email-template') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 text-sm font-semibold">
        <i class="ri-mail-send-line text-lg"></i><span>Email Template</span>
    </a>
    <a href="/admin/backend-setting" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->is('admin/backend-setting') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 text-sm font-semibold">
        <i class="ri-settings-3-line text-lg"></i><span>Backend Settings</span>
    </a>
    <a href="/admin/profile" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->is('admin/profile') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 text-sm font-semibold">
        <i class="ri-user-smile-line text-lg"></i><span>Profile</span>
    </a>
</div>
<div class="pt-3 mt-3 border-t border-gray-100">
    <p class="px-3 text-xs font-semibold text-heading/40 uppercase tracking-wider mb-2">Localization</p>
    <a href="/admin/localization/country" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->is('admin/localization/country') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 text-sm font-semibold">
        <i class="ri-map-pin-line text-lg"></i><span>Country</span>
    </a>
    <a href="/admin/localization/state" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->is('admin/localization/state') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 text-sm font-semibold">
        <i class="ri-map-pin-2-line text-lg"></i><span>State</span>
    </a>
    <a href="/admin/localization/city" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->is('admin/localization/city') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 text-sm font-semibold">
        <i class="ri-building-line text-lg"></i><span>City</span>
    </a>
    <a href="/admin/localization/time-zone" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->is('admin/localization/time-zone') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 text-sm font-semibold">
        <i class="ri-time-line text-lg"></i><span>Time Zone</span>
    </a>
</div>
<a href="/admin/icon-providers/icon" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('admin/icon-providers/*') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
    <i class="ri-icons-line text-lg"></i><span>Icon Providers</span>
</a>
<a href="/admin/noticeboard" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('admin/noticeboard') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
    <i class="ri-megaphone-line text-lg"></i><span>Notice Board</span>
</a>