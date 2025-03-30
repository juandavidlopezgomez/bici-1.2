<template>
  <AuthenticatedLayout>
    <template #header>
      Agregar Producto
    </template>

    <div class="max-w-3xl mx-auto p-6 bg-white rounded-lg shadow-md">
      <form @submit.prevent="submitProduct" class="space-y-6">
        <!-- Nombre -->
        <div>
          <label class="block text-sm font-medium text-gray-700">Nombre del Producto</label>
          <input
            v-model="form.name"
            type="text"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
            :class="{ 'border-red-500': errors.name }"
            placeholder="Ej: Laptop Dell XPS"
            required
          />
          <p v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name }}</p>
        </div>

        <!-- Código de Barras -->
        <div>
          <label class="block text-sm font-medium text-gray-700">Código de Barras</label>
          <div class="flex items-center space-x-3">
            <input
              v-model="form.barcode"
              type="text"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
              :class="{ 'border-red-500': errors.barcode }"
              placeholder="Escanea o ingresa el código de barras"
            />
            <button
              type="button"
              @click="startBarcodeScanner"
              class="mt-1 px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700"
            >
              Escanear
            </button>
          </div>
          <p v-if="errors.barcode" class="mt-1 text-sm text-red-600">{{ errors.barcode }}</p>
        </div>

        <!-- Descripción -->
        <div>
          <label class="block text-sm font-medium text-gray-700">Descripción</label>
          <textarea
            v-model="form.description"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
            :class="{ 'border-red-500': errors.description }"
            placeholder="Describe el producto..."
            rows="3"
          ></textarea>
          <p v-if="errors.description" class="mt-1 text-sm text-red-600">{{ errors.description }}</p>
        </div>

        <!-- Precios -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">Precio de Compra</label>
            <input
              v-model="form.purchase_price"
              type="number"
              step="0.01"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
              :class="{ 'border-red-500': errors.purchase_price }"
              placeholder="Ej: 500.00"
              required
            />
            <p v-if="errors.purchase_price" class="mt-1 text-sm text-red-600">{{ errors.purchase_price }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Precio de Venta</label>
            <input
              v-model="form.sale_price"
              type="number"
              step="0.01"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
              :class="{ 'border-red-500': errors.sale_price }"
              placeholder="Ej: 600.00"
              required
            />
            <p v-if="errors.sale_price" class="mt-1 text-sm text-red-600">{{ errors.sale_price }}</p>
          </div>
        </div>

        <!-- Imagen -->
        <div>
          <label class="block text-sm font-medium text-gray-700">Imagen del Producto</label>
          <input
            type="file"
            @change="handleImageUpload"
            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100"
            accept="image/*"
          />
          <p v-if="errors.image" class="mt-1 text-sm text-red-600">{{ errors.image }}</p>
        </div>

        <!-- Stock -->
        <div>
          <label class="block text-sm font-medium text-gray-700">Stock Inicial</label>
          <input
            v-model="form.stock"
            type="number"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
            :class="{ 'border-red-500': errors.stock }"
            placeholder="Ej: 10"
            required
          />
          <p v-if="errors.stock" class="mt-1 text-sm text-red-600">{{ errors.stock }}</p>
        </div>

        <!-- Categoría y Proveedor -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">Categoría</label>
            <select
              v-model="form.category_id"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
              :class="{ 'border-red-500': errors.category_id }"
              required
            >
              <option value="" disabled>Selecciona una categoría</option>
              <option v-for="category in categories" :key="category.id" :value="category.id">
                {{ category.name }}
              </option>
            </select>
            <p v-if="errors.category_id" class="mt-1 text-sm text-red-600">{{ errors.category_id }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Proveedor</label>
            <select
              v-model="form.supplier_id"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
              :class="{ 'border-red-500': errors.supplier_id }"
            >
              <option value="" disabled>Selecciona un proveedor (opcional)</option>
              <option v-for="supplier in suppliers" :key="supplier.id" :value="supplier.id">
                {{ supplier.name }}
              </option>
            </select>
            <p v-if="errors.supplier_id" class="mt-1 text-sm text-red-600">{{ errors.supplier_id }}</p>
          </div>
        </div>

        <!-- Botones -->
        <div class="flex justify-end space-x-3">
          <button
            type="button"
            @click="cancel"
            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400"
          >
            Cancelar
          </button>
          <button
            type="submit"
            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700"
            :disabled="isSubmitting"
          >
            {{ isSubmitting ? 'Guardando...' : 'Guardar Producto' }}
          </button>
        </div>
      </form>

      <!-- Modal para escanear código de barras -->
      <div v-if="showScanner" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-lg">
          <h2 class="text-xl font-semibold mb-4">Escanear Código de Barras</h2>
          <div id="barcode-scanner" class="w-full h-64 bg-gray-200"></div>
          <div class="mt-4 flex justify-end space-x-2">
            <button
              @click="stopBarcodeScanner"
              class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400"
            >
              Cancelar
            </button>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { router } from '@inertiajs/vue3'
import axios from 'axios'
import Quagga from 'quagga'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const form = ref({
  name: '',
  barcode: '',
  description: '',
  purchase_price: 0,
  sale_price: 0,
  image: null,
  stock: 0,
  category_id: '',
  supplier_id: '',
})

const errors = ref({})
const categories = ref([])
const suppliers = ref([])
const isSubmitting = ref(false)
const showScanner = ref(false)

onMounted(async () => {
  // Obtener categorías
  try {
    const categoriesResponse = await axios.get('/categories')
    categories.value = categoriesResponse.data
  } catch (error) {
    console.error(error)
    alert('Error al cargar las categorías')
  }

  // Obtener proveedores
  try {
    const suppliersResponse = await axios.get('/suppliers')
    suppliers.value = suppliersResponse.data
  } catch (error) {
    console.error(error)
    alert('Error al cargar los proveedores')
  }
})

const handleImageUpload = (event) => {
  form.value.image = event.target.files[0]
}

const startBarcodeScanner = () => {
  showScanner.value = true
  Quagga.init({
    inputStream: {
      name: 'Live',
      type: 'LiveStream',
      target: document.querySelector('#barcode-scanner'),
      constraints: {
        facingMode: 'environment', // Usa la cámara trasera
      },
    },
    decoder: {
      readers: ['ean_reader', 'upc_reader', 'code_128_reader'], // Tipos de códigos de barras soportados
    },
  }, (err) => {
    if (err) {
      console.error(err)
      alert('Error al iniciar el escáner de códigos de barras')
      showScanner.value = false
      return
    }
    Quagga.start()
  })

  Quagga.onDetected((result) => {
    if (result && result.codeResult) {
      form.value.barcode = result.codeResult.code
      stopBarcodeScanner()
      alert('Código de barras escaneado con éxito')
    }
  })
}

const stopBarcodeScanner = () => {
  Quagga.stop()
  showScanner.value = false
}

const validateForm = () => {
  errors.value = {}

  if (!form.value.name) {
    errors.value.name = 'El nombre del producto es obligatorio'
  }
  if (form.value.purchase_price <= 0) {
    errors.value.purchase_price = 'El precio de compra debe ser mayor a 0'
  }
  if (form.value.sale_price <= 0) {
    errors.value.sale_price = 'El precio de venta debe ser mayor a 0'
  }
  if (form.value.sale_price < form.value.purchase_price) {
    errors.value.sale_price = 'El precio de venta debe ser mayor o igual al precio de compra'
  }
  if (form.value.stock < 0) {
    errors.value.stock = 'El stock no puede ser negativo'
  }
  if (!form.value.category_id) {
    errors.value.category_id = 'Debes seleccionar una categoría'
  }

  return Object.keys(errors.value).length === 0
}

const submitProduct = async () => {
  if (!validateForm()) {
    alert('Por favor, corrige los errores en el formulario')
    return
  }

  isSubmitting.value = true
  const formData = new FormData()
  formData.append('name', form.value.name)
  formData.append('barcode', form.value.barcode)
  formData.append('description', form.value.description)
  formData.append('purchase_price', form.value.purchase_price)
  formData.append('sale_price', form.value.sale_price)
  formData.append('stock', form.value.stock)
  formData.append('category_id', form.value.category_id)
  if (form.value.supplier_id) {
    formData.append('supplier_id', form.value.supplier_id)
  }
  if (form.value.image) {
    formData.append('image', form.value.image)
  }

  try {
    await axios.post('/products', formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    })
    alert('Producto agregado con éxito')
    router.visit('/products')
  } catch (error) {
    if (error.response && error.response.data.errors) {
      errors.value = error.response.data.errors
      alert('Error al agregar el producto. Revisa los campos.')
    } else {
      alert('Error al agregar el producto')
    }
    console.error(error)
  } finally {
    isSubmitting.value = false
  }
}

const cancel = () => {
  router.visit('/products')
}

onUnmounted(() => {
  if (showScanner.value) {
    Quagga.stop()
  }
})
</script>