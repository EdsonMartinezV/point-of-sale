<script setup lang="ts">
import { MoreHorizontal } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, /* DropdownMenuLabel, DropdownMenuSeparator, */ DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { usePurchasesStore } from '@/stores/purchases';

const props = defineProps<{
  purchaseId: string | number
}>();

const purchasesStore = usePurchasesStore();

const setPurchaseToShow = (id: string | number) => {
  purchasesStore.setIdToShow(id);
};

/* const setPurchaseToEdit = (id: string | number) => {
  purchasesStore.setIdToEdit(id);
};

const setPurchaseToDelete = (id: string | number) => {
  purchasesStore.setIdToDelete(id);
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
      <DropdownMenuItem @click="setPurchaseToShow(props.purchaseId)">Ver</DropdownMenuItem>
      <!-- <DropdownMenuItem @click="setPurchaseToEdit(props.purchaseId)">Editar</DropdownMenuItem>
      <DropdownMenuItem @click="setPurchaseToDelete(props.purchaseId)">Eliminar</DropdownMenuItem> -->
    </DropdownMenuContent>
  </DropdownMenu>
</template>
