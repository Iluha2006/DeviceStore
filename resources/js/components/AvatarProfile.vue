<template>
    <div class="avatar-upload">
      <input
        type="file"
        ref="fileInput"
        accept="image/*"
        @change="handleFileUpload"
        style="display: none"
      >
      <div class="avatar-preview" @click="triggerFileInput" title="Изменить фото">
        <img v-if="avatarUrl" :src="avatarUrl" class="preview-image">
        <img v-else :src="defaultAvatar" class="preview-image">
        <div class="avatar-overlay">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
            <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
          </svg>
          Изменить
        </div>
      </div>
      <span class="avatar-hint">Нажмите на фото, чтобы загрузить новое</span>
    </div>
  </template>

  <script setup>
  import { ref, onMounted } from 'vue';
  import { useUserStore } from '../Store/user';

  const userStore = useUserStore();
  const fileInput = ref(null);
  const defaultAvatar = '/images/default-avatar.png';


  const avatarUrl = ref(defaultAvatar);

  const loadAvatar = () => {
    if (!userStore.user?.id) return;

    const savedAvatar = localStorage.getItem(`avatar_${userStore.user.id}`);
    avatarUrl.value = savedAvatar || defaultAvatar;
  };

  onMounted(loadAvatar);

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
        localStorage.setItem(`avatar_${userStore.user.id}`, e.target.result);
        avatarUrl.value = e.target.result;
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
    gap: var(--dm-space-3);
  }

  .avatar-preview {
    position: relative;
    width: 140px;
    height: 140px;
    border-radius: 50%;
    cursor: pointer;
    overflow: hidden;
    box-shadow: var(--dm-shadow-md);
    border: 3px solid var(--dm-primary-soft);
    transition: border-color 0.2s ease;
  }

  .avatar-preview:hover {
    border-color: var(--dm-primary);
  }

  .preview-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .avatar-overlay {
    position: absolute;
    inset: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 4px;
    font-size: var(--dm-text-xs);
    font-weight: 700;
    color: #ffffff;
    background: rgba(20, 22, 26, 0.55);
    opacity: 0;
    transition: opacity 0.2s ease;
  }

  .avatar-preview:hover .avatar-overlay {
    opacity: 1;
  }

  .avatar-hint {
    font-size: var(--dm-text-xs);
    color: var(--dm-text-muted);
    text-align: center;
  }
  </style>