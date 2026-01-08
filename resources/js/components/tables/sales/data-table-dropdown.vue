<script setup lang="ts">
import { MoreHorizontal } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, /* DropdownMenuLabel, DropdownMenuSeparator, */ DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { useSalesStore } from '@/stores/sales';

const props = defineProps<{
  saleId: string | number
}>();

const salesStore = useSalesStore();

const setSaleToShow = (id: string | number) => {
  salesStore.setIdToShow(id);
};

/* const setSaleToEdit = (id: string | number) => {
  salesStore.setIdToEdit(id);
};

const setSaleToDelete = (id: string | number) => {
  salesStore.setIdToDelete(id);
}; */
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
      <DropdownMenuItem @click="setSaleToShow(props.saleId)">Ver</DropdownMenuItem>
      <!-- <DropdownMenuItem @click="setSaleToEdit(props.saleId)">Editar</DropdownMenuItem>
      <DropdownMenuItem @click="setSaleToDelete(props.saleId)">Eliminar</DropdownMenuItem> -->
    </DropdownMenuContent>
  </DropdownMenu>
</template>
