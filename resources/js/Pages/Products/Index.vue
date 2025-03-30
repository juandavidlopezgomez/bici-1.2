<template>
  <AuthenticatedLayout>
    <template #header>Listado de Productos</template>

    <div class="bg-white rounded-lg shadow-md p-6">
      <div class="mb-6 flex justify-between items-center">
        <h2 class="text-xl font-semibold text-gray-800">Todos los Productos</h2>
        <Link
          href="/products/create"
          class="py-2 px-4 bg-green-600 hover:bg-green-700 text-white rounded-md shadow-sm transition-colors"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
          </svg>
          Agregar Producto
        </Link>
      </div>

      <div v-if="$page.props.flash.message" class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700">
        {{ $page.props.flash.message }}
      </div>

      <!-- Table -->
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Producto
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Categoría
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Precio Compra
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Precio Venta
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Stock
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Ganancia
              </th>
              <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                Acciones
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="product in products" :key="product.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="flex-shrink-0 h-10 w-10">
                    <img v-if="product.image_path" :src="'/storage/' + product.image_path" class="h-10 w-10 rounded-full object-cover" alt="" />
                    <div v-else class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                      </svg>
                    </div>
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">{{ product.name }}</div>
                    <div class="text-sm text-gray-500">SKU: {{ product.sku }}</div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                  {{ product.category ? product.category.name : 'Sin categoría' }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                ${{ product.purchase_price.toFixed(2) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                ${{ product.selling_price.toFixed(2) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ product.stock }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">${{ product.profit.toFixed(2) }}</div>
                <div class="text-xs text-gray-500">{{ product.profit_percentage }}%</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <Link :href="`/products/${product.id}/edit`" class="text-indigo-600 hover:text-indigo-900 mr-3">
                  Editar
                </Link>
                <button @click="confirmDelete(product)" class="text-red-600 hover:text-red-900">
                  Eliminar
                </button>
              </td>
            </tr>
            <tr v-if="products.length === 0">
              <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                No hay productos registrados. <Link href="/products/create" class="text-green-600 hover:text-green-800">Agregar uno nuevo</Link>.
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center z-50">
      <div class="relative mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
          <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
          </div>
          <h3 class="text-lg leading-6 font-medium text-gray-900 mt-2">Eliminar Producto</h3>
          <div class="mt-2 px-7 py-3">
            <p class="text-sm text-gray-500">
              ¿Estás seguro de que deseas eliminar {{ productToDelete ? productToDelete.name : '' }}? Esta acción no se puede deshacer.
            </p>
          </div>
          <div class="items-center px-4 py-3 flex justify-center space-x-4">
            <button @click="showDeleteModal = false" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 focus:outline-none">
              Cancelar
            </button>
            <button @click="deleteProduct" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none" :disabled="isDeleting">
              <span v-if="isDeleting">Eliminando...</span>
              <span v-else>Sí, eliminar</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

// Props
const props = defineProps({
  products: Array
});

// State
const showDeleteModal = ref(false);
const productToDelete = ref(null);
const isDeleting = ref(false);

// Methods
const confirmDelete = (product) => {
  productToDelete.value = product;
  showDeleteModal.value = true;
};

const deleteProduct = () => {
  isDeleting.value = true;
  router.delete(`/products/${productToDelete.value.id}`, {
    onSuccess: () => {
      showDeleteModal.value = false;
      isDeleting.value = false;
    },
    onError: () => {
      isDeleting.value = false;
    }
  });
};
</script>