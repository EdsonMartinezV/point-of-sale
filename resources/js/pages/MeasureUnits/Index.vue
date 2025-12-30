<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Form } from '@inertiajs/vue3';
import { ref } from 'vue';

import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';
import DataTable from '@/components/ui/data-table.vue';
import {
  AlertDialog,
  AlertDialogAction,
  AlertDialogCancel,
  AlertDialogContent,
  AlertDialogDescription,
  AlertDialogFooter,
  AlertDialogHeader,
  AlertDialogTitle,
} from '@/components/ui/alert-dialog'
import InputError from '@/components/InputError.vue';
import { LoaderCircle } from 'lucide-vue-next';

import type { MeasureUnit } from '@/types/main';
import MeasureUnitController from '@/actions/App/Http/Controllers/MeasureUnitController';
import { columns } from '@/components/tables/measureUnits/columns';
import { useMeasureUnitsStore } from '@/stores/measureUnits';

const props = defineProps<{
  measureUnits: MeasureUnit[];
}>();

const measureUnitsStore = useMeasureUnitsStore();
const measureUnitToEdit = ref<MeasureUnit | null>(null);
const showDestroyAlert = ref<boolean>(false);
const showMessageAlert = ref<boolean>(false);

const handleMainFormFinish = () => {
    measureUnitsStore.clearIdToEdit();
    showMessageAlert.value = true;
};

const handleDestroyFormFinish = () => {
    measureUnitsStore.clearIdToDelete();
    showMessageAlert.value = true;
};

measureUnitsStore.$subscribe((mutation, state) => {
    if (state.idToEdit !== null) {
        const measureUnit = props.measureUnits.find(mU => mU.id === state.idToEdit) || null;
        measureUnitToEdit.value = measureUnit;
    } else {
        measureUnitToEdit.value = null;
    }

    if (state.idToDelete !== 0) {
        showDestroyAlert.value = true;
    } else {
        showDestroyAlert.value = false;
    }
});
</script>

<template>
    <Head title="Presentaciones" />

    <AppLayout>
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <!-- Form box -->
            <div class="relative overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border p-4 space-y-4">
                <h2 class="font-medium text-lg">Registrar nueva presentación</h2>
                <Form
                    v-bind="measureUnitsStore.idToEdit === null ? MeasureUnitController.store.form() : MeasureUnitController.update.form(measureUnitsStore.idToEdit)"
                    :reset-on-success="true"
                    :onSuccess="handleMainFormFinish"
                    v-slot="{ errors, processing }"
                    class="flex flex-col gap-4 w-full mt-4"
                >
                    <div class="flex gap-4 w-full items-start">
                        <div class="grid gap-2 w-full">
                            <Label for="name">Nombre<span class="text-red-500">*</span></Label>
                            <Input
                                id="name"
                                :model-value="measureUnitToEdit?.name"
                                type="text"
                                required
                                autofocus
                                :tabindex="1"
                                autocomplete="name"
                                name="name"
                                placeholder="Nombre de la presentación"
                            />
                            <InputError :message="errors.name" />
                        </div>
                        <div class="grid gap-2 w-full">
                            <Label for="abbreviation">Abreviatura</Label>
                            <Input
                                id="abbreviation"
                                :model-value="measureUnitToEdit?.abbreviation"
                                type="text"
                                :tabindex="2"
                                autocomplete="abbreviation"
                                name="abbreviation"
                                placeholder="Abreviatura de la presentación"
                            />
                            <InputError :message="errors.abbreviation" />
                        </div>
                    </div>

                    <Button
                        type="submit"
                        class="w-full"
                        :tabindex="3"
                        :disabled="processing"
                    >
                        <LoaderCircle
                            v-if="processing"
                            class="h-4 w-4 animate-spin"
                        />
                        {{ measureUnitsStore.idToEdit === null ? 'Guardar' : 'Actualizar' }}
                    </Button>
                </Form>
            </div>

            <!-- List box -->
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border p-4">
                <h2 class="font-medium text-lg">Presentaciones</h2>
                <DataTable :columns="columns" :data="props.measureUnits"/>
            </div>

            <!-- Dialog alert -->
            <AlertDialog v-model:open="showDestroyAlert">
                <AlertDialogContent>
                    <AlertDialogHeader>
                        <AlertDialogTitle>¿Estás seguro de eliminar esta presentación?</AlertDialogTitle>
                        <AlertDialogDescription>
                            Esta acción no se puede deshacer<br>Esto eliminará permanentemente la presentación seleccionada
                        </AlertDialogDescription>
                    </AlertDialogHeader>
                    <AlertDialogFooter>
                        <AlertDialogCancel @click="measureUnitsStore.clearIdToDelete()">Cancelar</AlertDialogCancel>
                        <Form
                            v-bind="MeasureUnitController.destroy.form({ id: measureUnitsStore.idToDelete })"
                            :reset-on-success="true"
                            :onFinish="handleDestroyFormFinish"
                        >
                          <Button type="submit" class="bg-red-600 hover:bg-red-700 focus:ring-red-500">
                            Eliminar
                          </Button>
                        </Form>
                    </AlertDialogFooter>
                </AlertDialogContent>
            </AlertDialog>

            <!-- Message alert -->
            <AlertDialog v-model:open="showMessageAlert">
                <AlertDialogContent>
                    <AlertDialogHeader>
                        <AlertDialogTitle>{{ $page.props.flash.success ? 'Listo!' : 'Error' }}</AlertDialogTitle>
                        <AlertDialogDescription>
                            {{ $page.props.flash.success || $page.props.flash.error }}
                        </AlertDialogDescription>
                    </AlertDialogHeader>
                    <AlertDialogFooter>
                        <AlertDialogAction @click="measureUnitsStore.clearIdToDelete()">Aceptar</AlertDialogAction>
                    </AlertDialogFooter>
                </AlertDialogContent>
            </AlertDialog>
        </div>
    </AppLayout>
</template>