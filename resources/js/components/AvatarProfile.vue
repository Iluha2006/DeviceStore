<template>
    <div class="avatar-upload">
      <input
        type="file"
        ref="fileInput"
        accept="image/*"
        @change="handleFileUpload"
        style="display: none"
      >
      <div class="avatar-preview" @click="triggerFileInput">
        <img v-if="avatarUrl" :src="avatarUrl" class="preview-image">
        <img v-else :src="defaultAvatar" class="preview-image">
      </div>
    </div>
  </template>

  <script setup>
  import { ref, computed, onMounted } from 'vue';
  import { useUserStore } from '../Store/user';

  const userStore = useUserStore();
  const fileInput = ref(null);
  const defaultAvatar = 'https://avatars.mds.yandex.net/i?id=1fec8837c92eca6c1175ac4c8e6d56383e5d7956-5603780-images-thumbs&n=13';


  const avatarUrl = computed(() => {
    if (!userStore.user?.id) return defaultAvatar;

    const savedAvatar = localStorage.getItem(`avatar_${userStore.user.id}`);
    return savedAvatar || defaultAvatar;
  });

  const triggerFileInput = () => {
    fileInput.value.click();
  };

  const handleFileUpload = (event) => {
    const file = event.target.files[0];
    if (!file) return;

    if (!file.type.match('image.*')) {
      alert('Пожалуйста, выберите изображение!');
      return;
    }

    if (file.size > 5 * 1024 * 1024) {
      alert('Файл слишком большой! Максимальный размер 5MB.');
      return;
    }

    const reader = new FileReader();
    reader.onload = (e) => {

      if (userStore.user?.id) {
        localStorage.setItem(`user_avatar_ ${userStore.user.id}`, e.target.result);
      }
    };
    reader.readAsDataURL(file);
  };
  </script>
  <style scoped>
  .avatar-upload {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 15px;
    margin: 20px 0;
  }

  .avatar-preview {
    position: relative;
    width: 150px;
    height: 150px;
    border-radius: 50%;
    cursor: pointer;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  }

  .preview-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .actions {
    display: flex;
    gap: 10px;
  }

  .select-button,
  .delete-btn {
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.2s;
  }

  .select-button {
    background-color: #42b983;
    color: white;
  }

  .select-button:hover {
    background-color: #369f6e;
  }

  .delete-btn {
    background-color: #e53e3e;
    color: white;
  }

  .delete-btn:hover {
    background-color: #c53030;
  }
  </style>