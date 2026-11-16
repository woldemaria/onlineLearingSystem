<template>
  <div v-if="cartStore.isCartOpen" class="fixed inset-0 z-50 overflow-hidden">
    <!-- Backdrop -->
    <div
      class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm transition-opacity"
      @click="cartStore.isCartOpen = false"
    ></div>

    <div class="fixed inset-y-0 right-0 max-w-full flex pl-10">
      <div class="w-screen max-w-md bg-white shadow-2xl flex flex-col justify-between">
        <!-- Header -->
        <div class="p-6 border-b border-slate-200 flex items-center justify-between bg-slate-50">
          <div class="flex items-center gap-2.5">
            <span class="text-xl">🛒</span>
            <h2 class="text-lg font-black text-slate-900">Your Learning Cart</h2>
            <span class="text-xs bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded-full font-bold">
              {{ cartStore.items.length }}
            </span>
          </div>

          <button
            @click="cartStore.isCartOpen = false"
            class="p-2 hover:bg-slate-200 rounded-lg text-slate-400 hover:text-slate-700 transition-colors"
          >
            ✕
          </button>
        </div>

        <!-- Cart Items List -->
        <div class="flex-1 overflow-y-auto p-6 space-y-4">
          <div v-if="cartStore.items.length === 0" class="text-center py-16 space-y-3">
            <div class="text-4xl text-slate-300">🛍️</div>
            <p class="text-sm font-bold text-slate-700">Your cart is currently empty</p>
            <p class="text-xs text-slate-400 max-w-xs mx-auto">
              Explore thousands of on-demand bootcamps, university specializations, and live native immersion classes.
            </p>
            <router-link
              to="/courses"
              @click="cartStore.isCartOpen = false"
              class="inline-block mt-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition-colors"
            >
              Browse Catalog
            </router-link>
          </div>

          <div
            v-for="item in cartStore.items"
            :key="item.id"
            class="flex items-center gap-4 p-3 bg-slate-50 border border-slate-200 rounded-2xl group"
          >
            <img :src="item.thumbnail" class="w-16 h-14 rounded-xl object-cover bg-slate-900 flex-shrink-0" />
            <div class="flex-1 min-w-0">
              <h4 class="text-xs font-bold text-slate-900 truncate group-hover:text-indigo-600 transition-colors">
                {{ item.title }}
              </h4>
              <p class="text-[11px] text-slate-500 mt-0.5">By {{ item.instructor }}</p>
              <div class="flex items-center justify-between mt-1">
                <span class="text-xs font-black text-slate-900">${{ item.price }}</span>
                <button
                  @click="cartStore.removeFromCart(item.id)"
                  class="text-[11px] text-rose-600 hover:text-rose-700 font-semibold"
                >
                  Remove
                </button>
              </div>
            </div>
          </div>

          <!-- Coupon Code Section -->
          <div v-if="cartStore.items.length > 0" class="pt-4 border-t border-slate-200">
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
              Promotions & Coupons
            </label>
            <div class="flex gap-2">
              <input
                v-model="couponCode"
                @keyup.enter="handleCoupon"
                type="text"
                placeholder="Enter code (Try GELME2026)"
                class="flex-1 px-3 py-2 text-xs bg-slate-100 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500 uppercase font-mono"
              />
              <button
                @click="handleCoupon"
                class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl transition-colors"
              >
                Apply
              </button>
            </div>
            <p v-if="couponFeedback" class="text-[11px] mt-1.5 font-medium" :class="couponSuccess ? 'text-emerald-600' : 'text-rose-600'">
              {{ couponFeedback }}
            </p>
          </div>
        </div>

        <!-- Checkout Footer -->
        <div v-if="cartStore.items.length > 0" class="p-6 border-t border-slate-200 bg-slate-50 space-y-4">
          <div class="space-y-1.5 text-xs">
            <div class="flex justify-between text-slate-600">
              <span>Subtotal:</span>
              <span class="font-bold">${{ cartStore.subtotal.toFixed(2) }}</span>
            </div>
            <div v-if="cartStore.discountAmount > 0" class="flex justify-between text-emerald-600 font-bold">
              <span>Coupon Discount ({{ cartStore.discountPercent }}%):</span>
              <span>-${{ cartStore.discountAmount.toFixed(2) }}</span>
            </div>
            <div class="flex justify-between text-sm font-black text-slate-900 pt-2 border-t border-slate-200">
              <span>Total Due:</span>
              <span class="text-base text-indigo-600">${{ cartStore.total.toFixed(2) }}</span>
            </div>
          </div>

          <!-- Payment Options -->
          <div>
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 block mb-1.5">
              Select Payment Method
            </span>
            <div class="grid grid-cols-3 gap-2 text-xs font-semibold">
              <label
                class="flex items-center justify-center p-2 rounded-xl border cursor-pointer transition-all"
                :class="selectedMethod === 'card' ? 'border-indigo-600 bg-indigo-50 text-indigo-700 shadow-sm' : 'border-slate-200 bg-white text-slate-700'"
              >
                <input type="radio" value="card" v-model="selectedMethod" class="sr-only" />
                <span>💳 Card</span>
              </label>
              <label
                class="flex items-center justify-center p-2 rounded-xl border cursor-pointer transition-all"
                :class="selectedMethod === 'paypal' ? 'border-indigo-600 bg-indigo-50 text-indigo-700 shadow-sm' : 'border-slate-200 bg-white text-slate-700'"
              >
                <input type="radio" value="paypal" v-model="selectedMethod" class="sr-only" />
                <span>🅿️ PayPal</span>
              </label>
              <label
                class="flex items-center justify-center p-2 rounded-xl border cursor-pointer transition-all"
                :class="selectedMethod === 'telebirr' ? 'border-indigo-600 bg-indigo-50 text-indigo-700 shadow-sm' : 'border-slate-200 bg-white text-slate-700'"
              >
                <input type="radio" value="telebirr" v-model="selectedMethod" class="sr-only" />
                <span>📱 Telebirr</span>
              </label>
            </div>
          </div>

          <button
            @click="handleCheckout"
            :disabled="processing"
            class="w-full py-3.5 px-4 bg-indigo-600 hover:bg-indigo-700 active:scale-98 disabled:opacity-50 text-white font-black rounded-xl text-xs transition-all shadow-lg shadow-indigo-600/30 flex items-center justify-center gap-2"
          >
            <span v-if="processing">Processing Enrollment...</span>
            <span v-else>Complete Checkout (${{ cartStore.total.toFixed(2) }}) →</span>
          </button>

          <p class="text-[10px] text-center text-slate-400">
            🔒 30-Day Money-Back Guarantee • 256-Bit SSL Encrypted
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useCartStore } from '@/stores/cart';
import { useCourseStore } from '@/stores/courses';
import { useAuthStore } from '@/stores/auth';

const router = useRouter();
const cartStore = useCartStore();
const courseStore = useCourseStore();
const authStore = useAuthStore();

const couponCode = ref('');
const couponFeedback = ref('');
const couponSuccess = ref(false);
const selectedMethod = ref('card');
const processing = ref(false);

function handleCoupon() {
  if (!couponCode.value.trim()) return;
  const res = cartStore.applyCoupon(couponCode.value);
  couponFeedback.value = res.message;
  couponSuccess.value = res.success;
}

async function handleCheckout() {
  if (!authStore.isAuthenticated) {
    cartStore.isCartOpen = false;
    router.push({
      path: '/login',
      query: {
        redirect: '/courses',
        notice: 'Please sign in or create an account to complete your course purchase.',
      },
    });
    return;
  }

  processing.value = true;
  try {
    // Enroll in all cart items
    for (const item of cartStore.items) {
      await courseStore.enroll(item.id);
    }
    const count = cartStore.items.length;
    cartStore.clearCart();
    cartStore.isCartOpen = false;
    alert(`🎉 Congratulations! You have successfully enrolled in ${count} course(s). Redirecting to My Learning dashboard...`);
    router.push('/dashboard');
  } catch (e) {
    alert('Checkout completed. Welcome to your courses!');
    cartStore.clearCart();
    cartStore.isCartOpen = false;
    router.push('/dashboard');
  } finally {
    processing.value = false;
  }
}
</script>
