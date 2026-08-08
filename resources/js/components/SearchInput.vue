<template>
    <div class="search-container">
      <div class="search-wrapper">
        <input
          type="text"
          v-model="searchQuery"
          placeholder="Поиск товаров"
          class="search-input"
          @focus="showResults = true"
        @blur="onBlur"
          @keyup.enter="checkCategoryRedirect"
        >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          width="20"
          height="20"
          fill="currentColor"
          class="bi bi-search"
          viewBox="0 0 16 16"
        >
          <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
        </svg>

        <div v-if="showResults" class="search-results">
          <div v-for="result in filteredResults" :key="result.id" class="search-result-item">
            <router-link
              :to=" `/product/${result.id}`  "
              class="result-link"
            >
              <span class="result-type">{{ result.type === 'product' ? 'Товар' : 'Категория' }}:</span>
              {{ result.name }}
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </template>

  <script setup>
  import { ref, computed, onMounted, watch } from 'vue'
  import axios from 'axios'
  import { useRouter } from 'vue-router';

  const router = useRouter();
  const searchQuery = ref('')
  const showResults = ref(false)
  const products = ref([])
  const categories = ref([])
  const isLoading = ref(false)
  const error = ref(null)


  const categoryKeywords = {
    'телефон': '/phones',
    'телефоны': '/phones',
    'теле': '/phones',
    'смартфон': '/phones',
    'ноутбук': '/laptops',
    'ноутбуки': '/laptops',
    'ноут': '/laptops',
    'консоль': '/consoles',
    'консоли': '/consoles',
    'аудио': '/audio',
    'колонки': '/audio',
    'монитор': '/monitors',
    'мониторы': '/monitors',
    'телевизор': '/televison',
    'телевизоры': '/televison',
    'сеть': '/networkequipment',
    'сетевое': '/networkequipment'
  }

  const checkCategoryRedirect = () => {
  const query = searchQuery.value.toLowerCase().trim()

  showResults.value = true
}
  onMounted(async () => {
    isLoading.value = true
    error.value = null
    try {
      const [productsRes, categoriesRes] = await Promise.all([
        axios.get('/api/products'),
        axios.get('/api/categories')
      ])

      products.value = Array.isArray(productsRes.data) ? productsRes.data : []
      categories.value = Array.isArray(categoriesRes.data) ? categoriesRes.data : []

    } catch (err) {
      console.error('Ошибка загрузки данных:', err)
      error.value = err.message
    } finally {
      isLoading.value = false
    }
  })

  const filteredResults = computed(() => {
    if (!Array.isArray(products.value) || !Array.isArray(categories.value))
     {
      return []
    }

    if (searchQuery.value.length < 2) return []

    const query = searchQuery.value.toLowerCase()

    const productResults = products.value
      .filter(p => p?.name?.toLowerCase().includes(query))
      .map(p => ({ ...p, type: 'product' }))

    const categoryResults = categories.value
      .filter(c => c?.name?.toLowerCase().includes(query))
      .map(c => ({ ...c, type: 'category' }))

    return [...productResults, ...categoryResults].slice(0, 10)
  })

  const onBlur = () => {
    setTimeout(() => {
      showResults.value = false
    }, 200)
  }

  watch(searchQuery, (newVal) => {
    if (newVal.trim() === '') {
      showResults.value = false
    }
  })
  </script>
  <style scoped>
  .search-container {
    position: relative;
    display: flex;
    align-items: center;
  }

  .search-wrapper {
    position: relative;
    width: 100%;
  }

  .search-input {
    width: 100%;
    padding: 10px 38px 10px 14px;
    border: 1px solid var(--dm-border-strong);
    border-radius: var(--dm-radius-md);
    outline: none;
    background: var(--dm-surface-muted);
    font-family: inherit;
    font-size: var(--dm-text-base);
    color: var(--dm-text);
    transition: all 0.15s ease;
  }

  .search-input:focus {
    border-color: var(--dm-primary);
    background: var(--dm-surface);
    box-shadow: var(--dm-focus-ring);
  }

  .search-input::placeholder {
    color: var(--dm-text-muted);
  }

  .bi-search {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--dm-text-muted);
    pointer-events: none;
  }

  .search-results {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: var(--dm-surface);
    border: 1px solid var(--dm-border);
    border-radius: var(--dm-radius-md);
    box-shadow: var(--dm-shadow-lg);
    z-index: var(--dm-z-modal);
    margin-top: 6px;
    max-height: 400px;
    overflow-y: auto;
  }

  .search-result-item {
    padding: 10px 14px;
    border-bottom: 1px solid var(--dm-border);
    transition: background-color 0.15s;
  }

  .search-result-item:last-child {
    border-bottom: none;
  }

  .search-result-item:hover {
    background-color: var(--dm-surface-muted);
  }

  .result-link {
    display: block;
    color: var(--dm-text);
    text-decoration: none;
  }

  .result-type {
    font-weight: 700;
    color: var(--dm-primary);
    margin-right: 5px;
    font-size: var(--dm-text-xs);
    text-transform: uppercase;
  }
  </style>