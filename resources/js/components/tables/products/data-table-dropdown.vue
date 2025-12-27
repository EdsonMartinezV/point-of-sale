<script setup lang="ts">
import { MoreHorizontal } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, /* DropdownMenuLabel, DropdownMenuSeparator, */ DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { useProductsStore } from '@/stores/products';

const props = defineProps<{
  productId: string | number
}>();

const productsStore = useProductsStore();

const setProductToEdit = (id: string | number) => {
  productsStore.setIdToEdit(id);
};

const setProductToDelete = (id: string | number) => {
  productsStore.setIdToDelete(id);
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
      <DropdownMenuItem @click="setProductToEdit(props.productId)">Editar</DropdownMenuItem>
      <DropdownMenuItem @click="setProductToDelete(props.productId)">Eliminar</DropdownMenuItem>
    </DropdownMenuContent>
  </DropdownMenu>
</template>
