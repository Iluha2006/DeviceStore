<template>
  <header class="dm-header">
    <div class="dm-header-inner">
      <button
        class="dm-icon-btn dm-header-burger"
        @click="$emit('toggleMenu')"
        aria-label="Открыть меню"
      >
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5"/>
        </svg>
      </button>

      <router-link to="/" class="dm-logo">
        <span class="dm-logo-mark">
          <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16">
            <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
          </svg>
        </span>
        <span>
          <span class="dm-logo-name">DeviceStore</span>
          
        </span>
      </router-link>

      <div class="dm-header-search">
        <SearchInput />
      </div>

      <nav class="dm-header-actions">
        <router-link
          to="/favorites"
          class="dm-icon-btn"
          title="Избранное"
        >
          <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16">
            <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15"/>
          </svg>
          <span v-if="favoritesCount > 0" class="dm-icon-badge">{{ favoritesCount }}</span>
        </router-link>

        <router-link
          to="/cartproduct"
          class="dm-icon-btn"
          title="Корзина"
        >
          <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16">
            <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
          </svg>
          <span v-if="cartCount > 0" class="dm-icon-badge">{{ cartCount }}</span>
        </router-link>

        <router-link
          v-if="userStore.user"
          :to="'/profile/' + userStore.user.id"
          class="dm-icon-btn"
          title="Профиль"
        >
          <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16">
            <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/>
          </svg>
        </router-link>

        <button
          v-else
          class="dm-icon-btn"
          @click="$emit('toggle-profile-modal')"
          title="Войти"
        >
          <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16">
            <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/>
          </svg>
        </button>

        <router-link
          v-if="userStore.user && userStore.user.id === 1"
          to="/admin/create-product"
          class="dm-btn dm-btn-primary dm-btn-sm dm-header-admin"
        >
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
          </svg>
          Создать продукт
        </router-link>
      </nav>
    </div>
  </header>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';
import SearchInput from './SearchInput.vue';
import { useUserStore } from '../Store/user';

const userStore = useUserStore();
const route = useRoute();
const cartCount = ref(0);
const favoritesCount = ref(0);
const emit = defineEmits(['toggleMenu', 'toggle-profile-modal']);

const fetchCounts = async () => {
  if (!userStore.isAuthenticated || !userStore.token) {
    cartCount.value = 0;
    favoritesCount.value = 0;
    return;
  }

  try {
    const [cartRes, favRes] = await Promise.all([
      axios.get('/api/cart', {
        withCredentials: true,
        headers: { 'Accept': 'application/json', 'Authorization': `Bearer ${userStore.token}` }
      }),
      axios.get('/api/favorites', {
        withCredentials: true,
        headers: { 'Accept': 'application/json', 'Authorization': `Bearer ${userStore.token}` }
      })
    ]);

    cartCount.value = Array.isArray(cartRes.data)
      ? cartRes.data.reduce((sum, item) => sum + (item?.quantity || 0), 0)
      : 0;
    favoritesCount.value = Array.isArray(favRes.data) ? favRes.data.length : 0;
  } catch (error) {
    cartCount.value = 0;
    favoritesCount.value = 0;
  }
};

watch(() => route.fullPath, fetchCounts);

onMounted(() => {
  fetchCounts();
});
</script>

<style scoped>
.dm-header {
  position: sticky;
  top: 0;
  z-index: var(--dm-z-header);
  background: var(--dm-surface);
  border-bottom: 1px solid var(--dm-border);
}

.dm-header-inner {
  max-width: var(--dm-content-width);
  height: var(--dm-header-height);
  margin: 0 auto;
  padding: 0 var(--dm-space-4);
  display: flex;
  align-items: center;
  gap: var(--dm-space-5);
}

.dm-header-burger {
  display: none;
}

.dm-header-search {
  flex: 1;
  max-width: 520px;
  margin-left: var(--dm-space-2);
}

.dm-header-actions {
  margin-left: auto;
  display: flex;
  align-items: center;
  gap: var(--dm-space-1);
}

@media (max-width: 860px) {
  .dm-header-inner {
    gap: var(--dm-space-3);
  }

  .dm-header-burger {
    display: inline-flex;
  }

  .dm-header-search {
    order: 3;
    max-width: none;
    flex-basis: 100%;
    margin-left: 0;
  }

  .dm-header-inner {
    flex-wrap: wrap;
    height: auto;
    padding: var(--dm-space-3) var(--dm-space-4);
    gap: var(--dm-space-3);
  }

  .dm-header-admin {
    display: none;
  }

  .dm-logo-tagline {
    display: none;
  }
}
</style>
