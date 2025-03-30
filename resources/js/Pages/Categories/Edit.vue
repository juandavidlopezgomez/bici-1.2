<!-- resources/js/Pages/Categories/Edit.vue -->
<template>
  <AuthenticatedLayout>
    <template #header>Editar Categoría</template>

    <div class="py-6">
      <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <form @submit.prevent="submit">
              <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-700">Nombre de la categoría*</label>
                <input 
                  id="name" 
                  v-model="form.name" 
                  type="text" 
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200"
                  required
                />
                <div v-if="form.errors.name" class="text-red-500 text-sm mt-1">{{ form.errors.name }}</div>
              </div>

              <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700">Descripción</label>
                <textarea 
                  id="description" 
                  v-model="form.description" 
                  rows="3"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200"
                ></textarea>
                <div v-if="form.errors.description" class="text-red-500 text-sm mt-1">{{ form.errors.description }}</div>
              </div>

              <div class="mb-6">
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

              <div class="flex items-center justify-end">
                <Link
                  :href="route('categories.index')"
                  class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded mr-2"
                >
                  Cancelar
                </Link>
                <button
                  type="submit"
                  class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                  :disabled="form.processing"
                >
                  Actualizar
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
  category: Object
});

const form = useForm({
  name: props.category.name,
  description: props.category.description || '',
  active: props.category.active
});

const submit = () => {
  form.put(route('categories.update', props.category.id));
};
</script>