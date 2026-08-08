<template>
  <div class="dm-page product-page">
    <transition name="dm-fade">
      <div v-if="ModalMessage" class="dm-toast">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14m.75-10.25v5.5a.75.75 0 0 1-1.5 0v-5.5a.75.75 0 0 1 1.5 0M8 11.5a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5"/>
        </svg>
        {{ message }}
      </div>
    </transition>

    <nav class="breadcrumbs">
      <router-link to="/">Главная</router-link>
      <span>/</span>
      <span class="breadcrumbs-current">{{ product.name || 'Товар' }}</span>
    </nav>

    <div v-if="!product.id" class="dm-loading">
      <span class="dm-spinner"></span>
      Загрузка товара...
    </div>

    <div v-else class="product-detail">
      <div class="dm-card product-image-card">
        <div class="product-image-wrapper">
          <img :src="product.imageUrl" :alt="product.name" class="dm-img-contain">
        </div>
      </div>

      <div class="dm-card product-info">
        <span class="dm-badge dm-badge-primary" v-if="product.discount && product.discount !== '0%'">
          Скидка −{{ product.discount }}
        </span>

        <h1 class="product-name">{{ product.name }}</h1>

        <div class="product-rating" v-if="product.reviews_count">
          <div class="stars">
            <span v-for="i in 5" :key="i" :class="{ 'filled': i <= Math.round(product.reviews_avg_rating || 0) }">★</span>
          </div>
          <span class="rating-value">{{ Number(product.reviews_avg_rating || 0).toFixed(1) }}</span>
          <button class="rating-link" @click="showReviewsModal = true">
            {{ product.reviews_count }} {{ plural(product.reviews_count) }}
          </button>
        </div>

        <div class="price-section">
          <span class="price">{{ formatPrice(product.price) }}</span>
          <span v-if="product.discount && product.discount !== '0%'" class="dm-badge dm-badge-danger">
            Выгода −{{ product.discount }}
          </span>
        </div>

        <div class="availability">
          <span v-if="product.quantity > 0" class="dm-badge dm-badge-success">
            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 16 16">
              <path d="M13.485 1.431a1.473 1.473 0 0 1 2.104 2.062l-7.84 9.801a1.473 1.473 0 0 1-2.12.04L.431 8.138a1.473 1.473 0 0 1 2.084-2.083l4.111 4.112z"/>
            </svg>
            В наличии: {{ product.quantity }} шт.
          </span>
          <span v-else class="dm-badge dm-badge-danger">Нет в наличии</span>
        </div>

        <div class="product-description" v-if="product.description">
          <h3 class="info-title">Описание</h3>
          <p class="info-text">{{ product.description }}</p>
        </div>

        <div class="product-actions">
          <button
            class="dm-btn dm-btn-primary dm-btn-lg"
            @click="addToCart(product)"
            :disabled="product.quantity <= 0"
          >
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
              <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
            </svg>
            {{ product.quantity > 0 ? 'В корзину' : 'Нет в наличии' }}
          </button>

          <button
            class="dm-icon-btn favorite-button"
            @click="addToFavorites(product)"
            :class="{ 'is-favorite': product.is_favorite }"
            title="В избранное"
          >
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
              <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15"/>
            </svg>
          </button>

          <button class="dm-btn dm-btn-secondary dm-btn-lg" @click="showReviewsModal = true">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
              <path d="M8 2c1.1 0 1.99.9 1.99 2S9.1 6 8 6s-1.99-.9-1.99-2S6.9 2 8 2m2 8.5c0-.96 1.09-2.04 2.3-2.31L12.5 8c-1.77-.33-2.31-.9-2.31-1.5h-1.1V14h4v-3.5z"/>
            </svg>
            Отзывы
            <span class="dm-count-badge" v-if="product.reviews_count">{{ product.reviews_count }}</span>
          </button>
        </div>
      </div>
    </div>

    <transition name="modal">
      <div v-if="showReviewsModal" class="modal-mask">
        <div class="modal-wrapper">
          <div class="modal-container">
            <div class="modal-header">
              <h3>Отзывы о товаре</h3>
              <button class="dm-icon-btn" @click="showReviewsModal = false" title="Закрыть">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                  <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                </svg>
              </button>
            </div>
            <div class="modal-body" v-if="product.id">
              <ProductReviews :product-id="product.id" :is-modal="true" />
            </div>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';
import { useUserStore } from '../Store/user';
import ProductReviews from './Feedback/ProductRewues.vue';

const route = useRoute();
const router = useRouter();
const product = ref({});
const ModalMessage = ref(false);
const message = ref("");
const showReviewsModal = ref(false);
const userStore = useUserStore();

const formatPrice = (value) => {
  const num = Number(value || 0);
  return num.toLocaleString('ru-RU') + ' ₽';
};

const plural = (n) => {
  n = Number(n) || 0;
  const forms = ['отзыв', 'отзыва', 'отзывов'];
  const abs = Math.abs(n) % 100;
  const last = abs % 10;
  if (abs > 10 && abs < 20) return forms[2];
  if (last > 1 && last < 5) return forms[1];
  if (last === 1) return forms[0];
  return forms[2];
};

const fetchProduct = async () => {
  try {
    const response = await axios.get(`/api/products/${route.params.id}`);
    product.value = response.data;
  } catch (error) {
    console.error('Error fetching product:', error);
  }
};

const addToCart = async (product) => {
  try {
    if (!userStore.isAuthenticated) {
      router.push('/erorsauth');
      return;
    }

    await axios.post('/api/AddCart', {
      product_id: product.id,
      quantity: 1
    }, {
      withCredentials: true,
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Authorization': `Bearer ${userStore.token}`
      }
    });

    message.value = "Товар добавлен в корзину!";
    ModalMessage.value = true;
    setTimeout(() => {
      ModalMessage.value = false;
    }, 1300);
  } catch (error) {
    console.error('Ошибка добавления:', error);
  }
};

const addToFavorites = async (product) => {
  try {
    if (!userStore.isAuthenticated) {
      router.push('/erorsauth');
      return;
    }

    await axios.post('/api/favorites/add', {
      product_id: product.id
    }, {
      withCredentials: true,
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Authorization': `Bearer ${userStore.token}`
      }
    });

    message.value = "Товар добавлен в избранное!";
    ModalMessage.value = true;
    setTimeout(() => {
      ModalMessage.value = false;
    }, 1300);
  } catch (error) {
    console.error('Error adding to favorites:', error);
  }
}

onMounted(() => {
  fetchProduct();
});
</script>

<style scoped>
.product-page {
  max-width: var(--dm-content-width);
}

.breadcrumbs {
  display: flex;
  align-items: center;
  gap: var(--dm-space-2);
  margin-bottom: var(--dm-space-5);
  font-size: var(--dm-text-sm);
  color: var(--dm-text-muted);
}

.breadcrumbs a {
  color: var(--dm-primary);
  text-decoration: none;
  font-weight: 600;
}

.breadcrumbs a:hover {
  text-decoration: underline;
}

.breadcrumbs-current {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  max-width: 420px;
}

.product-detail {
  display: flex;
  gap: var(--dm-space-6);
  align-items: flex-start;
}

.product-image-card {
  flex: 1;
  padding: var(--dm-space-6);
}

.product-image-wrapper {
  width: 100%;
  aspect-ratio: 4 / 3;
  display: flex;
  align-items: center;
  justify-content: center;
  background: var(--dm-surface-muted);
  border: 1px solid var(--dm-border);
  border-radius: var(--dm-radius-lg);
  overflow: hidden;
}

.product-info {
  flex: 1;
  padding: var(--dm-space-6);
  display: flex;
  flex-direction: column;
  gap: var(--dm-space-4);
}

.product-name {
  margin: 0;
  font-size: var(--dm-text-2xl);
  font-weight: 800;
  letter-spacing: -0.01em;
  color: var(--dm-text);
  line-height: 1.25;
}

.product-rating {
  display: flex;
  align-items: center;
  gap: var(--dm-space-2);
}

.stars {
  font-size: var(--dm-text-lg);
  color: var(--dm-border-strong);
  letter-spacing: 2px;
}

.stars .filled {
  color: var(--dm-warning);
}

.rating-value {
  font-weight: 800;
  color: var(--dm-text);
}

.rating-link {
  border: none;
  background: none;
  cursor: pointer;
  font-size: var(--dm-text-sm);
  font-weight: 600;
  color: var(--dm-primary);
  text-decoration: underline;
  padding: 0;
}

.rating-link:hover {
  color: var(--dm-primary-hover);
}

.price-section {
  display: flex;
  align-items: center;
  gap: var(--dm-space-3);
  padding: var(--dm-space-4) 0;
  border-top: 1px solid var(--dm-border);
  border-bottom: 1px solid var(--dm-border);
}

.price {
  font-size: 2rem;
  font-weight: 800;
  color: var(--dm-text);
}

.availability {
  display: flex;
  align-items: center;
  gap: var(--dm-space-2);
}

.product-description {
  padding: var(--dm-space-4) 0;
  border-top: 1px solid var(--dm-border);
}

.info-title {
  margin: 0 0 var(--dm-space-3);
  font-size: var(--dm-text-md);
  font-weight: 700;
  color: var(--dm-text);
}

.info-text {
  margin: 0;
  font-size: var(--dm-text-base);
  line-height: 1.6;
  color: var(--dm-text-secondary);
}

.product-actions {
  display: flex;
  align-items: center;
  gap: var(--dm-space-3);
  margin-top: auto;
  flex-wrap: wrap;
}

.favorite-button {
  width: 48px;
  height: 48px;
  border: 1px solid var(--dm-border-strong);
  background: var(--dm-surface);
}

.favorite-button:hover {
  border-color: var(--dm-danger);
  background: var(--dm-danger-soft);
  color: var(--dm-danger);
}

.favorite-button.is-favorite {
  color: var(--dm-danger);
}

/* ---- Модальное окно отзывов ---- */
.modal-mask {
  position: fixed;
  z-index: var(--dm-z-modal);
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(16, 24, 40, 0.55);
  backdrop-filter: blur(2px);
  display: flex;
  justify-content: center;
  align-items: center;
  padding: var(--dm-space-4);
}

.modal-wrapper {
  width: 100%;
  max-width: 720px;
  max-height: 92vh;
}

.modal-container {
  background: var(--dm-bg);
  border-radius: var(--dm-radius-xl);
  box-shadow: var(--dm-shadow-lg);
  max-height: 92vh;
  display: flex;
  flex-direction: column;
  overflow: hidden;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: var(--dm-space-4) var(--dm-space-6);
  background: var(--dm-surface);
  border-bottom: 1px solid var(--dm-border);
}

.modal-header h3 {
  margin: 0;
  font-size: var(--dm-text-xl);
  font-weight: 800;
  color: var(--dm-text);
}

.modal-body {
  padding: var(--dm-space-5) var(--dm-space-6);
  overflow-y: auto;
}

.modal-enter-active,
.modal-leave-active {
  transition: opacity 0.2s ease;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}

.dm-fade-enter-active,
.dm-fade-leave-active {
  transition: opacity 0.25s ease, transform 0.25s ease;
}

.dm-fade-enter-from,
.dm-fade-leave-to {
  opacity: 0;
  transform: translate(-50%, -8px);
}

@media (max-width: 860px) {
  .product-detail {
    flex-direction: column;
  }

  .product-image-card,
  .product-info {
    width: 100%;
  }
}
</style>
