<template>
  <div class="dm-page favorites-page">
    <transition name="dm-fade">
      <div v-if="ModalMessage" class="dm-toast">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14m.75-10.25v5.5a.75.75 0 0 1-1.5 0v-5.5a.75.75 0 0 1 1.5 0M8 11.5a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5"/>
        </svg>
        {{ message }}
      </div>
    </transition>

    <h1 class="dm-page-title">
      Избранные товары
      <span class="dm-count-badge" v-if="favorites.length > 0">{{ favorites.length }}</span>
    </h1>

    <div v-if="!favorites || favorites.length === 0" class="dm-card dm-empty">
      <div class="dm-empty-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="44" height="44" fill="currentColor" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314"/>
        </svg>
      </div>
      <h2>Список избранного пуст</h2>
      <p>Отмечайте товары сердечком, чтобы быстро возвращаться к ним и сравнивать.</p>
      <router-link to="/" class="dm-btn dm-btn-primary dm-btn-lg">Перейти в каталог</router-link>
    </div>

    <div v-else>
      <div class="dm-card favorites-toolbar">
        <label class="favorite-check-all">
          <input type="checkbox" class="dm-checkbox" v-model="selectAll" @change="toggleSelectAll">
          <span>Выбрать все</span>
        </label>

        <div class="favorite-filter">
          <span class="favorite-filter-label">Сортировка:</span>
          <select v-model="sortOption" class="dm-select">
            <option value="default">По умолчанию</option>
            <option value="price_asc">По возрастанию цены</option>
            <option value="price_desc">По убыванию цены</option>
            <option value="date">По дате добавления</option>
          </select>
        </div>

        <label class="favorite-instock">
          <input type="checkbox" class="dm-checkbox" v-model="inStockOnly">
          <span>В наличии ({{ inStockCount }})</span>
        </label>
      </div>

      <div v-if="errorMessage" class="dm-alert">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
          <path d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14M8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4m.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2"/>
        </svg>
        {{ errorMessage }}
      </div>

      <div class="favorites-list">
        <transition-group name="dm-list" tag="div">
          <div class="dm-card favorite-item" v-for="item in filteredFavorites" :key="item.id">
            <div class="item-checkbox">
              <input type="checkbox" class="dm-checkbox" v-model="selectedItems" :value="item.id">
            </div>

            <div class="favorite-item-img">
              <img v-if="item.product?.imageUrl" :src="item.product.imageUrl" class="dm-img-contain" :alt="item.product.name" />
              <span v-else class="dm-muted">Нет фото</span>
            </div>

            <div class="favorite-item-details">
              <div class="favorite-item-head">
                <div class="favorite-item-info">
                  <h3 class="favorite-item-name">{{ item.product?.name }}</h3>
                  <div class="favorite-item-meta">
                    <span class="dm-badge dm-badge-primary" v-if="item.product?.discount && item.product.discount !== '0%'">
                      Скидка {{ item.product.discount }}
                    </span>
                    <span class="dm-badge dm-badge-success" v-if="item.product?.quantity > 0">В наличии</span>
                    <span class="dm-badge dm-badge-danger" v-else>Нет в наличии</span>
                  </div>
                </div>

                <div class="favorite-item-price">
                  <span class="favorite-item-price-value">{{ formatPrice(item.product?.price) }}</span>
                  <span v-if="item.product?.quantity > 0" class="favorite-item-price-stock">
                    Осталось: {{ item.product.quantity }} шт.
                  </span>
                </div>
              </div>

              <div class="favorite-item-actions">
                <button
                  class="dm-btn dm-btn-primary dm-btn-sm"
                  @click="addToCart(item.product)"
                  :disabled="item.product.quantity <= 0"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
                  </svg>
                  {{ item.product.quantity > 0 ? 'В корзину' : 'Нет в наличии' }}
                </button>
                <button class="dm-btn dm-btn-ghost dm-btn-sm remove-btn" @click="removeFromFavorites(item.product)">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                    <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                  </svg>
                  Удалить
                </button>
              </div>
            </div>
          </div>
        </transition-group>
      </div>

      <div class="selected-actions" v-if="selectedItems.length > 0">
        <span class="selected-info">Выбрано: {{ selectedItems.length }}</span>
        <button class="dm-btn dm-btn-primary" @click="addSelectedToCart">Добавить выбранные в корзину</button>
        <button class="dm-btn dm-btn-danger" @click="clearFavorites()">Удалить выбранные</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import { useUserStore } from '../../Store/user';

const favorites = ref([]);
const errorMessage = ref('');
const ModalMessage = ref(false);
const message = ref("");
const userStore = useUserStore();
const sortOption = ref('default');
const inStockOnly = ref(false);
const selectedItems = ref([]);
const selectAll = ref(false);

const fetchFavorites = async () => {
  try {
    const response = await axios.get('/api/favorites', {
      withCredentials: true,
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${userStore.token}`,
      }
    });
    favorites.value = response.data;
  } catch (error) {
    console.error('Ошибка при загрузке избранного:', error);
    errorMessage.value = 'Не удалось загрузить избранное';
  }
};

const formatPrice = (value) => {
  const num = Number(value || 0);
  return num.toLocaleString('ru-RU') + ' ₽';
};

const inStockCount = computed(() => {
  return favorites.value.filter(item => item.product?.quantity > 0).length;
});

const filteredFavorites = computed(() => {
  let result = [...favorites.value];
  if (inStockOnly.value)
   {
    result = result.filter(item => item.product?.quantity > 0);
  }

  switch (sortOption.value) {
    case 'price_asc':
      result.sort((a, b) => (a.product?.price || 0) - (b.product?.price || 0));
      break;
    case 'price_desc':
      result.sort((a, b) => (b.product?.price || 0) - (a.product?.price || 0));
      break;
    case 'date':
      result.sort((a, b) => new Date(b.createdAt) - new Date(a.createdAt));
      break;
  }

  return result;
});


const toggleSelectAll = () => {
  if (selectAll.value) {
    selectedItems.value = filteredFavorites.value.map(item => item.id);
  } else {
    selectedItems.value = [];
  }
};

const addSelectedToCart = async () => {
  try {
    const selectedProducts = favorites.value.filter(item =>
      selectedItems.value.includes(item.id)
    ).map(item => item.product);

    for (const product of selectedProducts) {
      if (product.quantity > 0) {
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
      }
    }

    message.value = `Добавлено ${selectedProducts.length} товаров в корзину`;
    ModalMessage.value = true;
    setTimeout(() => ModalMessage.value = false, 1300);

    selectedItems.value = [];
    selectAll.value = false;
  } catch (error) {
    console.error('Ошибка при добавлении выбранных товаров:', error);
    errorMessage.value = 'Не удалось добавить выбранные товары';
  }
};



async function clearFavorites() {
    try {
        await axios.delete('/api/favorites/clear', {
            withCredentials: true,
            headers: {
                'Authorization': `Bearer ${userStore.token}`
            }
        });

        message.value = "Избранное очищено";
        ModalMessage.value = true;
        setTimeout(() => ModalMessage.value = false, 1000);

        await fetchFavorites();
        selectedItems.value = [];
        selectAll.value = false;
    } catch (error) {
        console.error('Ошибка очистки избранного:', error);
        errorMessage.value = 'Не удалось очистить избранное';
    }
}
const removeFromFavorites = async (product) => {
  try {
    await axios.delete(`/api/favorites/remove/${product.id}`, {
      withCredentials: true,
      headers: {
        'Authorization': `Bearer ${userStore.token}`
      }
    });
    message.value = "Товар удален из избранного";
    ModalMessage.value = true;
    setTimeout(() => ModalMessage.value = false, 1300);
    await fetchFavorites();
  } catch (error) {
    console.error('Ошибка при удалении из избранного:', error);
    errorMessage.value = 'Не удалось удалить товар из избранного';
  }
};
const addToCart = async (product) => {
  try {
    if (product.quantity <= 0) {
      errorMessage.value = 'Товара нет в наличии';
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
    setTimeout(() => ModalMessage.value = false, 1300);
  } catch (error) {
    console.error('Ошибка при добавлении в корзину:', error);
    errorMessage.value = 'Не удалось добавить товар в корзину';
  }
};

onMounted(() => {
  fetchFavorites();
});
</script>

<style scoped>
.favorites-page {
  max-width: 960px;
}

.favorites-toolbar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: var(--dm-space-4);
  flex-wrap: wrap;
  padding: var(--dm-space-4) var(--dm-space-5);
  margin-bottom: var(--dm-space-6);
}

.favorite-check-all,
.favorite-instock {
  display: inline-flex;
  align-items: center;
  gap: var(--dm-space-2);
  font-size: var(--dm-text-base);
  font-weight: 600;
  color: var(--dm-text);
  cursor: pointer;
}

.favorite-filter {
  display: inline-flex;
  align-items: center;
  gap: var(--dm-space-3);
}

.favorite-filter-label {
  font-size: var(--dm-text-base);
  font-weight: 600;
  color: var(--dm-text-secondary);
}

.favorites-list {
  display: flex;
  flex-direction: column;
  gap: var(--dm-space-4);
}

.favorite-item {
  display: flex;
  align-items: flex-start;
  gap: var(--dm-space-5);
  padding: var(--dm-space-4);
  transition: box-shadow 0.2s ease;
}

.favorite-item:hover {
  box-shadow: var(--dm-shadow-md);
}

.item-checkbox {
  padding-top: var(--dm-space-2);
}

.favorite-item-img {
  flex-shrink: 0;
  width: 150px;
  height: 150px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: var(--dm-radius-md);
  background: var(--dm-surface-muted);
  border: 1px solid var(--dm-border);
  overflow: hidden;
}

.favorite-item-details {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: var(--dm-space-4);
  min-width: 0;
}

.favorite-item-head {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: var(--dm-space-4);
}

.favorite-item-info {
  display: flex;
  flex-direction: column;
  gap: var(--dm-space-2);
  min-width: 0;
}

.favorite-item-name {
  margin: 0;
  font-size: var(--dm-text-lg);
  font-weight: 700;
  line-height: 1.3;
  color: var(--dm-text);
}

.favorite-item-meta {
  display: flex;
  gap: var(--dm-space-2);
  flex-wrap: wrap;
}

.favorite-item-price {
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  gap: var(--dm-space-1);
  text-align: right;
}

.favorite-item-price-value {
  font-size: var(--dm-text-xl);
  font-weight: 800;
  color: var(--dm-text);
}

.favorite-item-price-stock {
  font-size: var(--dm-text-sm);
  color: var(--dm-text-muted);
}

.favorite-item-actions {
  display: flex;
  gap: var(--dm-space-3);
  flex-wrap: wrap;
}

.remove-btn {
  color: var(--dm-text-muted);
}

.remove-btn:hover {
  color: var(--dm-danger);
  background: var(--dm-danger-soft);
}

.selected-actions {
  display: flex;
  align-items: center;
  gap: var(--dm-space-3);
  flex-wrap: wrap;
  margin-top: var(--dm-space-6);
  padding: var(--dm-space-4) var(--dm-space-5);
  background: var(--dm-surface);
  border: 1px solid var(--dm-border);
  border-radius: var(--dm-radius-lg);
  box-shadow: var(--dm-shadow-sm);
}

.selected-info {
  margin-right: auto;
  font-size: var(--dm-text-base);
  font-weight: 600;
  color: var(--dm-text-secondary);
}

@media (max-width: 560px) {
  .favorite-item {
    flex-direction: column;
    align-items: stretch;
  }

  .favorite-item-img {
    width: 100%;
    height: 200px;
  }

  .favorite-item-head {
    flex-direction: column;
  }

  .favorite-item-price {
    align-items: flex-start;
    text-align: left;
  }
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

.dm-list-enter-active,
.dm-list-leave-active {
  transition: all 0.3s ease;
}

.dm-list-enter-from,
.dm-list-leave-to {
  opacity: 0;
  transform: translateY(-8px);
}
</style>
