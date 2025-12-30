<script setup lang="ts">
import { MoreHorizontal } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, /* DropdownMenuLabel, DropdownMenuSeparator, */ DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { useMeasureUnitsStore } from '@/stores/measureUnits';

const props = defineProps<{
  measureUnitId: string | number
}>();

const measureUnitsStore = useMeasureUnitsStore();

const setMeasureUnitToEdit = (id: string | number) => {
  measureUnitsStore.setIdToEdit(id);
};

const setMeasureUnitToDelete = (id: string | number) => {
  measureUnitsStore.setIdToDelete(id);
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
      <DropdownMenuItem @click="setMeasureUnitToEdit(props.measureUnitId)">Editar</DropdownMenuItem>
      <DropdownMenuItem @click="setMeasureUnitToDelete(props.measureUnitId)">Eliminar</DropdownMenuItem>
    </DropdownMenuContent>
  </DropdownMenu>
</template>
