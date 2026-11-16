import { defineStore } from 'pinia';
import { ref, computed } from 'vue';

export const useCartStore = defineStore('cart', () => {
  const items = ref(JSON.parse(localStorage.getItem('gelme_cart') || '[]'));
  const coupon = ref('');
  const discountPercent = ref(0);
  const isCartOpen = ref(false);

  const subtotal = computed(() => {
    return items.value.reduce((acc, item) => acc + (item.price || 0), 0);
  });

  const discountAmount = computed(() => {
    return (subtotal.value * (discountPercent.value / 100));
  });

  const total = computed(() => {
    return Math.max(0, subtotal.value - discountAmount.value);
  });

  function saveCart() {
    localStorage.setItem('gelme_cart', JSON.stringify(items.value));
  }

  function addToCart(course) {
    if (!items.value.some(i => i.id === course.id)) {
      items.value.push({
        id: course.id,
        title: course.title,
        slug: course.slug,
        price: course.price,
        instructor: course.instructor_name || course.instructor?.name || 'Lead Instructor',
        thumbnail: course.thumbnail_url || 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=400&auto=format&fit=crop&q=80',
      });
      saveCart();
    }
    isCartOpen.value = true;
  }

  function removeFromCart(courseId) {
    items.value = items.value.filter(i => i.id !== courseId);
    saveCart();
  }

  function clearCart() {
    items.value = [];
    coupon.value = '';
    discountPercent.value = 0;
    saveCart();
  }

  function applyCoupon(code) {
    const clean = code.trim().toUpperCase();
    if (clean === 'GELME2026' || clean === 'UDEMYDEAL' || clean === 'COURSERA50') {
      coupon.value = clean;
      discountPercent.value = 30; // 30% additional discount
      return { success: true, message: `Coupon ${clean} applied! 30% extra discount.` };
    }
    return { success: false, message: 'Invalid or expired coupon code. Try GELME2026.' };
  }

  return {
    items,
    coupon,
    discountPercent,
    isCartOpen,
    subtotal,
    discountAmount,
    total,
    addToCart,
    removeFromCart,
    clearCart,
    applyCoupon,
  };
});
