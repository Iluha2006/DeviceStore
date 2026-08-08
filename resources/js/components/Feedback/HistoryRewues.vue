<template>
  <div class="user-reviews">
    <div v-if="loading" class="dm-loading">
      <span class="dm-spinner"></span>
      Загрузка отзывов...
    </div>

    <div v-else-if="error" class="dm-alert">
      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
        <path d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14M8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4m.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2"/>
      </svg>
      {{ error }}
    </div>

    <div v-else-if="reviews.length === 0" class="dm-empty no-reviews">
      <div class="dm-empty-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="38" height="38" fill="currentColor" viewBox="0 0 16 16">
          <path d="M6 3.5a1.5 1.5 0 0 1 5 0l.443 1.326a1.5 1.5 0 0 0 .94.94l1.327.443a1.5 1.5 0 0 1 0 2.832l-1.327.443a1.5 1.5 0 0 0-.94.94L11 12.5a1.5 1.5 0 0 1-5 0l-.443-1.326a1.5 1.5 0 0 0-.94-.94l-1.327-.443a1.5 1.5 0 0 1 0-2.832l1.327-.443a1.5 1.5 0 0 0 .94-.94z"/>
        </svg>
      </div>
      <h2>У вас пока нет отзывов</h2>
      <p>Напишите отзыв о покупке на странице товара — он появится здесь.</p>
    </div>

    <div v-else class="reviews-list">
      <div v-for="review in reviews" :key="review.id" class="dm-card review-item">
        <div class="product-info" v-if="review.product">
          <div class="product-image-wrap">
            <img :src="review.product.imageUrl" :alt="review.product.name" class="dm-img-contain">
          </div>
          <div class="product-meta">
            <router-link :to="`/product/${review.product.id}`" class="product-link">
              {{ review.product.name }}
            </router-link>
            <span class="product-id">Артикул: {{ review.product.id }}</span>
          </div>
        </div>

        <div class="review-header">
          <div class="review-stars">
            <span v-for="i in 5" :key="i" :class="{ 'filled': i <= review.rating }">★</span>
          </div>
          <span class="review-date">{{ formatDate(review.created_at) }}</span>
        </div>

        <div class="review-content">
          <div v-if="review.pros" class="pros">
            <span class="review-tag success">Достоинства</span>
            <p>{{ review.pros }}</p>
          </div>
          <div v-if="review.cons" class="cons">
            <span class="review-tag danger">Недостатки</span>
            <p>{{ review.cons }}</p>
          </div>
          <div v-if="review.comment" class="comment">
            <p>{{ review.comment }}</p>
          </div>
        </div>

        <button
          @click="deleteReview(review.id)"
          class="dm-btn dm-btn-danger dm-btn-sm"
        >
          <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor" viewBox="0 0 16 16">
            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
            <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
          </svg>
          Удалить
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useUserStore } from '@/Store/user';

const userStore = useUserStore();
const reviews = ref([]);
const loading = ref(false);
const error = ref(null);

const fetchUserReviews = async () => {
  try {
    loading.value = true;
    error.value = null;
    const response = await axios.get('/api/user/reviews', {
      withCredentials: true,
      headers: {
        'Accept': 'application/json',
        'Authorization': `Bearer ${userStore.token}`,
        'Content-Type': 'application/json'
      }
    });
    reviews.value = response.data;
  } catch (err) {
    error.value = 'Ошибка загрузки отзывов';
    console.error('Error fetching reviews:', err);
  } finally {
    loading.value = false;
  }
};

const deleteReview = async (reviewId) => {
if (!confirm('Вы уверены, что хотите удалить этот отзыв?')) return;

try {
  await axios.delete(`/api/reviews/${reviewId}`, {
    withCredentials: true,
    headers: {
      'Authorization': `Bearer ${userStore.token}`,
      'Accept': 'application/json'
    }
  });
  await fetchUserReviews();
} catch (err) {
  error.value = err.response?.data?.message || 'Ошибка удаления отзыва';
  console.error('Error deleting review:', err);
}
};

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('ru-RU', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
};

onMounted(() => {
  if (userStore.user?.id) {
    fetchUserReviews();
  }
});
</script>

<style scoped>
.user-reviews {
  display: flex;
  flex-direction: column;
}

.reviews-list {
  display: flex;
  flex-direction: column;
  gap: var(--dm-space-4);
}

.review-item {
  padding: var(--dm-space-5);
  display: flex;
  flex-direction: column;
  gap: var(--dm-space-4);
}

.product-info {
  display: flex;
  align-items: center;
  gap: var(--dm-space-4);
}

.product-image-wrap {
  flex-shrink: 0;
  width: 72px;
  height: 72px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: var(--dm-surface-muted);
  border: 1px solid var(--dm-border);
  border-radius: var(--dm-radius-md);
  overflow: hidden;
}

.product-meta {
  display: flex;
  flex-direction: column;
  gap: 2px;
  min-width: 0;
}

.product-link {
  font-weight: 700;
  font-size: var(--dm-text-md);
  color: var(--dm-primary);
  text-decoration: none;
  line-height: 1.3;
}

.product-link:hover {
  text-decoration: underline;
}

.product-id {
  font-size: var(--dm-text-xs);
  color: var(--dm-text-muted);
}

.review-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: var(--dm-space-4);
  padding: var(--dm-space-3) 0;
  border-top: 1px solid var(--dm-border);
  border-bottom: 1px solid var(--dm-border);
}

.review-stars {
  font-size: var(--dm-text-lg);
  color: var(--dm-border-strong);
  letter-spacing: 2px;
}

.review-stars .filled {
  color: var(--dm-warning);
}

.review-date {
  color: var(--dm-text-muted);
  font-size: var(--dm-text-sm);
}

.review-content {
  display: flex;
  flex-direction: column;
  gap: var(--dm-space-3);
}

.pros,
.cons {
  display: flex;
  flex-direction: column;
  gap: 2px;
}

.review-tag {
  align-self: flex-start;
  padding: 2px 10px;
  font-size: var(--dm-text-xs);
  font-weight: 700;
  border-radius: var(--dm-radius-full);
}

.review-tag.success {
  color: var(--dm-success);
  background: var(--dm-success-soft);
}

.review-tag.danger {
  color: var(--dm-danger);
  background: var(--dm-danger-soft);
}

.pros p,
.cons p,
.comment p {
  margin: 0;
  font-size: var(--dm-text-base);
  line-height: 1.6;
  color: var(--dm-text);
}

.no-reviews {
  padding: var(--dm-space-12) var(--dm-space-6);
}
</style>
