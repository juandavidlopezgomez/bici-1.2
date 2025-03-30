<template>
  <AuthenticatedLayout>
    <template #header>
      Listado de Productos
    </template>

    <div class="p-6 bg-white rounded-lg shadow-md">
      <!-- Filtros -->
      <div class="mb-6 flex flex-col md:flex-row gap-4">
        <div class="flex-1">
          <label class="block text-sm font-medium text-gray-700">Buscar por Nombre</label>
          <input
            v-model="filters.search"
            type="text"
            placeholder="Buscar producto..."
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
            @input="fetchProducts"
          />
        </div>
        <div class="flex-1">
          <label class="block text-sm font-medium text-gray-700">Filtrar por Categoría</label>
          <select
            v-model="filters.category_id"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
            @change="fetchProducts"
          >
            <option value="">Todas las categorías</option>
            <option v-for="category in categories" :key="category.id" :value="category.id">
              {{ category.name }}
            </option>
          </select>
        </div>
      </div>

      <!-- Tabla de Productos -->
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoría</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proveedor</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio de Venta</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ganancia</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="product in products.data" :key="product.id">
              <td class="px-6 py-4 whitespace-nowrap">{{ product.name }}</td>
              <td class="px-6 py-4 whitespace-nowrap">{{ product.category ? product.category.name : 'Sin categoría' }}</td>
              <td class="px-6 py-4 whitespace-nowrap">{{ product.supplier ? product.supplier.name : 'Sin proveedor' }}</td>
              <td class="px-6 py-4 whitespace-nowrap">{{ product.stock }}</td>
              <td class="px-6 py-4 whitespace-nowrap">${{ product.sale_price }}</td>
              <td class="px-6 py-4 whitespace-nowrap">${{ product.profit }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <button
                  @click="editProduct(product)"
                  class="text-blue-600 hover:text-blue-800 mr-2"
                >
                  Editar
                </button>
                <button
                  @click="deleteProduct(product.id)"
                  class="text-red-600 hover:text-red-800"
                >
                  Eliminar
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Paginación -->
      <div class="mt-6 flex justify-between items-center">
        <span class="text-sm text-gray-700">
          Mostrando {{ products.from }} a {{ products.to }} de {{ products.total }} productos
        </span>
        <div class="flex space-x-2">
          <button
            v-for="link in products.links"
            :key="link.label"
            @click="fetchProducts(link.url)"
            :disabled="!link.url"
            class="px-3 py-1 rounded-md text-sm"
            :class="{
              'bg-green-600 text-white': link.active,
              'bg-gray-200 text-gray-700': !link.active && link.url,
              'bg-gray-100 text-gray-400 cursor-not-allowed': !link.url
            }"
            v-html="link.label"
          ></button>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import axios from 'axios'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const products = ref({ data: [] })
const categories = ref([])
const filters = ref({
  search: '',
  category_id: '',
})

onMounted(async () => {
  // Obtener categorías
  const categoriesResponse = await axios.get('/api/categories')
  categories.value = categoriesResponse.data

  // Obtener productos iniciales
  fetchProducts()
})

const fetchProducts = async (url = '/api/products') => {
  const params = {
    search: filters.value.search,
    category_id: filters.value.category_id,
  }
  const response = await axios.get(url, { params })
  products.value = response.data
}

const editProduct = (product) => {
  // Aquí puedes redirigir a una página de edición o abrir un modal
  alert(`Funcionalidad de edición para el producto ${product.name} aún no implementada.`)
}

const deleteProduct = async (id) => {
  if (confirm('¿Estás seguro de que deseas eliminar este producto?')) {
    try {
      await axios.delete(`/api/products/${id}`)
      alert('Producto eliminado con éxito')
      fetchProducts()
    } catch (error) {
      console.error(error)
      alert('Error al eliminar el producto')
    }
  }
}
</script>