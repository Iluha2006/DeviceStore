<template>
    <div>
        <navbar-modal></navbar-modal>
      <ImageSlider />
      <product-card></product-card>

    <Intsall/>

    </div>
  </template>

  <script setup>
  import { ref } from 'vue'

  import ProductCard from './ProductCard.vue'
  import ImageSlider from './Ainimation/ImageSlider.vue'
   import NavbarModal from './Modal/NavbarModal.vue'
  import Intsall from '../Install/Intsall.vue'
  </script>

  <style scoped>

  </style>