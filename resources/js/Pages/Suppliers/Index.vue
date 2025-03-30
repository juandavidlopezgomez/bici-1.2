<!-- resources/js/Pages/Suppliers/Index.vue -->
<template>
  <AuthenticatedLayout>
    <template #header>Gestión de Proveedores</template>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
      <div class="p-6 bg-white border-b border-gray-200 flex justify-between items-center">
        <h2 class="text-xl font-semibold text-gray-800">Listado de Proveedores</h2>
        <Link
          :href="route('suppliers.create')"
          class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
          </svg>
          Nuevo Proveedor
        </Link>
      </div>

      <!-- Mensajes Flash -->
      <div v-if="$page.props.flash && $page.props.flash.message" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 mx-6">
        <p>{{ $page.props.flash.message }}</p>
      </div>
      <div v-if="$page.props.flash && $page.props.flash.error" class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 mx-6">
        <p>{{ $page.props.flash.error }}</p>
      </div>

      <!-- Tabla de proveedores -->
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contacto</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teléfono</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="supplier in suppliers" :key="supplier.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ supplier.id }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ supplier.name }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ supplier.contact_person || '—' }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ supplier.phone || '—' }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ supplier.email || '—' }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm">
                <span 
                  :class="supplier.active 
                    ? 'bg-green-100 text-green-800' 
                    : 'bg-red-100 text-red-800'" 
                  class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                >
                  {{ supplier.active ? 'Activo' : 'Inactivo' }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <div class="flex space-x-2">
                  <Link
                    :href="route('suppliers.edit', supplier.id)"
                    class="text-indigo-600 hover:text-indigo-900"
                  >
                    Editar
                  </Link>
                  <button 
                    @click="confirmDelete(supplier)" 
                    class="text-red-600 hover:text-red-900"
                  >
                    Eliminar
                  </button>
                </div>
              </td>
            </tr>
            <tr v-if="suppliers.length === 0">
              <td colspan="7" class="px-6 py-4 text-center text-sm font-medium text-gray-500">
                No hay proveedores registrados
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Modal de confirmación de eliminación -->
    <div v-if="showDeleteModal" class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="showDeleteModal = false"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
          <div>
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
              <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
            </div>
            <div class="mt-3 text-center sm:mt-5">
              <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                Eliminar Proveedor
              </h3>
              <div class="mt-2">
                <p class="text-sm text-gray-500">
                  ¿Estás seguro de que deseas eliminar el proveedor <span class="font-semibold">{{ supplierToDelete?.name }}</span>? Esta acción no se puede deshacer.
                </p>
              </div>
            </div>
          </div>
          <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
            <button 
              type="button" 
              class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:col-start-2 sm:text-sm"
              @click="deleteSupplier"
            >
              Eliminar
            </button>
            <button 
              type="button" 
              class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:col-start-1 sm:text-sm"
              @click="showDeleteModal = false"
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
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

// Props
const props = defineProps({
  suppliers: Array
});

// Estado del modal de confirmación
const showDeleteModal = ref(false);
const supplierToDelete = ref(null);

// Métodos
const confirmDelete = (supplier) => {
  supplierToDelete.value = supplier;
  showDeleteModal.value = true;
};

const deleteSupplier = () => {
  router.delete(`/suppliers/${supplierToDelete.value.id}`, {
    onSuccess: () => {
      showDeleteModal.value = false;
      supplierToDelete.value = null;
    }
  });
};
</script>