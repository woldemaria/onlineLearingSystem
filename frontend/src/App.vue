<template>
  <div class="min-h-screen flex flex-col font-sans bg-slate-50 text-slate-900">
    <Navbar v-if="!isPlayerOrLiveRoom" />
    <main class="flex-1">
      <router-view />
    </main>
    <Footer v-if="!isPlayerOrLiveRoom" />
    <AiTutorWidget v-if="!isLiveRoom" />
    <CartDrawer />
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { useRoute } from 'vue-router';
import Navbar from '@/components/layout/Navbar.vue';
import Footer from '@/components/layout/Footer.vue';
import AiTutorWidget from '@/components/ai/AiTutorWidget.vue';
import CartDrawer from '@/components/cart/CartDrawer.vue';

const route = useRoute();

const isAuthPage = computed(() => {
  return route.name === 'Login' || route.name === 'Register';
});

const isPlayerOrLiveRoom = computed(() => {
  return route.name === 'CoursePlayer' || route.name === 'LiveVirtualClassroom' || isAuthPage.value;
});

const isLiveRoom = computed(() => {
  return route.name === 'LiveVirtualClassroom' || isAuthPage.value;
});
</script>
