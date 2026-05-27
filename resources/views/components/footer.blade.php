<footer class="bg-[#111827] text-white pt-16 pb-8">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 pb-12 border-b border-white/10">
            <div>
                <a href="/" class="flex items-center gap-2 mb-5">
                    <div class="w-10 h-10 rounded-lg bg-primary flex items-center justify-center">
                        <i class="ri-graduation-cap-fill text-white text-xl"></i>
                    </div>
                    <span class="text-2xl font-extrabold text-white">Edulab</span>
                </a>
                <p class="text-white/70 text-sm leading-relaxed mb-6">
                    Designing a user-friendly interface is essential for improving user engagement. Focus on simplicity.
                </p>
                <div class="flex items-center gap-3">
                    <a href="https://www.linkedin.com/" target="_blank" class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center hover:bg-primary transition-colors duration-300">
                        <i class="ri-linkedin-fill text-white text-sm"></i>
                    </a>
                    <a href="https://x.com/?lang=en" target="_blank" class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center hover:bg-primary transition-colors duration-300">
                        <i class="ri-twitter-x-fill text-white text-sm"></i>
                    </a>
                    <a href="https://www.facebook.com/" target="_blank" class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center hover:bg-primary transition-colors duration-300">
                        <i class="ri-facebook-fill text-white text-sm"></i>
                    </a>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-bold mb-5">Support</h3>
                <ul class="space-y-3">
                    <li><a href="/contact" class="text-white/70 text-sm hover:text-secondary transition-colors duration-300">Forum Support</a></li>
                    <li><a href="/contact" class="text-white/70 text-sm hover:text-secondary transition-colors duration-300">Help & FAQ</a></li>
                    <li><a href="/bundles" class="text-white/70 text-sm hover:text-secondary transition-colors duration-300">Course Bundles</a></li>
                    <li><a href="/contact" class="text-white/70 text-sm hover:text-secondary transition-colors duration-300">Contact Us</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-lg font-bold mb-5">Company</h3>
                <ul class="space-y-3">
                    <li><a href="/about-us" class="text-white/70 text-sm hover:text-secondary transition-colors duration-300">About Us</a></li>
                    <li><a href="/courses" class="text-white/70 text-sm hover:text-secondary transition-colors duration-300">Courses</a></li>
                    <li><a href="/courses" class="text-white/70 text-sm hover:text-secondary transition-colors duration-300">Help Center</a></li>
                    <li><a href="/blogs" class="text-white/70 text-sm hover:text-secondary transition-colors duration-300">News</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-lg font-bold mb-5">Subscribe to Newsletter</h3>
                <p class="text-white/70 text-sm mb-4">Stay updated with our latest news and offers.</p>
                <form method="POST" action="/newsletter" class="flex">
                    @csrf
                    <input name="email" type="email" placeholder="Your email" class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-l-lg text-white text-sm placeholder:text-white/50 focus:outline-none focus:border-primary">
                    <button type="submit" class="px-5 py-3 bg-secondary text-heading font-semibold rounded-r-lg hover:opacity-90 transition-all duration-300">
                        <i class="ri-send-plane-fill"></i>
                    </button>
                </form>
            </div>
        </div>

        <div class="flex flex-col md:flex-row items-center justify-between gap-4 pt-8">
            <p class="text-white/60 text-sm">
                2024. All rights reserved by <a href="https://codexshaper.com/" target="_blank" class="text-secondary hover:underline">CodexShaper</a>
            </p>
            <div class="flex items-center gap-4 text-white/60 text-sm">
                <a href="/terms-conditions" class="hover:text-secondary transition-colors duration-300">Terms of Conditions</a>
                <span>|</span>
                <a href="/privacy-policy" class="hover:text-secondary transition-colors duration-300">Privacy Policy</a>
            </div>
        </div>
    </div>
</footer>