<div x-data="searchDropdown()"
     @click.away="open = false"
     @keydown.escape="open = false"
     class="relative">
    <div class="flex items-center">
        <i class="ri-search-line text-heading/40 text-sm absolute left-3"></i>
        <input x-ref="searchInput"
               type="text"
               x-model="query"
               @input="search()"
               @focus="if (results.length) open = true"
               @keydown.down.prevent="selectedIndex = Math.min(selectedIndex + 1, results.length - 1)"
               @keydown.up.prevent="selectedIndex = Math.max(selectedIndex - 1, 0)"
               @keydown.enter.prevent="goToResult()"
               placeholder="Search courses, users, lessons..."
               class="w-48 lg:w-64 pl-8 pr-3 py-2 text-sm rounded-lg border border-heading/10 bg-gray-50 focus:outline-none focus:border-primary focus:bg-white focus:ring-1 focus:ring-primary/20 transition-all">
    </div>

    <div x-show="open && results.length > 0"
         x-cloak
         class="absolute top-full right-0 mt-2 w-80 lg:w-96 max-w-[calc(100vw-2rem)] bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden z-50">
        <template x-for="(result, index) in results" :key="result.id">
            <a :href="result.url"
               class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-colors"
               :class="{ 'bg-primary-5' : index === selectedIndex }"
               @mouseenter="selectedIndex = index">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-bold"
                     :class="{
                         'bg-primary-10 text-primary': result.type === 'Course',
                         'bg-green-10 text-green-600': result.type === 'Student',
                         'bg-amber-10 text-amber-600': result.type === 'Instructor',
                         'bg-blue-10 text-blue-600': result.type === 'Lesson'
                     }">
                    <i class="text-sm"
                       :class="{
                           'ri-book-open-line': result.type === 'Course',
                           'ri-user-line': result.type === 'Student',
                           'ri-user-star-line': result.type === 'Instructor',
                           'ri-file-list-3-line': result.type === 'Lesson'
                       }"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-heading truncate" x-text="result.title"></p>
                    <p class="text-xs text-heading/50 truncate">
                        <span x-text="result.type"></span>
                        <span x-show="result.subtitle"> · <span x-text="result.subtitle"></span></span>
                    </p>
                </div>
            </a>
        </template>
    </div>

    <div x-show="open && loading"
         x-cloak
         class="absolute top-full right-0 mt-2 w-80 lg:w-96 max-w-[calc(100vw-2rem)] bg-white rounded-xl shadow-lg border border-gray-100 p-6 text-center z-50">
        <p class="text-sm text-heading/50">Searching...</p>
    </div>
</div>

@push('scripts')
<script>
    function searchDropdown() {
        return {
            query: '',
            results: [],
            open: false,
            loading: false,
            selectedIndex: 0,
            debounceTimer: null,

            search() {
                if (this.debounceTimer) clearTimeout(this.debounceTimer);
                const q = this.query.trim();

                if (q.length < 2) {
                    this.results = [];
                    this.open = false;
                    return;
                }

                this.loading = true;
                this.debounceTimer = setTimeout(() => {
                    axios.get('/admin/search', { params: { q: q } })
                        .then(response => {
                            this.results = response.data.results || [];
                            this.open = this.results.length > 0;
                            this.selectedIndex = 0;
                        })
                        .catch(() => {
                            this.results = [];
                            this.open = false;
                        })
                        .finally(() => {
                            this.loading = false;
                        });
                }, 300);
            },

            goToResult() {
                if (this.results.length > 0 && this.selectedIndex >= 0) {
                    window.location.href = this.results[this.selectedIndex].url;
                }
            }
        }
    }
</script>
@endpush
