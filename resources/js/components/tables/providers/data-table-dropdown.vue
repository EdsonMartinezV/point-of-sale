<script setup lang="ts">
import { MoreHorizontal } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, /* DropdownMenuLabel, DropdownMenuSeparator, */ DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { useProvidersStore } from '@/stores/providers';

const props = defineProps<{
  providerId: string | number
}>();

const providersStore = useProvidersStore();

const setProviderToEdit = (id: string | number) => {
  providersStore.setIdToEdit(id);
};

const setProviderToDelete = (id: string | number) => {
  providersStore.setIdToDelete(id);
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
      <DropdownMenuItem @click="setProviderToEdit(props.providerId)">Editar</DropdownMenuItem>
      <DropdownMenuItem @click="setProviderToDelete(props.providerId)">Eliminar</DropdownMenuItem>
    </DropdownMenuContent>
  </DropdownMenu>
</template>
