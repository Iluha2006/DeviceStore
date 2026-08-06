<template>
    <div class="create-product-container">
      <div class="create-product-card">
        <h2 class="form-title">Добавить новый продукт</h2>
        <form @submit.prevent="createProduct" class="product-form">
          <div class="form-grid">
            <!-- Название -->
            <div class="form-group">
              <label class="form-label">Название продукта</label>
              <input
                v-model="product.name"
                class="form-input"
                placeholder="Введите название"
                required
              >
            </div>

            <!-- Цена и категория в одной строке -->
            <div class="form-row">
              <div class="form-group">
                <label class="form-label">Цена (₽)</label>
                <div class="input-with-icon">
                  <span class="input-icon">₽</span>
                  <input
                    v-model.number="product.price"
                    type="number"
                    step="0.01"
                    min="0"
                    class="form-input"
                    placeholder="0.00"
                    required
                  >
                </div>
              </div>

              <div class="form-group">
                <label class="form-label">Категория</label>
                <select
                  v-model.number="product.category_id"
                  class="form-select"
                  required
                >
                  <option value="" disabled selected>Выберите категорию</option>
                  <option v-for="cat in categoriesList" :value="cat.id" :key="cat.id">
  {{ cat.categoryname }}
</option>
                </select>
              </div>
            </div>


            <div class="form-group">
              <label class="form-label">Описание</label>
              <textarea
                v-model="product.description"
                class="form-textarea"
                placeholder="Подробное описание продукта..."
                rows="3"
              ></textarea>
            </div>
            <div class="form-group">
              <label class="form-label">Ссылка на изображение</label>
              <div class="image-preview" v-if="product.imageUrl">
                <img :src="product.imageUrl" alt="Превью" class="preview-image">
              </div>
              <input
                v-model="product.imageUrl"
                type="url"
                class="form-input"
                placeholder="https://example.com/image.jpg"
              >
            </div>
            <div class="form-row">
              <div class="form-group">
                <label class="form-label">Количество на складе</label>
                <input
                  v-model.number="product.quantity"
                  type="number"
                  min="0"
                  class="form-input"
                  placeholder="0"
                >
              </div>

              <div class="form-group">
                <label class="form-label">Скидка (%)</label>
                <input
                  v-model="product.discount"
                  class="form-input"
                  placeholder="0"
                >
              </div>
            </div>
          </div>

          <div class="form-actions">
            <button
              type="submit"
              class="submit-btn"
              :disabled="isSubmitting"
              :class="{ 'loading': isSubmitting }"
            >
              <span v-if="!isSubmitting">Создать продукт</span>
              <span v-else class="loading-text">
                <svg class="spinner" viewBox="0 0 50 50">
                  <circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="5"></circle>
                </svg>
                Сохранение...
              </span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useUserStore } from '@/Store/user';
import { useRouter } from 'vue-router';

const userStore = useUserStore();
const router = useRouter();
const isSubmitting = ref(false);
const categoriesList = ref([]);

const product = ref({
  name: '',
  price: 0,
  category_id: null,
  description: '',
  imageUrl: '',
  quantity: 0,
  discount: ''
});

async function loadCategories() {
  try {
    const response = await axios.get('/api/categories', {
      withCredentials: true,
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Authorization': `Bearer ${userStore.token}`
      }
    });
    categoriesList.value = response.data;
  } catch (error) {
    console.error('Ошибка загрузки категорий:', error);
  }
}

onMounted(() => {
  loadCategories();
});

async function createProduct() {
  isSubmitting.value = true;
  try {
    const response = await axios.post('/admin/products', product.value, {
      withCredentials: true,
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Authorization': `Bearer ${userStore.token}`
      }
    });

    alert(`Продукт "${response.data.name}" успешно создан!`);

  } catch (error) {
    console.error('Ошибка создания продукта:', error);
    alert(`Ошибка: ${error.response?.data?.message || 'Не удалось создать продукт'}`);
  } finally {
    isSubmitting.value = false;
  }
}
</script>

  <style scoped>
  .create-product-container {
    display: flex;
    justify-content: center;
    padding: 2rem;
    background-color: #f8fafc;
    min-height: 100vh;
  }

  .create-product-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    padding: 2.5rem;
    width: 100%;
    max-width: 800px;
  }

  .form-title {
    font-size: 1.75rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 2rem;
    text-align: center;
  }

  .product-form {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
  }

  .form-grid {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
  }

  .form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
  }

  .form-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
  }

  .form-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: #64748b;
  }

  .form-input, .form-select, .form-textarea {
    padding: 0.75rem 1rem;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 0.95rem;
    transition: all 0.2s;
    background-color: #f8fafc;
  }

  .form-input:focus, .form-select:focus, .form-textarea:focus {
    outline: none;
    border-color: #6366f1;
    box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2);
  }

  .form-textarea {
    resize: vertical;
    min-height: 100px;
  }

  .input-with-icon {
    position: relative;
  }

  .input-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #64748b;
    font-weight: 500;
  }

  .input-with-icon .form-input {
    padding-left: 2.5rem;
  }

  .image-preview {
    margin-bottom: 1rem;
  }

  .preview-image {
    max-width: 150px;
    max-height: 150px;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
  }

  .form-actions {
    display: flex;
    justify-content: flex-end;
    margin-top: 1rem;
  }

  .submit-btn {
    padding: 0.75rem 1.5rem;
    background-color: #6366f1;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }

  .submit-btn:hover {
    background-color: #4f46e5;
  }

  .submit-btn:disabled {
    background-color: #cbd5e1;
    cursor: not-allowed;
  }

  .loading-text {
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }

  .spinner {
    width: 1.25rem;
    height: 1.25rem;
    animation: rotate 1.4s linear infinite;
  }

  .spinner .path {
    stroke: white;
    stroke-linecap: round;
    animation: dash 1.4s ease-in-out infinite;
  }

  @keyframes rotate {
    100% {
      transform: rotate(360deg);
    }
  }

  @keyframes dash {
    0% {
      stroke-dasharray: 1, 150;
      stroke-dashoffset: 0;
    }
    50% {
      stroke-dasharray: 90, 150;
      stroke-dashoffset: -35;
    }
    100% {
      stroke-dasharray: 90, 150;
      stroke-dashoffset: -124;
    }
  }

  @media (max-width: 768px) {
    .create-product-card {
      padding: 1.5rem;
    }

    .form-row {
      grid-template-columns: 1fr;
    }
  }
  </style>