<template>
  <div class="dm-page profile-page">
    <h1 class="dm-page-title">Личный кабинет</h1>

    <div class="profile-layout">
      <aside class="profile-sidebar">
        <div class="dm-card profile-sidebar-card">
          <div class="profile-sidebar-user">
            <div class="profile-sidebar-avatar">
              <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" viewBox="0 0 16 16">
                <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/>
              </svg>
            </div>
            <div class="profile-sidebar-user-text" v-if="profile">
              <strong>{{ profile.name }}</strong>
              <span>{{ profile.email }}</span>
            </div>
          </div>

          <ul class="profile-menu">
            <li
              :class="{ active: activeTab === 'profile' }"
              @click="setActiveTab('profile')"
            >
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/>
              </svg>
              Личный кабинет
            </li>
            <li
              :class="{ active: activeTab === 'orders' }"
              @click="setActiveTab('orders')"
            >
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                <path d="M1 2.5A1.5 1.5 0 0 1 2.5 1h3A1.5 1.5 0 0 1 7 2.5v3A1.5 1.5 0 0 1 5.5 7h-3A1.5 1.5 0 0 1 1 5.5zM2.5 2a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5zm6.5.5A1.5 1.5 0 0 1 10.5 1h3A1.5 1.5 0 0 1 15 2.5v3A1.5 1.5 0 0 1 13.5 7h-3A1.5 1.5 0 0 1 9 5.5zM10.5 2a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5zM1 10.5A1.5 1.5 0 0 1 2.5 9h3A1.5 1.5 0 0 1 7 10.5v3A1.5 1.5 0 0 1 5.5 15h-3A1.5 1.5 0 0 1 1 13.5zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5zm6.5.5A1.5 1.5 0 0 1 10.5 9h3a1.5 1.5 0 0 1 1.5 1.5v3a1.5 1.5 0 0 1-1.5 1.5h-3A1.5 1.5 0 0 1 9 13.5zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5z"/>
              </svg>
              Мои заказы
            </li>
            <li
              :class="{ active: activeTab === 'reviews' }"
              @click="setActiveTab('reviews')"
            >
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                <path d="M6 3.5a1.5 1.5 0 0 1 5 0l.443 1.326a1.5 1.5 0 0 0 .94.94l1.327.443a1.5 1.5 0 0 1 0 2.832l-1.327.443a1.5 1.5 0 0 0-.94.94L11 12.5a1.5 1.5 0 0 1-5 0l-.443-1.326a1.5 1.5 0 0 0-.94-.94l-1.327-.443a1.5 1.5 0 0 1 0-2.832l1.327-.443a1.5 1.5 0 0 0 .94-.94zM8.5 2a.5.5 0 0 0-.9.3l-.443 1.326a2.5 2.5 0 0 1-1.57 1.57L4.26 5.6a.5.5 0 0 0 0 .944l1.326.443a2.5 2.5 0 0 1 1.57 1.57l.443 1.327a.5.5 0 0 0 .9 0l.443-1.326a2.5 2.5 0 0 1 1.57-1.57l1.327-.444a.5.5 0 0 0 0-.944l-1.326-.443a2.5 2.5 0 0 1-1.57-1.57z"/>
              </svg>
              Мои отзывы
            </li>
            <li>
              <router-link to="/cartproduct" class="menu-link">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                  <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
                </svg>
                Корзина
              </router-link>
            </li>
            <li>
              <router-link to="/favorites" class="menu-link">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                  <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15"/>
                </svg>
                Избранное
              </router-link>
            </li>
            <li @click="logout" class="logout-btn">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5H2a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h7.5a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2H2a1.5 1.5 0 0 0-1.5 1.5v9A1.5 1.5 0 0 0 2 14h7.5a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z"/>
                <path fill-rule="evenodd" d="M10.854 8.354a.5.5 0 0 0 0-.708l-2-2a.5.5 0 0 0-.708.708L9.293 7.5H4.5a.5.5 0 0 0 0 1h4.793l-1.147 1.146a.5.5 0 0 0 .708.708z"/>
              </svg>
              Выйти
            </li>
          </ul>
        </div>
      </aside>

      <div class="profile-content dm-card">
        <div v-if="loading" class="dm-loading">
          <span class="dm-spinner"></span>
          Загрузка профиля...
        </div>

        <div v-else-if="error" class="dm-alert">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
            <path d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14M8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4m.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2"/>
          </svg>
          {{ error }}
        </div>

        <div v-else-if="profile">
          <div v-if="activeTab === 'profile'" class="profile-info-section">
            <div class="avatar-section">
              <avatar-profile></avatar-profile>
              <div class="profile-name">
                <h2 v-if="!isEditing">{{ profile.name }}</h2>
                <input
                  v-else
                  v-model="editForm.name"
                  class="edit-input"
                  type="text"
                  placeholder="Имя"
                >
              </div>
            </div>

            <div class="user-info">
              <div class="info-field">
                <span class="label">Email</span>
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
                <span class="label">Дата регистрации</span>
                <span class="value">{{ formatDate(profile.created_at) }}</span>
              </div>

              <div class="action-buttons">
                <button @click="toggleEdit" class="dm-btn dm-btn-primary">
                  <svg v-if="!isEditing" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
                  </svg>
                  {{ isEditing ? 'Сохранить' : 'Редактировать' }}
                </button>
                <button
                  v-if="isEditing"
                  @click="cancelEdit"
                  class="dm-btn dm-btn-secondary"
                >
                  Отмена
                </button>
                <button @click="deleteProfile" class="dm-btn dm-btn-danger">Удалить профиль</button>
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
.profile-page {
  max-width: 1080px;
}

.profile-layout {
  display: flex;
  gap: var(--dm-space-6);
  align-items: flex-start;
}

.profile-sidebar {
  width: 270px;
  flex-shrink: 0;
  position: sticky;
  top: calc(var(--dm-header-height) + 16px);
}

.profile-sidebar-card {
  padding: var(--dm-space-5) 0;
}

.profile-sidebar-user {
  display: flex;
  align-items: center;
  gap: var(--dm-space-3);
  padding: 0 var(--dm-space-5) var(--dm-space-4);
  border-bottom: 1px solid var(--dm-border);
}

.profile-sidebar-avatar {
  flex-shrink: 0;
  width: 44px;
  height: 44px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  background: var(--dm-primary-soft);
  color: var(--dm-primary);
}

.profile-sidebar-user-text {
  display: flex;
  flex-direction: column;
  min-width: 0;
}

.profile-sidebar-user-text strong {
  font-size: var(--dm-text-md);
  font-weight: 700;
  color: var(--dm-text);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.profile-sidebar-user-text span {
  font-size: var(--dm-text-xs);
  color: var(--dm-text-muted);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.profile-menu {
  list-style: none;
  padding: 0;
  margin: var(--dm-space-3) 0 0;
  display: flex;
  flex-direction: column;
}

.profile-menu li {
  display: flex;
  align-items: center;
  gap: var(--dm-space-3);
  padding: 12px var(--dm-space-5);
  cursor: pointer;
  font-size: var(--dm-text-base);
  font-weight: 600;
  color: var(--dm-text-secondary);
  transition: background-color 0.15s ease, color 0.15s ease;
  border-left: 3px solid transparent;
}

.profile-menu li svg {
  flex-shrink: 0;
}

.profile-menu li:hover {
  background: var(--dm-surface-muted);
  color: var(--dm-text);
}

.profile-menu li.active {
  background: var(--dm-primary-soft);
  color: var(--dm-primary);
  border-left-color: var(--dm-primary);
}

.menu-link {
  display: flex;
  align-items: center;
  gap: var(--dm-space-3);
  color: inherit;
  text-decoration: none;
  flex: 1;
}

.logout-btn {
  margin-top: var(--dm-space-3);
  border-top: 1px solid var(--dm-border);
  padding-top: var(--dm-space-4);
  color: var(--dm-danger);
}

.logout-btn:hover {
  background: var(--dm-danger-soft);
  color: var(--dm-danger);
}

.profile-content {
  flex: 1;
  min-width: 0;
  padding: var(--dm-space-6);
}

.profile-info-section {
  display: flex;
  gap: var(--dm-space-8);
}

.avatar-section {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: var(--dm-space-3);
}

.profile-name h2 {
  margin: 0;
  font-size: var(--dm-text-xl);
  font-weight: 800;
  color: var(--dm-text);
}

.user-info {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: var(--dm-space-4);
}

.info-field {
  display: flex;
  align-items: center;
  gap: var(--dm-space-4);
  padding-bottom: var(--dm-space-4);
  border-bottom: 1px solid var(--dm-border);
}

.label {
  font-size: var(--dm-text-base);
  font-weight: 600;
  width: 170px;
  flex-shrink: 0;
  color: var(--dm-text-secondary);
}

.value {
  flex: 1;
  font-size: var(--dm-text-base);
  color: var(--dm-text);
}

.edit-input {
  flex: 1;
  padding: 9px 14px;
  font-family: inherit;
  font-size: var(--dm-text-base);
  color: var(--dm-text);
  border: 1px solid var(--dm-border-strong);
  border-radius: var(--dm-radius-md);
  transition: border-color 0.15s ease, box-shadow 0.15s ease;
}

.edit-input:focus {
  outline: none;
  border-color: var(--dm-primary);
  box-shadow: var(--dm-focus-ring);
}

.action-buttons {
  display: flex;
  gap: var(--dm-space-3);
  flex-wrap: wrap;
  margin-top: var(--dm-space-4);
}

.orders-section {
  overflow-y: auto;
  height: 500px;
  flex-grow: 1;
}

.offers-section {
  overflow-y: auto;
  height: 400px;
}

@media (max-width: 768px) {
  .profile-layout {
    flex-direction: column;
  }

  .profile-sidebar {
    width: 100%;
    position: static;
  }

  .profile-info-section {
    flex-direction: column;
  }

  .info-field {
    flex-direction: column;
    align-items: stretch;
    gap: var(--dm-space-2);
  }

  .label {
    width: auto;
  }
}
</style>
