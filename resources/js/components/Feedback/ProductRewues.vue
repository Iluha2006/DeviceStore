<template>
  <div class="reviews" :class="{ 'is-modal': isModal }">
    <transition name="dm-fade">
      <div v-if="toast" class="dm-toast">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14m.75-10.25v5.5a.75.75 0 0 1-1.5 0v-5.5a.75.75 0 0 1 1.5 0M8 11.5a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5"/>
        </svg>
        {{ toast }}
      </div>
    </transition>

    <div v-if="error" class="dm-alert">
      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
        <path d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14M8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4m.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2"/>
      </svg>
      {{ error }}
    </div>

    <div v-if="isLoading" class="dm-loading">
      <span class="dm-spinner"></span>
      Загрузка отзывов...
    </div>

    <template v-else>
      <div class="dm-card add-review">
        <div class="add-review-head">
          <div class="review-head-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
              <path d="M6 3.5a1.5 1.5 0 0 1 5 0l.443 1.326a1.5 1.5 0 0 0 .94.94l1.327.443a1.5 1.5 0 0 1 0 2.832l-1.327.443a1.5 1.5 0 0 0-.94.94L11 12.5a1.5 1.5 0 0 1-5 0l-.443-1.326a1.5 1.5 0 0 0-.94-.94l-1.327-.443a1.5 1.5 0 0 1 0-2.832l1.327-.443a1.5 1.5 0 0 0 .94-.94z"/>
            </svg>
          </div>
          <div>
            <h3 class="add-review-title">Оставить отзыв</h3>
            <p class="add-review-sub">Поделитесь впечатлениями о товаре</p>
          </div>
        </div>

        <div class="rating-input" :class="{ 'error': errors.rating }">
          <span class="rating-label">Ваша оценка:</span>
          <div class="stars-input">
            <span
              v-for="i in 5"
              :key="i"
              @click="newReview.rating = i; clearError('rating')"
              @mouseenter="hoverRating = i"
              @mouseleave="hoverRating = 0"
              :class="{ 'filled': i <= (hoverRating || newReview.rating) }"
            >★</span>
          </div>
          <span class="field-error" v-if="errors.rating">{{ errors.rating }}</span>
        </div>

        <div class="form-group">
          <label for="review-pros">Достоинства</label>
          <textarea
            id="review-pros"
            v-model="newReview.pros"
            @input="clearError('pros')"
            :class="{ 'error': errors.pros }"
            placeholder="Что вам понравилось?"
            rows="3"
          ></textarea>
          <span class="field-error" v-if="errors.pros">{{ errors.pros }}</span>
        </div>

        <div class="form-group">
          <label for="review-cons">Недостатки</label>
          <textarea
            id="review-cons"
            v-model="newReview.cons"
            @input="clearError('cons')"
            :class="{ 'error': errors.cons }"
            placeholder="Что можно улучшить?"
            rows="3"
          ></textarea>
          <span class="field-error" v-if="errors.cons">{{ errors.cons }}</span>
        </div>

        <div class="form-group">
          <label for="review-comment">Комментарий</label>
          <textarea
            id="review-comment"
            v-model="newReview.comment"
            @input="clearError('comment')"
            :class="{ 'error': errors.comment }"
            placeholder="Ваш комментарий"
            rows="4"
          ></textarea>
          <span class="field-error" v-if="errors.comment">{{ errors.comment }}</span>
        </div>

        <button
          @click="submitReview"
          class="dm-btn dm-btn-primary dm-btn-block"
          :disabled="submitting"
        >
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
            <path d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l7.528-7.288-5.337 13.34z"/>
          </svg>
          {{ submitting ? 'Отправка...' : 'Отправить отзыв' }}
        </button>
      </div>

      <div class="reviews-section">
        <div class="reviews-head">
          <h3 class="reviews-title">Отзывы покупателей</h3>
          <span class="dm-count-badge">{{ reviews.length }}</span>
        </div>

        <div v-if="reviews.length === 0" class="dm-empty no-reviews">
          <div class="dm-empty-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="38" height="38" fill="currentColor" viewBox="0 0 16 16">
              <path d="M6 3.5a1.5 1.5 0 0 1 5 0l.443 1.326a1.5 1.5 0 0 0 .94.94l1.327.443a1.5 1.5 0 0 1 0 2.832l-1.327.443a1.5 1.5 0 0 0-.94.94L11 12.5a1.5 1.5 0 0 1-5 0l-.443-1.326a1.5 1.5 0 0 0-.94-.94l-1.327-.443a1.5 1.5 0 0 1 0-2.832l1.327-.443a1.5 1.5 0 0 0 .94-.94z"/>
            </svg>
          </div>
          <h2>Отзывов пока нет</h2>
          <p>Будьте первым, кто поделится впечатлениями о товаре.</p>
        </div>

        <div class="reviews-list">
          <div v-for="review in reviews" :key="review.id" class="dm-card review-item">
            <div class="review-item-head">
              <div class="review-author">
                <div class="review-avatar">{{ initials(review.user?.name) }}</div>
                <div>
                  <strong>{{ review.user?.name }}</strong>
                  <span class="review-date">{{ formatDate(review.created_at) }}</span>
                </div>
              </div>
              <div class="review-stars">
                <span v-for="i in 5" :key="i" :class="{ 'filled': i <= review.rating }">★</span>
              </div>
            </div>

            <div class="review-content">
              <div v-if="review.pros" class="review-pros">
                <span class="review-tag success">Достоинства</span>
                <p>{{ review.pros }}</p>
              </div>
              <div v-if="review.cons" class="review-cons">
                <span class="review-tag danger">Недостатки</span>
                <p>{{ review.cons }}</p>
              </div>
              <div v-if="review.comment" class="review-comment">
                <p>{{ review.comment }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </template>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useUserStore } from '@/Store/user';

const userStore = useUserStore();
const isLoading = ref(false);
const submitting = ref(false);
const error = ref(null);
const errors = ref({});
const toast = ref('');
const hoverRating = ref(0);

const props = defineProps({
  productId: {
    type: Number,
    required: true
  },
  isModal: {
    type: Boolean,
    default: false
  }
});

const newReview = ref({
  rating: 0,
  pros: '',
  cons: '',
  comment: ''
});

const reviews = ref([]);

const showToast = (msg) => {
  toast.value = msg;
  setTimeout(() => (toast.value = ''), 2200);
};

const initials = (name = '') => {
  const parts = String(name).trim().split(/\s+/).filter(Boolean);
  return parts.slice(0, 2).map(p => p[0]?.toUpperCase() || '').join('');
};

const clearError = (field) => {
  if (errors.value[field]) {
    errors.value[field] = '';
  }
};

const validateForm = () => {
  let isValid = true;
  errors.value = {};

  if (!newReview.value.rating) {
    errors.value.rating = 'Поставьте оценку';
    isValid = false;
  }

  if (newReview.value.pros.length > 500) {
    errors.value.pros = 'Не более 500 символов';
    isValid = false;
  }

  if (newReview.value.cons.length > 500) {
    errors.value.cons = 'Не более 500 символов';
    isValid = false;
  }

  if (newReview.value.comment.length > 1000) {
    errors.value.comment = 'Не более 1000 символов';
    isValid = false;
  }

  return isValid;
};

const fetchReviews = async () => {
  try {
    isLoading.value = true;
    error.value = null;
    const response = await axios.get(`/api/products/${props.productId}/reviews`, {
      withCredentials: true,
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json'
      }
    });
    reviews.value = response.data;
  } catch (err) {
    console.error('Error fetching reviews:', err);
    error.value = 'Не удалось загрузить отзывы';
    reviews.value = [];
  } finally {
    isLoading.value = false;
  }
};

const submitReview = async () => {
  if (!validateForm()) {
    return;
  }

  if (!userStore.isAuthenticated) {
    showToast('Войдите, чтобы оставить отзыв');
    return;
  }

  try {
    submitting.value = true;
    await axios.post(
      `/api/products/${props.productId}/reviews`,
      newReview.value,
      {
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${userStore.token}`
        },
        withCredentials: true
      }
    );

    await fetchReviews();
    newReview.value = { rating: 0, pros: '', cons: '', comment: '' };
    errors.value = {};
    error.value = null;
    showToast('Спасибо! Отзыв успешно отправлен');
  } catch (err) {
    if (err.response?.data?.errors) {
      errors.value = err.response.data.errors;
    } else if (err.response?.status === 401) {
      showToast('Сессия истекла. Войдите снова');
    } else {
      showToast(err.response?.data?.message || 'Ошибка при отправке отзыва');
    }
  } finally {
    submitting.value = false;
  }
};

const formatDate = (dateString) => {
  if (!dateString) return '';
  return new Date(dateString).toLocaleDateString('ru-RU', {
    year: 'numeric', month: 'long', day: 'numeric'
  });
};

onMounted(() => {
  fetchReviews();
});
</script>

<style scoped>
.reviews {
  display: flex;
  flex-direction: column;
  gap: var(--dm-space-6);
}

/* ---- Форма ---- */
.add-review {
  padding: var(--dm-space-6);
}

.add-review-head {
  display: flex;
  align-items: center;
  gap: var(--dm-space-3);
  margin-bottom: var(--dm-space-5);
}

.review-head-icon {
  flex-shrink: 0;
  width: 42px;
  height: 42px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: var(--dm-radius-md);
  background: var(--dm-primary-soft);
  color: var(--dm-primary);
}

.add-review-title {
  margin: 0;
  font-size: var(--dm-text-lg);
  font-weight: 700;
  color: var(--dm-text);
}

.add-review-sub {
  margin: 2px 0 0;
  font-size: var(--dm-text-sm);
  color: var(--dm-text-muted);
}

.rating-input {
  display: flex;
  align-items: center;
  gap: var(--dm-space-3);
  flex-wrap: wrap;
  margin-bottom: var(--dm-space-4);
}

.rating-input.error {
  align-items: flex-start;
}

.rating-label {
  font-size: var(--dm-text-base);
  font-weight: 600;
  color: var(--dm-text);
}

.stars-input {
  display: inline-flex;
  gap: 2px;
  font-size: 1.6rem;
  line-height: 1;
  color: var(--dm-border-strong);
  cursor: pointer;
  user-select: none;
}

.stars-input span {
  transition: transform 0.1s ease, color 0.1s ease;
}

.stars-input span:hover {
  transform: scale(1.15);
}

.stars-input .filled {
  color: var(--dm-warning);
}

.form-group {
  margin-bottom: var(--dm-space-4);
}

.form-group label {
  display: block;
  margin-bottom: var(--dm-space-2);
  font-size: var(--dm-text-base);
  font-weight: 600;
  color: var(--dm-text);
}

.form-group textarea {
  width: 100%;
  padding: 10px 14px;
  font-family: inherit;
  font-size: var(--dm-text-base);
  color: var(--dm-text);
  background: var(--dm-surface-muted);
  border: 1px solid var(--dm-border-strong);
  border-radius: var(--dm-radius-md);
  resize: vertical;
  transition: border-color 0.15s ease, box-shadow 0.15s ease, background 0.15s ease;
}

.form-group textarea:focus {
  outline: none;
  background: var(--dm-surface);
  border-color: var(--dm-primary);
  box-shadow: var(--dm-focus-ring);
}

.form-group textarea.error,
.rating-input.error .stars-input {
  border-color: var(--dm-danger);
  color: var(--dm-danger);
}

.field-error {
  display: block;
  margin-top: var(--dm-space-1);
  font-size: var(--dm-text-xs);
  color: var(--dm-danger);
}

/* ---- Список отзывов ---- */
.reviews-head {
  display: flex;
  align-items: center;
  gap: var(--dm-space-3);
  margin-bottom: var(--dm-space-4);
}

.reviews-title {
  margin: 0;
  font-size: var(--dm-text-xl);
  font-weight: 800;
  color: var(--dm-text);
}

.reviews-list {
  display: flex;
  flex-direction: column;
  gap: var(--dm-space-4);
}

.review-item {
  padding: var(--dm-space-5);
}

.review-item-head {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: var(--dm-space-4);
  margin-bottom: var(--dm-space-4);
  padding-bottom: var(--dm-space-4);
  border-bottom: 1px solid var(--dm-border);
}

.review-author {
  display: flex;
  align-items: center;
  gap: var(--dm-space-3);
}

.review-author strong {
  display: block;
  font-size: var(--dm-text-md);
  font-weight: 700;
  color: var(--dm-text);
}

.review-avatar {
  flex-shrink: 0;
  width: 42px;
  height: 42px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  background: var(--dm-primary-soft);
  color: var(--dm-primary);
  font-size: var(--dm-text-md);
  font-weight: 800;
}

.review-date {
  font-size: var(--dm-text-xs);
  color: var(--dm-text-muted);
}

.review-stars {
  font-size: var(--dm-text-lg);
  color: var(--dm-border-strong);
  letter-spacing: 2px;
}

.review-stars .filled {
  color: var(--dm-warning);
}

.review-content {
  display: flex;
  flex-direction: column;
  gap: var(--dm-space-3);
}

.review-pros,
.review-cons {
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

.review-pros p,
.review-cons p,
.review-comment p {
  margin: 0;
  font-size: var(--dm-text-base);
  line-height: 1.6;
  color: var(--dm-text);
}

.no-reviews {
  padding: var(--dm-space-10) var(--dm-space-6);
}

/* ---- Модальный режим ---- */
.is-modal .add-review {
  box-shadow: none;
  padding: var(--dm-space-5);
  margin-bottom: var(--dm-space-4);
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
</style>
