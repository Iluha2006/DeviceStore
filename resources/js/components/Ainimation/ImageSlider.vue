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
        <span class="slide-tag">Новинки сезона</span>
        <h3>{{ currentImage.LogoText }}</h3>
        <p>{{ currentImage.title }}</p>
        <button class="dm-btn dm-btn-primary dm-btn-lg" @click="nextSlide">
          Следующий →
        </button>
      </div>
    </div>

    <div class="slide-dots">
      <button
        v-for="(img, index) in images"
        :key="img.id"
        class="dot"
        :class="{ active: index === currentIndex }"
        @click="goToSlide(index)"
        :aria-label="'Слайд ' + (index + 1)"
      ></button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount, computed } from 'vue'
import images from './DataJs/images'

const currentIndex = ref(0)
const autoScroll = ref(true)
let interval = null

const currentImage = computed(() => images[currentIndex.value])

const nextSlide = () => {
  currentIndex.value = (currentIndex.value + 1) % images.length
}

const goToSlide = (index) => {
  currentIndex.value = index
}

const startAutoScroll = () => {
  if (autoScroll.value) {
    interval = setInterval(nextSlide, 5000)
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
  max-width: var(--dm-content-width);
  height: 480px;
  margin: 0 auto;
  border-radius: var(--dm-radius-xl);
  overflow: hidden;
  box-shadow: var(--dm-shadow-lg);
  background: var(--dm-surface);
}

.slide {
  display: flex;
  height: 100%;
  position: relative;
}

.image-container {
  width: 58%;
  position: relative;
  background: var(--dm-surface-muted);
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
  width: 72px;
  height: auto;
  filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
}

.text-content {
  width: 42%;
  padding: 3rem;
  display: flex;
  flex-direction: column;
  justify-content: center;
  background: linear-gradient(135deg, var(--dm-primary-soft) 0%, var(--dm-surface) 60%);
}

.slide-tag {
  align-self: flex-start;
  margin-bottom: var(--dm-space-4);
  font-size: var(--dm-text-xs);
  font-weight: 700;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  color: var(--dm-primary);
  background: var(--dm-primary-soft);
  border: 1px solid var(--dm-primary);
  padding: 4px 12px;
  border-radius: var(--dm-radius-full);
}

.text-content h3 {
  margin: 0 0 var(--dm-space-4);
  font-size: 2.4rem;
  font-weight: 800;
  letter-spacing: -0.02em;
  color: var(--dm-text);
  line-height: 1.15;
}

.text-content p {
  margin: 0 0 var(--dm-space-6);
  font-size: 1.25rem;
  color: var(--dm-text-secondary);
  font-weight: 500;
}

.slide-dots {
  position: absolute;
  bottom: 20px;
  left: 50%;
  transform: translateX(-50%);
  display: flex;
  gap: 8px;
  padding: 8px 14px;
  background: rgba(255, 255, 255, 0.75);
  backdrop-filter: blur(6px);
  border-radius: var(--dm-radius-full);
  box-shadow: var(--dm-shadow-sm);
}

.dot {
  width: 9px;
  height: 9px;
  border: none;
  border-radius: 50%;
  background: var(--dm-border-strong);
  cursor: pointer;
  padding: 0;
  transition: all 0.2s ease;
}

.dot:hover {
  background: var(--dm-text-muted);
}

.dot.active {
  background: var(--dm-primary);
  transform: scale(1.25);
}

@media (max-width: 768px) {
  .images-scroll-container {
    height: auto;
  }

  .slide {
    flex-direction: column;
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

  .slide-tag {
    align-self: center;
  }

  .logo-image {
    width: 56px;
    top: 16px;
    left: 16px;
  }
}
</style>
