<script setup lang="ts">
import { MoreHorizontal } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, /* DropdownMenuLabel, DropdownMenuSeparator, */ DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { useCategoriesStore } from '@/stores/categories';

const props = defineProps<{
  categoryId: string | number
}>();

const categoriesStore = useCategoriesStore();

const setCategoryToEdit = (id: string | number) => {
  categoriesStore.setIdToEdit(id);
};

const setCategoryToDelete = (id: string | number) => {
  categoriesStore.setIdToDelete(id);
};
</script>

<template>
  <DropdownMenu>
    <DropdownMenuTrigger as-child>
      <Button variant="ghost" class="w-8 h-8 p-0">
        <!-- <span class="sr-only">Opciones</span> -->
        <MoreHorizontal class="w-4 h-4" />
      </Button>
    </DropdownMenuTrigger>
    <DropdownMenuContent align="end">
      <!-- <DropdownMenuLabel>Opciones</DropdownMenuLabel>
      <DropdownMenuSeparator /> -->
      <DropdownMenuItem @click="setCategoryToEdit(props.categoryId)">Editar</DropdownMenuItem>
      <DropdownMenuItem @click="setCategoryToDelete(props.categoryId)">Eliminar</DropdownMenuItem>
    </DropdownMenuContent>
  </DropdownMenu>
</template>
