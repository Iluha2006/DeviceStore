<template>
    <div class="profile-container">
      <div class="profile-header">
       </div>

      <div class="profile-layout">
        <div class="profile-sidebar">
          <ul class="profile-menu">
            <li
              :class="{ active: activeTab === 'profile' }"
              @click="setActiveTab('profile')"
            >
              Личный кабинет
            </li>
            <li
              :class="{ active: activeTab === 'orders' }"
              @click="setActiveTab('orders')"
            >
              Мои заказы
            </li>
            <li
              :class="{ active: activeTab === 'reviews' }"
              @click="setActiveTab('reviews')"
            >
              Мои Отзывы
            </li>
            <li>
              <router-link to="/cartproduct" class="menu-link">
                Корзина
              </router-link>
            </li>
            <li>
              <router-link to="/favorites" class="menu-link">
                Избранное
              </router-link>
            </li>

            <li @click="logout" class="logout-btn">
              Выйти
            </li>
          </ul>
        </div>
        <div class="profile-content">



                <div v-if="loading" class="loading">Загрузка...</div>
                <div v-else-if="error" class="error-message">{{ error }}</div>
                <div v-else-if="profile">
                  <div v-if="activeTab === 'profile'" class="profile-info-section">
                    <div class="avatar-section">
                      <avatar-profile></avatar-profile>
                      <h1 v-if="!isEditing">{{ profile.name }}</h1>
                      <input
                        v-else
                        v-model="editForm.name"
                        class="edit-input"
                        type="text"
                        placeholder="Имя"
                      >
                    </div>

                    <div class="user-info">
                      <div class="info-field">
                        <span class="label">Email:</span>
                        <span v-if="!isEditing" class="value">{{ profile.email }}</span>
                        <input
                          v-else
                          v-model="editForm.email"
                          class="edit-input"
                          type="email"
                          placeholder="Email"
                        >
                      </div>
                      <div class="info-field">
                        <span class="label">Дата регистрации:</span>
                        <span class="value">{{ formatDate(profile.created_at) }}</span>
                      </div>

                      <div class="action-buttons">
                        <button @click="toggleEdit" class="edit-btn">
                          {{ isEditing ? 'Сохранить' : 'Редактировать' }}
                        </button>
                        <button
                          v-if="isEditing"
                          @click="cancelEdit"
                          class="cancel-btn"
                        >
                          Отмена
                        </button>
                        <button @click="deleteProfile" class="delete-btn">Удалить профиль</button>
                      </div>
                    </div>
                  </div>

                  <div v-if="activeTab === 'reviews'" class="offers-section">
                    <history-rewues></history-rewues>
                  </div>

                  <div v-if="activeTab === 'orders'" class="orders-section">


                        <order-list></order-list>

                  </div>
                </div>
              </div>
            </div>
          </div>

  </template>

  <script setup>
  import AvatarProfile from './AvatarProfile.vue';
  import HistoryRewues from './Feedback/HistoryRewues.vue';
  import { ref, reactive, onMounted } from 'vue';
  import { useUserStore } from '../Store/user';
  import { useRouter } from 'vue-router';
  import axios from 'axios';
 import OrderList from './Order/OrderList.vue';
  const router = useRouter();
  const userStore = useUserStore();

  const activeTab = ref('profile');
  const isEditing = ref(false);
  const profile = ref(null);
  const error = ref(null);
  const loading = ref(false);

  const editForm = reactive({
    name: '',
    email: ''
  });

  const setActiveTab = (tab) => {
    activeTab.value = tab;
  };

  const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('ru-RU', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
};

  const logout = async () => {
    try {
      loading.value = true;
      await userStore.logout();
      router.push('/');
    } catch (err) {
      error.value = 'Ошибка при выходе из системы';
      console.error('Logout error:', err);
    } finally {
      loading.value = false;
    }
  };

  const fetchProfile = async () => {
    try {
      loading.value = true;
      const response = await axios.get(`/api/profile/${userStore.user.id}`, {
        headers: {
          'Authorization': `Bearer ${userStore.token}`,
          'Accept': 'application/json'
        }
      });
      profile.value = response.data;
    } catch (err) {
      error.value = err.response?.data?.message || 'Ошибка загрузки профиля';
      console.error('Profile fetch error:', err);
    } finally {
      loading.value = false;
    }
  };

  const toggleEdit = () => {
    if (isEditing.value) {
      saveProfile();
    } else {
      startEditing();
    }
  };

  const startEditing = () => {
    if (profile.value) {
      editForm.name = profile.value.name;
      editForm.email = profile.value.email;
      isEditing.value = true;
    }
  };

  const saveProfile = async () => {
    try {
      if (!profile.value?.id) return;

      loading.value = true;
      const response = await axios.put(`/api/profile/${profile.value.id}`, editForm, {
        headers: {
          'Authorization': `Bearer ${userStore.token}`,
          'Content-Type': 'application/json'
        }
      });

      profile.value = response.data;
      userStore.setUser(response.data);
      isEditing.value = false;
      error.value = null;
    } catch (err) {
      error.value = err.response?.data?.message || 'Ошибка сохранения профиля';
      console.error('Save profile error:', err);
    } finally {
      loading.value = false;
    }
  };

  const cancelEdit = () => {
    isEditing.value = false;
    error.value = null;
  };

  const deleteProfile = async () => {
    if (!confirm('Вы уверены, что хотите удалить профиль? Это действие нельзя отменить.')) {
      return;
    }

    try {
      if (!profile.value?.id) return;

      loading.value = true;
      await axios.delete(`/api/profile/${profile.value.id}`, {
        headers: {
          'Authorization': `Bearer ${userStore.token}`,
          'Content-Type': 'application/json'
        }
      });
      await userStore.logout();
      router.push('/');
    } catch (err) {
      error.value = err.response?.data?.message || 'Ошибка удаления профиля';
      console.error('Delete profile error:', err);
    } finally {
      loading.value = false;
    }
  };

  onMounted(async () => {
    if (userStore.user?.id) {
      await fetchProfile();
    } else {
      error.value = 'Пользователь не авторизован';
    }
  });
  </script>

  <style scoped>

.orders-section{
    overflow-y: auto;
    height: 500px;
    flex-grow: 1;
}
.offers-section {
    overflow-y: auto;
    height: 300px;

}
  .profile-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
  }

  .profile-header {
    text-align: center;
    margin-bottom: 30px;
  }

  .profile-header h1 {
    font-size: 2rem;
    color: #333;
  }

  h1{
    position:relative ;
    top:0
  }

  .profile-layout {
    display: flex;
    gap: 40px;
  }

  .profile-sidebar {
    width: 250px;
    flex-shrink: 0;
  }

  .profile-menu {
    list-style: none;
    padding: 0;
    margin: 0;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    overflow: hidden;
  }

  .profile-menu li {
    padding: 15px 20px;
    cursor: pointer;
    transition: background-color 0.2s;
    border-bottom: 1px solid #f0f0f0;
  }

  .profile-menu li:last-child {
    border-bottom: none;
  }

  .profile-menu li:hover {
    background-color: #f8f8f8;
  }

  .profile-menu li.active {
    background-color: #3498db;
    color: white;
  }

  .menu-link {
    color: inherit;
    text-decoration: none;
    display: block;
  }

  .logout-btn {
    color: #e74c3c;
  }

  .profile-content {
    flex: 1;
    background: white;
    border-radius: 8px;
    padding: 30px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  }

  .profile-info-section {
    display: flex;
    gap: 40px;
  }

  .avatar-section {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 15px;
  }

  .avatar-section h1 {
    margin: 10px 0 0;
    font-size: 1.5rem;
  }

  .user-info {
    flex: 1;
  }

  .info-field {
    margin-bottom: 15px;
    display: flex;
    align-items: center;
  }

  .label {
    font-weight: bold;
    width: 150px;
    color: #555;
  }

  .value {
    flex: 1;
  }

  .edit-input {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 1rem;
    width: 100%;
    max-width: 300px;
  }

  .edit-input:focus {
    outline: none;
    border-color: #3498db;
    box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
  }

  .action-buttons {
    display: flex;
    gap: 15px;
    margin-top: 30px;
  }

  .edit-btn, .delete-btn, .cancel-btn {
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-weight: 500;
    transition: background-color 0.2s;
  }

  .edit-btn {
    background-color: #3498db;
    color: white;
  }

  .edit-btn:hover {
    background-color: #2980b9;
  }

  .cancel-btn {
    background-color: #f8f8f8;
    color: #333;
    border: 1px solid #ddd;
  }

  .cancel-btn:hover {
    background-color: #eee;
  }

  .delete-btn {
    background-color: #f8f8f8;
    color: #e74c3c;
    border: 1px solid #e74c3c;
  }

  .delete-btn:hover {
    background-color: #ffecec;
  }

  .loading {
    text-align: center;
    padding: 40px;
    color: #777;
  }

  .error-message {
    color: #e74c3c;
    padding: 15px;
    background-color: #fdecea;
    border-radius: 4px;
    margin: 20px 0;
  }

  @media (max-width: 768px) {
    .profile-layout {
      flex-direction: column;
    }

    .profile-sidebar {
      width: 100%;
      margin-bottom: 20px;
    }

    .profile-info-section {
      flex-direction: column;
    }
  }
  </style>