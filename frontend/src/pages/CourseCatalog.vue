<template>
  <div class="min-h-screen bg-slate-50 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
          <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Global Course Catalog</h1>
          <p class="text-xs sm:text-sm text-slate-600 mt-1">
            Discover {{ courseStore.courses.length }} masterclasses, Stanford/MITx degree specializations, and live 25-minute native immersion tracks.
          </p>
        </div>

        <!-- Sorting Dropdown (Udemy Style) -->
        <div class="flex items-center gap-2 text-xs">
          <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">Sort by:</span>
          <select
            v-model="sortBy"
            class="p-2 bg-white border border-slate-200 rounded-xl text-slate-800 font-semibold focus:outline-none focus:ring-1 focus:ring-indigo-500 shadow-sm"
          >
            <option value="popular">Most Popular</option>
            <option value="highest_rated">Highest Rated (★)</option>
            <option value="price_low">Price: Low to High</option>
            <option value="price_high">Price: High to Low</option>
          </select>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar Filters (Udemy / Coursera style) -->
        <div class="space-y-6 bg-white p-6 rounded-2xl border border-slate-200 h-fit shadow-sm">
          <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <h3 class="font-extrabold text-slate-900 text-sm">Filters</h3>
            <button @click="resetFilters" class="text-xs text-indigo-600 hover:text-indigo-700 font-bold">
              Reset All
            </button>
          </div>

          <!-- Search Filter -->
          <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Search Catalog</label>
            <input
              v-model="searchQuery"
              @input="applyFilters"
              type="text"
              placeholder="Search keyword..."
              class="w-full text-xs p-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500"
            />
          </div>

          <!-- Category Filter -->
          <div class="pt-4 border-t border-slate-100">
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Category</label>
            <div class="space-y-2">
              <label class="flex items-center gap-2 text-xs text-slate-700 cursor-pointer">
                <input
                  type="radio"
                  v-model="selectedCategory"
                  value=""
                  @change="applyFilters"
                  class="text-indigo-600 focus:ring-indigo-500"
                />
                <span>All Categories</span>
              </label>
              <label
                v-for="cat in courseStore.categories"
                :key="cat.id"
                class="flex items-center justify-between text-xs text-slate-700 cursor-pointer"
              >
                <div class="flex items-center gap-2">
                  <input
                    type="radio"
                    v-model="selectedCategory"
                    :value="cat.slug"
                    @change="applyFilters"
                    class="text-indigo-600 focus:ring-indigo-500"
                  />
                  <span>{{ cat.name }}</span>
                </div>
                <span class="text-slate-400 font-mono text-[10px]">({{ cat.courses_count }})</span>
              </label>
            </div>
          </div>

          <!-- Level Filter -->
          <div class="pt-4 border-t border-slate-100">
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Skill Level</label>
            <select
              v-model="selectedLevel"
              @change="applyFilters"
              class="w-full text-xs p-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none"
            >
              <option value="all">All Levels</option>
              <option value="beginner">Beginner</option>
              <option value="intermediate">Intermediate</option>
              <option value="advanced">Advanced</option>
              <option value="all_levels">All Levels Welcome</option>
            </select>
          </div>

          <!-- Format / Type (Udemy vs Coursera vs OGC) -->
          <div class="pt-4 border-t border-slate-100">
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Delivery Format</label>
            <select
              v-model="selectedType"
              @change="applyFilters"
              class="w-full text-xs p-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none"
            >
              <option value="all">All Formats</option>
              <option value="self_paced">On-Demand Self Paced (Udemy)</option>
              <option value="cohort_based">Cohort / Degree Specialization (Coursera/edX)</option>
              <option value="live_virtual">Live 25-Min Immersion (Online Global Classroom)</option>
            </select>
          </div>
        </div>

        <!-- Course Cards Grid -->
        <div class="lg:col-span-3">
          <div v-if="courseStore.loading" class="text-center py-20 text-slate-400 text-sm">
            Loading courses...
          </div>

          <div v-else-if="sortedCourses.length === 0" class="text-center py-20 bg-white rounded-2xl border border-slate-200">
            <p class="text-slate-500 font-medium">No courses found matching your selected criteria.</p>
            <button @click="resetFilters" class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded-xl text-xs font-bold">
              Clear All Filters
            </button>
          </div>

          <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div
              v-for="course in sortedCourses"
              :key="course.id"
              class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between group"
            >
              <div>
                <!-- Thumbnail & Badges -->
                <div class="relative aspect-video bg-slate-900 overflow-hidden">
                  <img
                    :src="course.thumbnail_url"
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                  />
                  <span class="absolute top-3 left-3 bg-slate-900/80 backdrop-blur-md text-white text-[10px] font-bold px-2 py-0.5 rounded-full uppercase">
                    {{ course.level.replace('_', ' ') }}
                  </span>
                  <span
                    v-if="course.type === 'live_virtual'"
                    class="absolute top-3 right-3 bg-emerald-600 text-white text-[10px] font-bold px-2.5 py-0.5 rounded-full uppercase flex items-center gap-1 shadow-md"
                  >
                    <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                    Live 25-Min OGC
                  </span>
                  <span
                    v-else-if="course.type === 'cohort_based'"
                    class="absolute top-3 right-3 bg-blue-600 text-white text-[10px] font-bold px-2.5 py-0.5 rounded-full uppercase shadow-md"
                  >
                    University Specialization
                  </span>
                </div>

                <div class="p-5">
                  <div class="flex items-center justify-between text-xs text-indigo-600 font-semibold mb-1.5">
                    <span>{{ course.category }}</span>
                    <span class="text-slate-400 font-normal truncate max-w-[150px]">{{ course.institution }}</span>
                  </div>

                  <h3 class="font-extrabold text-slate-900 group-hover:text-indigo-600 transition-colors line-clamp-2 text-base leading-snug mb-2">
                    <router-link :to="`/courses/${course.slug}`">
                      {{ course.title }}
                    </router-link>
                  </h3>

                  <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed mb-4">
                    {{ course.subtitle || course.description }}
                  </p>

                  <div class="flex items-center justify-between text-xs text-slate-500 pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-1.5 min-w-0 pr-2">
                      <img :src="course.instructor_avatar" class="w-5 h-5 rounded-full" />
                      <span class="truncate font-medium text-slate-700">{{ course.instructor_name }}</span>
                    </div>
                    <span class="text-amber-500 font-bold flex-shrink-0">★ {{ course.rating }} ({{ course.reviews_count }})</span>
                  </div>
                </div>
              </div>

              <!-- Price & Action Buttons -->
              <div class="p-5 pt-0 flex items-center justify-between">
                <div>
                  <span class="text-lg font-black text-slate-900">${{ course.price }}</span>
                  <span class="text-xs text-slate-400 ml-1.5 line-through">${{ (course.price * 2.8).toFixed(2) }}</span>
                </div>

                <div class="flex items-center gap-2">
                  <button
                    @click="addToCart(course)"
                    class="p-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-bold transition-colors"
                    title="Add to Cart"
                  >
                    🛒
                  </button>
                  <router-link
                    :to="`/courses/${course.slug}`"
                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg text-xs transition-colors shadow-sm"
                  >
                    View Details
                  </router-link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { useCourseStore } from '@/stores/courses';
import { useCartStore } from '@/stores/cart';

const route = useRoute();
const courseStore = useCourseStore();
const cartStore = useCartStore();

const searchQuery = ref(route.query.search || '');
const selectedCategory = ref('');
const selectedLevel = ref('all');
const selectedType = ref('all');
const sortBy = ref('popular');

watch(
  () => route.query.search,
  (newSearch) => {
    searchQuery.value = newSearch || '';
    applyFilters();
  }
);

const sortedCourses = computed(() => {
  let list = [...courseStore.courses];
  if (sortBy.value === 'popular') {
    list.sort((a, b) => b.enrollments_count - a.enrollments_count);
  } else if (sortBy.value === 'highest_rated') {
    list.sort((a, b) => b.rating - a.rating);
  } else if (sortBy.value === 'price_low') {
    list.sort((a, b) => a.price - b.price);
  } else if (sortBy.value === 'price_high') {
    list.sort((a, b) => b.price - a.price);
  }
  return list;
});

function applyFilters() {
  courseStore.fetchCourses({
    search: searchQuery.value,
    category: selectedCategory.value,
    level: selectedLevel.value,
    type: selectedType.value,
  });
}

function resetFilters() {
  searchQuery.value = '';
  selectedCategory.value = '';
  selectedLevel.value = 'all';
  selectedType.value = 'all';
  applyFilters();
}

function addToCart(course) {
  cartStore.addToCart(course);
}

onMounted(() => {
  courseStore.fetchCategories();
  applyFilters();
});
</script>
