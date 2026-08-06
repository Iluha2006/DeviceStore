<template>
    <div id="app">
      <Header
        @toggle-profile-modal="toggleRegisterModal"
        @toggle-menu="toggleMenu"
      />
      <NavbarModal
        :is-open="isMenuOpen"
        @close="closeMenu"
      />
      <router-view></router-view>

      <Authorization
        v-if="isRegisterModalOpen"
        @close="closeRegisterModal"
      />
    </div>
  </template>

  <script setup>
  import { ref } from 'vue'
  import Header from './components/Header.vue'
  import NavbarModal from '../js/components/Modal/NavbarModal.vue'
  import Authorization from './components/Authorization.vue'

  const isRegisterModalOpen = ref(false)
  const isMenuOpen = ref(false)

  const toggleRegisterModal = () => {
    isRegisterModalOpen.value = !isRegisterModalOpen.value
  }

  const closeRegisterModal = () => {
    isRegisterModalOpen.value = false
  }

  const toggleMenu = () => {
    isMenuOpen.value = !isMenuOpen.value
  }

  const closeMenu = () => {
    isMenuOpen.value = false
  }
  </script>