<template>
  <div class="flex h-screen overflow-hidden">
    <aside 
      class="fixed top-0 left-0 h-full bg-gradient-to-b from-[#00C853] to-[#00695C] shadow-lg z-40 transition-all duration-300 ease-in-out"
      :class="{
        'w-20': sidebarCollapsed,
        'w-64': !sidebarCollapsed
      }"
    >
      <div class="py-6 px-4 flex items-center justify-between">
        <div class="flex items-center space-x-3">
          <div class="logo-container w-12 h-12 flex items-center justify-center bg-white rounded-full shadow-md transform transition-transform hover:scale-110">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-7 h-7 fill-[#00C853]">
              <path d="M19.5 3.5L18 2l-1.5 1.5L15 2l-1.5 1.5L12 2l-1.5 1.5L9 2 7.5 3.5 6 2 4.5 3.5 3 2v20l1.5-1.5L6 22l1.5-1.5L9 22l1.5-1.5L12 22l1.5-1.5L15 22l1.5-1.5L18 22l1.5-1.5L21 22V2l-1.5 1.5z"/>
              <path d="M6.5 6h11v2h-11zM6.5 10h11v2h-11zM6.5 14h4v2h-4z"/>
            </svg>
          </div>
          <h2 
            v-show="!sidebarCollapsed" 
            class="text-2xl font-bold text-white tracking-wide opacity-transition"
          >
            EcoInvoice
          </h2>
        </div>
        
        <button 
          @click="toggleSidebar" 
          class="text-white hover:bg-white/20 p-2 rounded-full transition-all"
        >
          <svg 
            xmlns="http://www.w3.org/2000/svg" 
            class="h-6 w-6" 
            fill="none" 
            viewBox="0 0 24 24" 
            stroke="currentColor"
          >
            <path 
              stroke-linecap="round" 
              stroke-linejoin="round" 
              stroke-width="2" 
              d="M13 5l7 7-7 7M5 5l7 7-7 7"
            />
          </svg>
        </button>
      </div>

      <div class="border-t border-white/20 my-4"></div>

      <nav class="px-4">
        <ul class="space-y-2">
          <!-- Dashboard -->
          <li>
<button 
  @click="navigateTo('/dashboard')" 
  class="w-full menu-item group flex items-center rounded-lg p-3 transition-all duration-300 ease-in-out"
  :class="{ 
    'menu-item-active': $page.url === '/dashboard',
    'bg-white/20 hover:bg-white/30': true 
  }"
>
  <span class="icon-wrapper mr-3">
    <svg 
      xmlns="http://www.w3.org/2000/svg" 
      class="menu-icon w-6 h-6" 
      viewBox="0 0 24 24" 
      fill="none" 
      stroke="white" 
      stroke-width="2"
    >
      <path 
        stroke-linecap="round" 
        stroke-linejoin="round" 
        d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"
      />
    </svg>
  </span>
  <span 
    v-show="!sidebarCollapsed" 
    class="menu-text flex-1"
  >
    Dashboard
  </span>
</button>
          </li>

          <!-- Módulo de Productos (Acordeón) -->
          <li>
            <button 
              @click="toggleProductsMenu" 
              class="menu-item group flex items-center w-full text-left"
              :class="{ 'menu-item-active': isProductsMenuActive }"
            >
              <span class="icon-wrapper">
                <svg 
                  xmlns="http://www.w3.org/2000/svg" 
                  class="menu-icon w-6 h-6" 
                  viewBox="0 0 24 24" 
                  fill="none" 
                  stroke="white" 
                  stroke-width="2"
                >
                  <path 
                    stroke-linecap="round" 
                    stroke-linejoin="round" 
                    d="M20 7l-8-4-8 4m16 0v10a2 2 0 01-2 2H6a2 2 0 01-2-2V7m16 0H4"
                  />
                </svg>
              </span>
              <span 
                v-show="!sidebarCollapsed" 
                class="menu-text ml-3 flex-1"
              >
                Productos
              </span>
              <svg 
                v-show="!sidebarCollapsed" 
                xmlns="http://www.w3.org/2000/svg" 
                class="h-5 w-5 transform transition-transform"
                :class="{ 'rotate-180': productsMenuOpen }"
                fill="none" 
                viewBox="0 0 24 24" 
                stroke="currentColor"
              >
                <path 
                  stroke-linecap="round" 
                  stroke-linejoin="round" 
                  stroke-width="2" 
                  d="M19 9l-7 7-7-7"
                />
              </svg>
            </button>

            <!-- Submenú de Productos -->
            <ul 
              v-show="productsMenuOpen && !sidebarCollapsed" 
              class="pl-12 pt-2 space-y-2 transition-all duration-300 ease-in-out"
            >
              <li>
                <button 
                  @click="navigateTo('/products/create')" 
                  class="submenu-item flex items-center"
                  :class="{ 'submenu-item-active': $page.url === '/products/create' }"
                >
                  <span class="submenu-text">Agregar Producto</span>
                </button>
              </li>
              <li>
                <button 
                  @click="navigateTo('/products')" 
                  class="submenu-item flex items-center"
                  :class="{ 'submenu-item-active': $page.url === '/products' }"
                >
                  <span class="submenu-text">Listado de Productos</span>
                </button>
              </li>
              <li>
                <button 
                  @click="navigateTo('/categories')" 
                  class="submenu-item flex items-center"
                  :class="{ 'submenu-item-active': $page.url === '/categories' }"
                >
                  <span class="submenu-text">Categorías</span>
                </button>
              </li>
              <li>
                <button 
                  @click="navigateTo('/suppliers')" 
                  class="submenu-item flex items-center"
                  :class="{ 'submenu-item-active': $page.url === '/suppliers' }"
                >
                  <span class="submenu-text">Proveedores</span>
                </button>
              </li>
            </ul>
          </li>
        </ul>
      </nav>

      <div class="absolute bottom-0 left-0 right-0 p-4">
        <div 
          v-show="!sidebarCollapsed"
          class="bg-white/10 rounded-lg p-4 text-center text-white transition-all"
        >
          <p class="text-sm font-semibold opacity-80">EcoInvoice</p>
          <p class="text-xs text-white/70">© 2024 Todos los derechos reservados</p>
        </div>
      </div>
    </aside>

    <main 
      class="flex-1 transition-all duration-300 ease-in-out overflow-auto bg-gray-50 p-6"
      :class="{
        'ml-20': sidebarCollapsed,
        'ml-64': !sidebarCollapsed
      }"
    >
      <header class="sticky top-0 z-10 backdrop-blur-md bg-white/90 mb-6 px-6 py-4 flex justify-between items-center rounded-lg shadow-md">
        <div class="flex items-center">
          <h1 class="text-2xl font-semibold text-gray-800">
            <slot name="header"></slot>
          </h1>
        </div>

        <div class="flex items-center space-x-4">
          <div class="flex items-center gap-3 bg-green-50 px-4 py-2 rounded-full shadow-sm">
            <svg 
              xmlns="http://www.w3.org/2000/svg" 
              class="h-6 w-6 text-[#00C853]" 
              fill="none" 
              viewBox="0 0 24 24" 
              stroke="currentColor"
            >
              <path 
                stroke-linecap="round" 
                stroke-linejoin="round" 
                stroke-width="2" 
                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" 
              />
            </svg>
            <span class="text-sm font-medium text-gray-700">
              {{ $page.props.auth.user.name }}
            </span>
          </div>

          <button 
            @click="logout" 
            class="p-2 rounded-lg hover:bg-red-100 transition-colors group"
          >
            <svg 
              xmlns="http://www.w3.org/2000/svg" 
              class="h-6 w-6 text-gray-500 group-hover:text-red-500 transition-colors" 
              fill="none" 
              viewBox="0 0 24 24" 
              stroke="currentColor"
            >
              <path 
                stroke-linecap="round" 
                stroke-linejoin="round" 
                stroke-width="2" 
                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" 
              />
            </svg>
          </button>
        </div>
      </header>

      <slot></slot>
    </main>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { usePage, router } from '@inertiajs/vue3'

const sidebarCollapsed = ref(false)
const productsMenuOpen = ref(false)

const toggleSidebar = () => {
  sidebarCollapsed.value = !sidebarCollapsed.value
}

const toggleProductsMenu = () => {
  productsMenuOpen.value = !productsMenuOpen.value
}

// Método para navegar a una ruta
const navigateTo = (url) => {
  router.visit(url, { preserveState: true, preserveScroll: true })
}

// Método para cerrar sesión
const logout = () => {
  router.post('/logout', {}, {
    onSuccess: () => {
      router.visit('/login')
    }
  })
}

// Determinar si el menú de productos está activo (algún submenú seleccionado)
const isProductsMenuActive = computed(() => {
  const activeUrls = [
    '/products/create',
    '/products',
    '/categories',
    '/suppliers',
  ]
  return activeUrls.includes(usePage().url)
})
</script>

<style scoped>
.menu-item {
  @apply flex items-center p-2 rounded-lg text-white transition-all duration-300 ease-in-out;
}

.menu-item:hover {
  @apply bg-white/20;
}

.menu-item-active {
  @apply bg-white/30;
}

.icon-wrapper {
  @apply w-10 h-10 flex items-center justify-center rounded-full bg-white/10;
}

.menu-text {
  @apply font-medium text-sm opacity-transition;
}

.submenu-item {
  @apply flex items-center p-2 rounded-lg text-white transition-all duration-300 ease-in-out;
}

.submenu-item:hover {
  @apply bg-white/10;
}

.submenu-item-active {
  @apply bg-white/20;
}

.submenu-text {
  @apply font-medium text-sm opacity-80;
}

.opacity-transition {
  transition: opacity 0.3s ease, transform 0.3s ease;
}

.menu-icon {
  transition: transform 0.3s ease;
}

.menu-item:hover .menu-icon {
  transform: scale(1.1);
}
</style>