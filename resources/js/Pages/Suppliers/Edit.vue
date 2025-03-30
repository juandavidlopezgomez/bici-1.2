<!-- resources/js/Pages/Suppliers/Edit.vue -->
<template>
  <AuthenticatedLayout>
    <template #header>Editar Proveedor</template>

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
          <!-- Información del proveedor -->
          <div>
            <div class="mb-4">
              <label for="name" class="block text-sm font-medium text-gray-700">Nombre del Proveedor*</label>
              <input 
                id="name" 
                v-model="form.name" 
                type="text" 
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200"
                required
              />
            </div>

            <div class="mb-4">
              <label for="contact_person" class="block text-sm font-medium text-gray-700">Persona de Contacto</label>
              <input 
                id="contact_person" 
                v-model="form.contact_person" 
                type="text" 
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200"
              />
            </div>

            <div class="mb-4">
              <label for="email" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
              <input 
                id="email" 
                v-model="form.email" 
                type="email" 
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200"
              />
            </div>
          </div>

          <div>
            <div class="mb-4">
              <label for="phone" class="block text-sm font-medium text-gray-700">Teléfono</label>
              <input 
                id="phone" 
                v-model="form.phone" 
                type="text" 
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200"
              />
            </div>

            <div class="mb-4">
              <label for="address" class="block text-sm font-medium text-gray-700">Dirección</label>
              <textarea 
                id="address" 
                v-model="form.address" 
                rows="3"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200"
              ></textarea>
            </div>

            <div class="mb-4">
              <div class="flex items-center">
                <input 
                  id="active" 
                  v-model="form.active" 
                  type="checkbox" 
                  class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded"
                />
                <label for="active" class="ml-2 block text-sm text-gray-700">Activo</label>
              </div>
            </div>
          </div>
        </div>

        <!-- Botones de acción -->
        <div class="flex justify-end space-x-3 pt-6 border-t">
          <Link 
            href="/suppliers" 
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
            <span v-else>Actualizar Proveedor</span>
          </button>
        </div>
      </form>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

// Props
const props = defineProps({
  supplier: Object,
  errors: Object
});

// Form data
const form = useForm({
  name: props.supplier.name,
  contact_person: props.supplier.contact_person || '',
  email: props.supplier.email || '',
  phone: props.supplier.phone || '',
  address: props.supplier.address || '',
  active: props.supplier.active
});

// UI state
const isSubmitting = ref(false);

// Methods
const submit = () => {
  isSubmitting.value = true;
  
  form.put(`/suppliers/${props.supplier.id}`, {
    onSuccess: () => {
      isSubmitting.value = false;
    },
    onError: () => {
      isSubmitting.value = false;
    }
  });
};
</script>