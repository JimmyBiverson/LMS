<a href="/admin"
   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('admin') && request()->path() == 'admin' ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
    <i class="ri-dashboard-line text-lg"></i><span>Dashboard</span>
</a>

<a href="/admin/zoom"
   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('admin/zoom*') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300">
    <i class="ri-video-on-line text-lg"></i><span>Zoom Classroom</span>
</a>

<div x-data="{ open: localStorage.getItem('sidebarGroup_academics') === 'true' }" :class="open ? 'mb-2' : ''">
    <button @click="open = !open; localStorage.setItem('sidebarGroup_academics', open)"
            class="flex items-center justify-between w-full px-3 py-2 mt-2 text-xs font-bold text-heading/40 uppercase tracking-wider hover:text-heading/70 transition-colors">
        <span>Academics</span>
        <i :class="open ? 'ri-arrow-up-s-line' : 'ri-arrow-down-s-line'" class="text-sm"></i>
    </button>
    <div x-show="open" x-cloak x-transition:enter="transition-all duration-300 ease-out" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-0.5 mt-1">
        <a href="/admin/school/classes" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->is('admin/school/classes') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 font-semibold"><i class="ri-group-2-line text-lg"></i><span>Classes</span></a>
        <a href="/admin/school/attendances" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->is('admin/school/attendances') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 font-semibold"><i class="ri-calendar-check-line text-lg"></i><span>Attendance</span></a>
        <a href="/admin/school/exams" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->is('admin/school/exams') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 font-semibold"><i class="ri-file-list-3-line text-lg"></i><span>Exams</span></a>
        <a href="/admin/school/results" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->is('admin/school/results') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 font-semibold"><i class="ri-bar-chart-box-line text-lg"></i><span>Results</span></a>
        <a href="/admin/school/timetables" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->is('admin/school/timetables') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 font-semibold"><i class="ri-calendar-todo-line text-lg"></i><span>Timetables</span></a>
    </div>
</div>

<div x-data="{ open: localStorage.getItem('sidebarGroup_content') === 'true' }" :class="open ? 'mb-2' : ''">
    <button @click="open = !open; localStorage.setItem('sidebarGroup_content', open)"
            class="flex items-center justify-between w-full px-3 py-2 mt-2 text-xs font-bold text-heading/40 uppercase tracking-wider hover:text-heading/70 transition-colors">
        <span>Content</span>
        <i :class="open ? 'ri-arrow-up-s-line' : 'ri-arrow-down-s-line'" class="text-sm"></i>
    </button>
    <div x-show="open" x-cloak x-transition:enter="transition-all duration-300 ease-out" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-0.5 mt-1">
        <a href="/admin/course" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->is('admin/course') && !request()->is('admin/course/bundle') && !request()->is('admin/course/level') && !request()->is('admin/course/tag') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 font-semibold"><i class="ri-book-open-line text-lg"></i><span>All Courses</span></a>
        <a href="/admin/course/bundle" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->is('admin/course/bundle') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 font-semibold"><i class="ri-price-tag-3-line text-lg"></i><span>Course Bundle</span></a>
        <a href="/admin/course/level" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->is('admin/course/level') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 font-semibold"><i class="ri-bar-chart-line text-lg"></i><span>Course Level</span></a>
        <a href="/admin/course/tag" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->is('admin/course/tag') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 font-semibold"><i class="ri-price-tag-3-line text-lg"></i><span>Course Tag</span></a>
        <a href="/admin/category" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->is('admin/category') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 font-semibold"><i class="ri-bookmark-line text-lg"></i><span>Category</span></a>
        <a href="/admin/subject" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->is('admin/subject') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 font-semibold"><i class="ri-book-mark-line text-lg"></i><span>Subject</span></a>
    </div>
</div>

<div x-data="{ open: localStorage.getItem('sidebarGroup_people') === 'true' }" :class="open ? 'mb-2' : ''">
    <button @click="open = !open; localStorage.setItem('sidebarGroup_people', open)"
            class="flex items-center justify-between w-full px-3 py-2 mt-2 text-xs font-bold text-heading/40 uppercase tracking-wider hover:text-heading/70 transition-colors">
        <span>People</span>
        <i :class="open ? 'ri-arrow-up-s-line' : 'ri-arrow-down-s-line'" class="text-sm"></i>
    </button>
    <div x-show="open" x-cloak x-transition:enter="transition-all duration-300 ease-out" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-0.5 mt-1">
        <a href="/admin/instructors" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->is('admin/instructors') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 font-semibold"><i class="ri-user-star-line text-lg"></i><span>Instructors</span></a>
        <a href="/admin/students" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->is('admin/students') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 font-semibold"><i class="ri-group-line text-lg"></i><span>Students</span></a>
        <a href="/admin/organizations" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->is('admin/organizations') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 font-semibold"><i class="ri-building-line text-lg"></i><span>Organization</span></a>
        <a href="/admin/school/parents" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->is('admin/school/parents') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 font-semibold"><i class="ri-user-heart-line text-lg"></i><span>Parents</span></a>
        <a href="/admin/staff" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->is('admin/staff') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 font-semibold"><i class="ri-user-settings-line text-lg"></i><span>Staff Manage</span></a>
    </div>
</div>

<div x-data="{ open: localStorage.getItem('sidebarGroup_blog') === 'true' }" :class="open ? 'mb-2' : ''">
    <button @click="open = !open; localStorage.setItem('sidebarGroup_blog', open)"
            class="flex items-center justify-between w-full px-3 py-2 mt-2 text-xs font-bold text-heading/40 uppercase tracking-wider hover:text-heading/70 transition-colors">
        <span>Blog</span>
        <i :class="open ? 'ri-arrow-up-s-line' : 'ri-arrow-down-s-line'" class="text-sm"></i>
    </button>
    <div x-show="open" x-cloak x-transition:enter="transition-all duration-300 ease-out" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-0.5 mt-1">
        <a href="/admin/blog" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->is('admin/blog') && !request()->is('admin/blog/category') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 font-semibold"><i class="ri-file-text-line text-lg"></i><span>All Blogs</span></a>
        <a href="/admin/blog/category" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->is('admin/blog/category') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 font-semibold"><i class="ri-bookmark-3-line text-lg"></i><span>Blog Category</span></a>
    </div>
</div>

<div x-data="{ open: localStorage.getItem('sidebarGroup_appearance') === 'true' }" :class="open ? 'mb-2' : ''">
    <button @click="open = !open; localStorage.setItem('sidebarGroup_appearance', open)"
            class="flex items-center justify-between w-full px-3 py-2 mt-2 text-xs font-bold text-heading/40 uppercase tracking-wider hover:text-heading/70 transition-colors">
        <span>Appearance</span>
        <i :class="open ? 'ri-arrow-up-s-line' : 'ri-arrow-down-s-line'" class="text-sm"></i>
    </button>
    <div x-show="open" x-cloak x-transition:enter="transition-all duration-300 ease-out" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-0.5 mt-1">
        <a href="/admin/slider" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->is('admin/slider') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 font-semibold"><i class="ri-slideshow-line text-lg"></i><span>Slider</span></a>
        <a href="/admin/hero" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->is('admin/hero') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 font-semibold"><i class="ri-image-line text-lg"></i><span>Hero</span></a>
        <a href="/admin/testimonial" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->is('admin/testimonial') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 font-semibold"><i class="ri-quote-text text-lg"></i><span>Testimonial</span></a>
        <a href="/admin/page" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->is('admin/page') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 font-semibold"><i class="ri-file-copy-line text-lg"></i><span>Pages</span></a>
        <a href="/admin/theme" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->is('admin/theme') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 font-semibold"><i class="ri-paint-brush-line text-lg"></i><span>Theme</span></a>
    </div>
</div>

<div x-data="{ open: localStorage.getItem('sidebarGroup_finance') === 'true' }" :class="open ? 'mb-2' : ''">
    <button @click="open = !open; localStorage.setItem('sidebarGroup_finance', open)"
            class="flex items-center justify-between w-full px-3 py-2 mt-2 text-xs font-bold text-heading/40 uppercase tracking-wider hover:text-heading/70 transition-colors">
        <span>Finance</span>
        <i :class="open ? 'ri-arrow-up-s-line' : 'ri-arrow-down-s-line'" class="text-sm"></i>
    </button>
    <div x-show="open" x-cloak x-transition:enter="transition-all duration-300 ease-out" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-0.5 mt-1">
        <a href="/admin/financial/sale" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->is('admin/financial/sale') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 font-semibold"><i class="ri-funds-line text-lg"></i><span>Sale History</span></a>
        <a href="/admin/financial/offline" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->is('admin/financial/offline') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 font-semibold"><i class="ri-bank-line text-lg"></i><span>Offline Payment</span></a>
        <a href="/admin/financial/payouts" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->is('admin/financial/payouts') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 font-semibold"><i class="ri-hand-coin-line text-lg"></i><span>Payouts</span></a>
        <a href="/admin/payment-method" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->is('admin/payment-method') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 font-semibold"><i class="ri-bank-card-line text-lg"></i><span>Payment Methods</span></a>
        <a href="/admin/lms-module/subscription" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->is('admin/lms-module/*') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 font-semibold"><i class="ri-refund-line text-lg"></i><span>Subscriptions</span></a>
        <a href="/admin/marketing/coupon" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->is('admin/marketing/coupon') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 font-semibold"><i class="ri-coupon-line text-lg"></i><span>Coupons</span></a>
    </div>
</div>

<div x-data="{ open: localStorage.getItem('sidebarGroup_support') === 'true' }" :class="open ? 'mb-2' : ''">
    <button @click="open = !open; localStorage.setItem('sidebarGroup_support', open)"
            class="flex items-center justify-between w-full px-3 py-2 mt-2 text-xs font-bold text-heading/40 uppercase tracking-wider hover:text-heading/70 transition-colors">
        <span>Support</span>
        <i :class="open ? 'ri-arrow-up-s-line' : 'ri-arrow-down-s-line'" class="text-sm"></i>
    </button>
    <div x-show="open" x-cloak x-transition:enter="transition-all duration-300 ease-out" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-0.5 mt-1">
        <a href="/admin/support-ticket/category" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->is('admin/support-ticket/category') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 font-semibold"><i class="ri-bookmark-line text-lg"></i><span>Support Category</span></a>
        <a href="/admin/support-ticket/ticket" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->is('admin/support-ticket/ticket') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 font-semibold"><i class="ri-question-answer-line text-lg"></i><span>Tickets</span>@php $openTickets = \App\Models\SupportTicket::whereIn('status', ['open', 'pending'])->count(); @endphp @if($openTickets > 0)<span class="ml-auto w-5 h-5 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center">{{ min($openTickets, 99) }}</span>@endif</a>
        <a href="/admin/noticeboard" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->is('admin/noticeboard') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 font-semibold"><i class="ri-megaphone-line text-lg"></i><span>Notice Board</span></a>
        <a href="/admin/contact" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->is('admin/contact') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 font-semibold"><i class="ri-mail-open-line text-lg"></i><span>Contacts</span>@php $unreadContact = \App\Models\ContactMessage::where('is_read', false)->count(); @endphp @if($unreadContact > 0)<span class="ml-auto w-5 h-5 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center">{{ min($unreadContact, 99) }}</span>@endif</a>
        <a href="/admin/faq" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->is('admin/faq') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 font-semibold"><i class="ri-question-line text-lg"></i><span>FAQ</span></a>
    </div>
</div>

<div x-data="{ open: localStorage.getItem('sidebarGroup_settings') === 'true' }" :class="open ? 'mb-2' : ''">
    <button @click="open = !open; localStorage.setItem('sidebarGroup_settings', open)"
            class="flex items-center justify-between w-full px-3 py-2 mt-2 text-xs font-bold text-heading/40 uppercase tracking-wider hover:text-heading/70 transition-colors">
        <span>Settings</span>
        <i :class="open ? 'ri-arrow-up-s-line' : 'ri-arrow-down-s-line'" class="text-sm"></i>
    </button>
    <div x-show="open" x-cloak x-transition:enter="transition-all duration-300 ease-out" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-0.5 mt-1">
        <a href="/admin/settings?tab=school" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->is('admin/settings') && request()->fullUrlIs('*tab=school*') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 font-semibold"><i class="ri-settings-3-line text-lg"></i><span>School Settings</span></a>
        <a href="/admin/settings?tab=theme" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->is('admin/settings') && request()->fullUrlIs('*tab=theme*') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 font-semibold"><i class="ri-palette-line text-lg"></i><span>Frontend Settings</span></a>
        <a href="/admin/settings?tab=backend" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->is('admin/settings') && request()->fullUrlIs('*tab=backend*') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 font-semibold"><i class="ri-settings-3-line text-lg"></i><span>Backend Settings</span></a>
        <a href="/admin/settings?tab=instructors" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->is('admin/settings') && request()->fullUrlIs('*tab=instructors*') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 font-semibold"><i class="ri-user-star-line text-lg"></i><span>Approve Instructors</span></a>
        <a href="/admin/profile" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->is('admin/profile') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 font-semibold"><i class="ri-user-smile-line text-lg"></i><span>Profile</span></a>
    </div>
</div>

<div x-data="{ open: localStorage.getItem('sidebarGroup_system') === 'true' }" :class="open ? 'mb-2' : ''">
    <button @click="open = !open; localStorage.setItem('sidebarGroup_system', open)"
            class="flex items-center justify-between w-full px-3 py-2 mt-2 text-xs font-bold text-heading/40 uppercase tracking-wider hover:text-heading/70 transition-colors">
        <span>System</span>
        <i :class="open ? 'ri-arrow-up-s-line' : 'ri-arrow-down-s-line'" class="text-sm"></i>
    </button>
    <div x-show="open" x-cloak x-transition:enter="transition-all duration-300 ease-out" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-0.5 mt-1">
        <a href="/admin/site-language" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->is('admin/site-language') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 font-semibold"><i class="ri-global-line text-lg"></i><span>Site Language</span></a>
        <a href="/admin/language" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->is('admin/language') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 font-semibold"><i class="ri-translate-line text-lg"></i><span>Language</span></a>
        <a href="/admin/currency" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->is('admin/currency') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 font-semibold"><i class="ri-money-dollar-circle-line text-lg"></i><span>Currency</span></a>
        <a href="/admin/email-template" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->is('admin/email-template') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 font-semibold"><i class="ri-mail-send-line text-lg"></i><span>Email Template</span></a>
        <a href="/admin/certificate" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->is('admin/certificate') || request()->is('admin/certificate/*') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 font-semibold"><i class="ri-award-line text-lg"></i><span>Certificate</span></a>
        <a href="/admin/review/course-review" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->is('admin/review/*') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 font-semibold"><i class="ri-star-line text-lg"></i><span>Review</span>@php $pendingReviews = \App\Models\Review::where('is_approved', false)->count(); @endphp @if($pendingReviews > 0)<span class="ml-auto w-5 h-5 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center">{{ min($pendingReviews, 99) }}</span>@endif</a>
        <a href="/admin/notification" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->is('admin/notification') && !request()->is('admin/notification/history') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 font-semibold"><i class="ri-mail-send-line text-lg"></i><span>Notification Templates</span></a>
        <a href="/admin/notification/history" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->is('admin/notification/history') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 font-semibold"><i class="ri-history-line text-lg"></i><span>Notification History</span></a>
        <a href="/admin/notifications" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->is('admin/notifications') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 font-semibold"><i class="ri-notification-3-line text-lg"></i><span>My Notifications</span>@php $unread = \App\Models\NotificationLog::where('user_id', auth()->id())->where('is_read', false)->count(); @endphp @if($unread > 0)<span class="ml-auto w-5 h-5 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center">{{ min($unread, 99) }}</span>@endif</a>
        <a href="/notifications/send" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->is('notifications/send') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 font-semibold"><i class="ri-send-plane-line text-lg"></i><span>Send Notification</span></a>
        <a href="/admin/meet-provider" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->is('admin/meet-provider') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 font-semibold"><i class="ri-video-on-line text-lg"></i><span>Meet Provider</span></a>
        <a href="/admin/icon-providers/icon" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->is('admin/icon-providers/*') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 font-semibold"><i class="ri-icons-line text-lg"></i><span>Icon Providers</span></a>
        <a href="/admin/wishlists" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->is('admin/wishlists') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 font-semibold"><i class="ri-heart-3-line text-lg"></i><span>Wishlists</span></a>
        <a href="/admin/localization/country" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->is('admin/localization/country') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 font-semibold"><i class="ri-map-pin-line text-lg"></i><span>Country</span></a>
        <a href="/admin/localization/state" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->is('admin/localization/state') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 font-semibold"><i class="ri-map-pin-2-line text-lg"></i><span>State</span></a>
        <a href="/admin/localization/city" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->is('admin/localization/city') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 font-semibold"><i class="ri-building-line text-lg"></i><span>City</span></a>
        <a href="/admin/localization/time-zone" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->is('admin/localization/time-zone') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 font-semibold"><i class="ri-time-line text-lg"></i><span>Time Zone</span></a>
        <a href="/admin/enrollment/all" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->is('admin/enrollment/all') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 font-semibold"><i class="ri-file-list-3-line text-lg"></i><span>Enrollments</span></a>
        <a href="/admin/enrollment/new-create" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->is('admin/enrollment/new-create') ? 'bg-primary-50 text-primary' : 'text-heading/70 hover:bg-primary-50 hover:text-primary' }} transition-all duration-300 font-semibold"><i class="ri-add-circle-line text-lg"></i><span>New Enrollment</span></a>
    </div>
</div>

