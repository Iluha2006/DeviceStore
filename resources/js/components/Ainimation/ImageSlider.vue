<template>
    <div class="images-scroll-container">
      <div class="slide">
        <div class="image-container">
          <img
            :src="currentImage.image"
            :alt="currentImage.LogoText"
            class="main-image"
          />
          <img
            :src="currentImage.imageLogo"
            alt="brand logo"
            class="logo-image"
          />
        </div>
        <div class="text-content">
          <h3>{{ currentImage.LogoText }}</h3>
          <p>{{ currentImage.title }}</p>
          <button
            class="cta-button"
            @click="nextSlide"
          >
            Следующий →
          </button>
        </div>
      </div>
    </div>
  </template>

  <script setup>
  import { ref, onMounted, onBeforeUnmount,computed } from 'vue'
  import images from './DataJs/images'

  const currentIndex = ref(0)
  const autoScroll = ref(true)
  let interval = null

  const currentImage = computed(() => images[currentIndex.value])

  const nextSlide = () => {
    currentIndex.value = (currentIndex.value + 1) % images.length
  }

  const startAutoScroll = () => {
    if (autoScroll.value) {
      interval = setInterval(nextSlide, 3000)
    }
  }

  onMounted(() => {
    startAutoScroll()
  })

  onBeforeUnmount(() => {
    if (interval) clearInterval(interval)
  })
  </script>

  <style scoped>
  .images-scroll-container {
    position: relative;
    width: 100%;
    max-width: 1200px;
    height: 600px;
    margin: 2rem auto;
    border-radius: 12px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
  }

  .slide {
    display: flex;
    height: 600px;
    position: relative;
  }

  .image-container {
    width: 60%;
    position: relative;
  }

  .main-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
  }

  .logo-image {
    position: absolute;
    top: 30px;
    left: 30px;
    width: 70px;
    height: auto;
    filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3));
  }

  .text-content {
    width: 40%;
    padding: 3rem;
    display: flex;
    flex-direction: column;
    justify-content: center;
    background: linear-gradient(to right, #f8f9fa, #ffffff);
  }

  .text-content h3 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
    color: #1d1d1f;
    line-height: 1.2;
  }

  .text-content p {
    font-size: 1.5rem;
    margin-bottom: 2rem;
    color: #86868b;
    font-weight: 500;
  }

  .cta-button {
    background: #0071e3;
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 28px;
    font-size: 1.1rem;
    font-weight: 500;
    cursor: pointer;
    width: fit-content;
    transition: all 0.3s;
    box-shadow: 0 4px 8px rgba(0, 113, 227, 0.3);
  }

  .cta-button:hover {
    background: #0062c4;
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(0, 113, 227, 0.4);
  }

  @media (max-width: 768px) {
    .slide {
      flex-direction: column;
      height: auto;
    }

    .image-container,
    .text-content {
      width: 100%;
    }

    .text-content {
      padding: 2rem 1.5rem;
      text-align: center;
      align-items: center;
    }

    .logo-image {
      width: 80px;
      top: 20px;
      left: 20px;
    }
  }
  </style>