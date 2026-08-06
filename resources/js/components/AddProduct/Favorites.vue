<template>
    <div class="favorites-container">
        <transition name="fade">
            <div v-if="ModalMessage" class="message-success">
                {{message}}
            </div>
        </transition>

      <h1>Избранные товары</h1>

      <div v-if="!favorites || favorites.length === 0" class="empty-favorites">
        <h1>Список избранного пуст</h1>
      </div>

      <div v-else>
        <div class="favorites-summary">

          <div class="filters-container">
            <div class="filter-group">
              <label><input type="checkbox" v-model="selectAll" @change="toggleSelectAll"> Выбрать все</label>
            </div>

            <div class="filter-group">
              <h4>Сортировка:</h4>
              <select v-model="sortOption" class="sort-select">
                <option value="default">По умолчанию</option>
                <option value="price_asc">По возрастанию цены</option>
                <option value="price_desc">По убыванию цены</option>
                <option value="date">По дате добавления</option>
              </select>
            </div>

            <div class="filter-group">
              <h4>Фильтр по наличию:</h4>
              <label>
                <input type="checkbox" v-model="inStockOnly"> В наличии ({{ inStockCount }})
              </label>
            </div>
          </div>
        </div>

        <div v-if="errorMessage" class="error-message">
          {{ errorMessage }}
        </div>

        <div class="favorite-item" v-for="item in filteredFavorites" :key="item.id">
          <div class="item-checkbox">
            <input type="checkbox" v-model="selectedItems" :value="item.id">
          </div>

          <img :src="item.product.imageUrl" class="favorite-item-image" :alt="item.product.name">

          <div class="favorite-item-details">
            <h3>{{ item.product.name }}</h3>
            <div class="price">Цена: {{ item.product.price }}₽</div>
            <div class="available" :class="{ 'out-of-stock': item.product.quantity <= 0 }">
              {{ item.product.quantity > 0 ? `В наличии: ${item.product.quantity} шт.` : 'Нет в наличии' }}
            </div>

            <div class="item-rating" v-if="item.product.rating">
              ★ {{ item.product.rating }} | {{ item.product.reviewsCount }} отзывов
            </div>



            <div class="item-actions">
              <button
                class="add-to-cart"
                @click="addToCart(item.product)"
                :disabled="item.product.quantity <= 0"
              >
                {{ item.product.quantity > 0 ? 'Добавить в корзину' : 'Нет в наличии' }}
              </button>
              <button class="remove-btn" @click="removeFromFavorites(item.product)">Удалить</button>
            </div>
          </div>
        </div>

        <div class="selected-actions" v-if="selectedItems.length > 0">
          <button class="add-selected" @click="addSelectedToCart">Добавить выбранные в корзину</button>
          <button class="remove-selected" @click="clearFavorites()">Удалить выбранные</button>
        </div>
      </div>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import { useUserStore } from '@/Store/user';

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
.favorites-container {
  max-width: 1000px;
  margin: 0 auto;
  padding: 20px;
}

.favorites-summary {
  margin-bottom: 20px;
  padding-bottom: 15px;
  border-bottom: 1px solid #eee;
}

.filters-container {
  display: flex;
  gap: 30px;
  margin: 15px 0;
  flex-wrap: wrap;
}

.filter-group {
  display: flex;
  flex-direction: column;
  gap: 5px;
}

.sort-select {
  padding: 5px;
  border-radius: 4px;
  border: 1px solid #ddd;
}

.favorite-item {
  display: flex;
  margin-bottom: 20px;
  padding: 15px;
  border: 1px solid #eee;
  border-radius: 8px;
  align-items: flex-start;
}

.item-checkbox {
  margin-right: 15px;
  padding-top: 5px;
}

.favorite-item-image {
  width: 150px;
  height: 150px;
  object-fit: contain;
  margin-right: 20px;
}

.favorite-item-details {
  flex-grow: 1;
}

h1 {
  text-align: center;
  font-size: 25px;
  margin-bottom: 20px;
}

.remove-btn {
  background: #ff4444;
  color: white;
  border: none;
  padding: 8px 15px;
  cursor: pointer;
  border-radius: 4px;
  margin-left: 10px;
}

.add-to-cart {
  background: #000;
  color: white;
  border: none;
  padding: 8px 15px;
  cursor: pointer;
  border-radius: 4px;
}

.add-to-cart:disabled {
  background: #ccc;
  cursor: not-allowed;
}

.empty-favorites {
  text-align: center;
  padding: 40px;
  font-size: 20px;
  color: #888;
}

.error-message {
  color: #ff4444;
  margin-bottom: 20px;
  padding: 10px;
  background: #ffeeee;
  border-radius: 4px;
}

.available {
  color: #666;
  font-size: 0.9rem;
  margin: 5px 0;
}

.out-of-stock {
  color: #ff4444;
}

.message-success {
  position: fixed;
  top: 80px;
  left: 50%;
  transform: translateX(-50%);
  background-color: #000;
  color: #fff;
  padding: 12px 20px;
  border-radius: 4px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.2);
  z-index: 1000;
  font-size: 1em;
}

.item-rating {
  color: #ffb400;
  margin: 5px 0;
}

.delivery-info {
  color: #666;
  font-size: 0.9rem;
  margin: 5px 0;
}

.item-actions {
  margin-top: 15px;
}

.selected-actions {
  display: flex;
  gap: 15px;
  margin-top: 20px;
  padding-top: 15px;
  border-top: 1px solid #eee;
}

.add-selected {
  background: #06ea28;
  color: white;
  border: none;
  padding: 10px 15px;
  cursor: pointer;
  border-radius: 4px;
}

.remove-selected {
  background: #ff4444;
  color: white;
  border: none;
  padding: 10px 15px;
  cursor: pointer;
  border-radius: 4px;
}

.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s;
}
.fade-enter, .fade-leave-to {
  opacity: 0;
}
</style>