<template>
  <AuthenticatedLayout>
    <template #header>Agregar Nuevo Producto</template>

    <div class="bg-white rounded-lg shadow-md p-6">
      <form @submit.prevent="submit" class="space-y-6">
        <!-- Mensajes de error generales -->
        <div v-if="Object.keys(errors).length > 0" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md mb-4">
          <p class="font-semibold">Por favor corrige los siguientes errores:</p>
          <ul class="mt-1 list-disc list-inside text-sm">
            <li v-for="(error, key) in errors" :key="key">{{ error }}</li>
          </ul>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Información básica -->
          <div class="space-y-6 col-span-1">
            <div>
              <h3 class="text-lg font-semibold text-gray-700 border-b pb-2">Información Básica</h3>
            </div>

            <div>
              <label for="name" class="block text-sm font-medium text-gray-700">Nombre del Producto*</label>
              <input 
                id="name" 
                v-model="form.name" 
                type="text" 
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200"
                required
              />
            </div>

            <div>
              <label for="description" class="block text-sm font-medium text-gray-700">Descripción</label>
              <textarea 
                id="description" 
                v-model="form.description" 
                rows="3"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200"
              ></textarea>
            </div>

            <div>
              <label for="category_id" class="block text-sm font-medium text-gray-700">Categoría*</label>
              <select 
                id="category_id" 
                v-model="form.category_id" 
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200"
                required
              >
                <option value="" disabled>Seleccione una categoría</option>
                <option v-for="category in categories" :key="category.id" :value="category.id">
                  {{ category.name }}
                </option>
              </select>
            </div>

            <div>
              <label for="supplier_id" class="block text-sm font-medium text-gray-700">Proveedor*</label>
              <select 
                id="supplier_id" 
                v-model="form.supplier_id" 
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200"
                required
              >
                <option value="" disabled>Seleccione un proveedor</option>
                <option v-for="supplier in suppliers" :key="supplier.id" :value="supplier.id">
                  {{ supplier.name }}
                </option>
              </select>
            </div>
          </div>

          <!-- Precios y Stock -->
          <div class="space-y-6 col-span-1">
            <div>
              <h3 class="text-lg font-semibold text-gray-700 border-b pb-2">Precios y Stock</h3>
            </div>

            <div class="grid grid-cols-2 gap-4">
              <div>
                <label for="purchase_price" class="block text-sm font-medium text-gray-700">Precio de Compra*</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <span class="text-gray-500 sm:text-sm">$</span>
                  </div>
                  <input 
                    id="purchase_price" 
                    v-model="form.purchase_price" 
                    type="number" 
                    step="0.01"
                    min="0"
                    class="pl-7 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200"
                    required
                    @input="calculateProfit"
                  />
                </div>
              </div>

              <div>
                <label for="selling_price" class="block text-sm font-medium text-gray-700">Precio de Venta*</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <span class="text-gray-500 sm:text-sm">$</span>
                  </div>
                  <input 
                    id="selling_price" 
                    v-model="form.selling_price" 
                    type="number" 
                    step="0.01"
                    min="0"
                    class="pl-7 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200"
                    required
                    @input="calculateProfit"
                  />
                </div>
              </div>
            </div>

            <div>
              <label for="stock" class="block text-sm font-medium text-gray-700">Stock Inicial*</label>
              <input 
                id="stock" 
                v-model="form.stock" 
                type="number" 
                min="0"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200"
                required
              />
            </div>

            <div v-if="profit !== null" class="mt-4 p-3 bg-green-50 rounded-md">
              <h4 class="font-medium text-green-800">Cálculo de Ganancia</h4>
              <div class="mt-2 grid grid-cols-2 gap-4">
                <div>
                  <p class="text-sm text-gray-700">Ganancia por Unidad:</p>
                  <p class="font-semibold">${{ profit.toFixed(2) }}</p>
                </div>
                <div>
                  <p class="text-sm text-gray-700">Porcentaje de Ganancia:</p>
                  <p class="font-semibold">{{ profitPercentage.toFixed(2) }}%</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Código de Barras e Imagen -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
          <div class="space-y-4">
            <div>
              <h3 class="text-lg font-semibold text-gray-700 border-b pb-2">Código de Barras</h3>
            </div>

            <div class="flex items-center space-x-4">
              <input 
                id="barcode" 
                v-model="form.barcode" 
                type="text" 
                placeholder="Escanea o ingresa el código de barras"
                class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200"
              />
              <button 
                type="button"
                @click="scanBarcode"
                class="py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-md shadow-sm transition-colors"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h3a1 1 0 011 1v3a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm2 2V5h1v1H5zM3 13a1 1 0 011-1h3a1 1 0 011 1v3a1 1 0 01-1 1H4a1 1 0 01-1-1v-3zm2 2v-1h1v1H5zM13 3a1 1 0 00-1 1v3a1 1 0 001 1h3a1 1 0 001-1V4a1 1 0 00-1-1h-3zm1 2v1h1V5h-1z" clip-rule="evenodd" />
                  <path d="M11 4a1 1 0 10-2 0v1a1 1 0 002 0V4zM10 7a1 1 0 011 1v1h2a1 1 0 110 2h-3a1 1 0 01-1-1V8a1 1 0 011-1zM16 9a1 1 0 100 2 1 1 0 000-2zM9 13a1 1 0 011-1h1a1 1 0 110 2v2a1 1 0 11-2 0v-3zM7 11a1 1 0 100-2H4a1 1 0 100 2h3zM17 13a1 1 0 01-1 1h-2a1 1 0 110-2h2a1 1 0 011 1zM16 17a1 1 0 100-2h-3a1 1 0 100 2h3z" />
                </svg>
                Escanear
              </button>
            </div>

            <div class="mt-1 text-sm text-gray-500">
              Si no proporcionas un código de barras, se generará uno automáticamente.
            </div>
          </div>

          <div class="space-y-4">
            <div>
              <h3 class="text-lg font-semibold text-gray-700 border-b pb-2">Imagen del Producto</h3>
            </div>

            <div class="flex items-center justify-center p-6 border-2 border-dashed border-gray-300 rounded-md">
              <div v-if="!imagePreview" class="text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <div class="mt-2 flex justify-center text-sm text-gray-600">
                  <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-green-600 hover:text-green-500 focus-within:outline-none">
                    <span>Cargar una imagen</span>
                    <input id="image" name="image" type="file" class="sr-only" @change="handleImageUpload">
                  </label>
                </div>
                <p class="text-xs text-gray-500 mt-1">PNG, JPG, GIF hasta 2MB</p>
              </div>
              <div v-else class="text-center">
                <img :src="imagePreview" alt="Vista previa" class="max-h-32 mx-auto">
                <button 
                  type="button" 
                  @click="removeImage" 
                  class="mt-2 text-sm text-red-600 hover:text-red-900"
                >
                  Eliminar imagen
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Botones de acción -->
        <div class="flex justify-end space-x-3 pt-6 border-t">
          <Link 
            href="/products" 
            class="py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
          >
            Cancelar
          </Link>
          <button 
            type="submit" 
            class="py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
            :disabled="isSubmitting"
          >
            <span v-if="isSubmitting">
              <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Guardando...
            </span>
            <span v-else>Guardar Producto</span>
          </button>
        </div>
      </form>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

// Props
const props = defineProps({
  categories: Array,
  suppliers: Array,
  errors: Object
});

// Form data
const form = useForm({
  name: '',
  description: '',
  purchase_price: '',
  selling_price: '',
  stock: 0,
  category_id: '',
  supplier_id: '',
  barcode: '',
  image: null
});

// UI state
const isSubmitting = ref(false);
const imagePreview = ref(null);
const profit = ref(null);
const profitPercentage = ref(0);

// Methods
const handleImageUpload = (e) => {
  const file = e.target.files[0];
  if (!file) return;
  
  // Check file size
  if (file.size > 2048 * 1024) {
    alert('La imagen es demasiado grande. El tamaño máximo es 2MB.');
    return;
  }
  
  // Create preview
  const reader = new FileReader();
  reader.onload = (e) => {
    imagePreview.value = e.target.result;
  };
  reader.readAsDataURL(file);
  
  // Set file to form
  form.image = file;
};

const removeImage = () => {
  form.image = null;
  imagePreview.value = null;
  document.getElementById('image').value = '';
};

const calculateProfit = () => {
  const purchase = parseFloat(form.purchase_price) || 0;
  const selling = parseFloat(form.selling_price) || 0;
  
  if (purchase > 0 && selling > 0) {
    profit.value = selling - purchase;
    profitPercentage.value = (profit.value / purchase) * 100;
  } else {
    profit.value = null;
    profitPercentage.value = 0;
  }
};

const scanBarcode = () => {
  // In a real implementation, you'd connect to a barcode scanner API
  // For demonstration, we'll simulate a scan with a prompt
  const scannedCode = prompt('Por favor escanee o ingrese el código de barras:');
  if (scannedCode) {
    form.barcode = scannedCode;
    
    // Optional: check if this product already exists in the system
    // You could make an API call here to check the barcode
  }
};

const submit = () => {
  isSubmitting.value = true;
  
  form.post('/products', {
    onSuccess: () => {
      isSubmitting.value = false;
    },
    onError: () => {
      isSubmitting.value = false;
    }
  });
};
</script>

<style scoped>
/* Custom styles if needed */
</style>