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
import {
  Command,
  CommandEmpty,
  CommandGroup,
  CommandInput,
  CommandItem,
  CommandList,
} from '@/components/ui/command'
import {
  Popover,
  PopoverContent,
  PopoverTrigger,
} from '@/components/ui/popover'
import InputError from '@/components/InputError.vue';
import { CheckIcon, ChevronsUpDownIcon, LoaderCircle } from 'lucide-vue-next';

import type { Category, MeasureUnit, Product } from '@/types/main';
import ProductController from '@/actions/App/Http/Controllers/ProductController';
import { columns } from '@/components/tables/products/columns';
import { useProductsStore } from '@/stores/products';
import { cn } from '@/lib/utils';
import { Checkbox } from '@/components/ui/checkbox';

const props = defineProps<{
  products: Product[],
  categories: Category[],
  measureUnits: MeasureUnit[],
}>();

const productsStore = useProductsStore();
const productToEdit = ref<Product | null>(null);
const showDestroyAlert = ref<boolean>(false);
const showMessageAlert = ref<boolean>(false);
const soldByRetail = ref<boolean>(false);

/* Combo boxes */
const openCategoryComboBox = ref<boolean>(false);
const openMeasureUnitComboBox = ref<boolean>(false);
const openRetailMeasureUnitComboBox = ref<boolean>(false);

const selectedCategoryId = ref<number | null | ''>(null);
const selectedMeasureUnitId = ref<number | null | ''>(null);
const selectedRetailMeasureUnitId = ref<number | null | ''>(null);

const handleMainFormFinish = () => {
    productsStore.clearIdToEdit();
    showMessageAlert.value = true;
    selectedCategoryId.value = null;
    selectedMeasureUnitId.value = null;
    selectedRetailMeasureUnitId.value = null;
    soldByRetail.value = false;
};

const handleDestroyFormFinish = () => {
    productsStore.clearIdToDelete();
    showMessageAlert.value = true;
};

productsStore.$subscribe((mutation, state) => {
    if (state.idToEdit !== null) {
        const product = props.products.find(p => p.id === state.idToEdit) || null;
        console.log('Editing product:', product);
        productToEdit.value = product;
        soldByRetail.value = productToEdit.value?.sold_by_retail ?? false;
        selectedCategoryId.value = productToEdit.value?.category?.id ?? null;
        selectedMeasureUnitId.value = productToEdit.value?.measure_unit?.id ?? null;
        selectedRetailMeasureUnitId.value = productToEdit.value?.retail_measure_unit?.id ?? null;
        console.log({
            soldByRetail: soldByRetail.value,
            selectedCategoryId: selectedCategoryId.value,
            selectedMeasureUnitId: selectedMeasureUnitId.value,
            selectedRetailMeasureUnitId: selectedRetailMeasureUnitId.value,
        })
    } else {
        productToEdit.value = null;
    }

    if (state.idToDelete !== 0) {
        showDestroyAlert.value = true;
    } else {
        showDestroyAlert.value = false;
    }
});
</script>

<template>
    <Head title="Productos" />

    <AppLayout>
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <!-- Form box -->
            <div class="relative overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border p-4 space-y-4">
                <h2 class="font-medium text-lg">Registrar nuevo producto</h2>
                <Form
                    v-bind="productsStore.idToEdit === null ? ProductController.store.form() : ProductController.update.form(productsStore.idToEdit)"
                    :reset-on-success="true"
                    :onSuccess="handleMainFormFinish"
                    v-slot="{ errors, processing }"
                    :transform="data => {
                        return {
                            ...data,
                            category_id: selectedCategoryId || null,
                            measure_unit_id: selectedMeasureUnitId || null,
                            retail_measure_unit_id: soldByRetail ? selectedRetailMeasureUnitId || null : null,
                            sold_by_retail: soldByRetail ? true : false,
                        }
                    }"
                    class="flex flex-col gap-4 w-full mt-4"
                >
                    <div class="flex gap-4 w-full items-start">
                        <div class="grid gap-2 w-full">
                            <Label for="name">Nombre<span class="text-red-500">*</span></Label>
                            <Input
                                id="name"
                                :model-value="productToEdit?.name"
                                type="text"
                                required
                                autofocus
                                :tabindex="1"
                                autocomplete="name"
                                name="name"
                                placeholder="Nombre del producto"
                            />
                            <InputError :message="errors.name" />
                        </div>
                        <div class="flex flex-col gap-2 items-center justify-center w-full self-center">
                            <Label for="sold_by_retail" class="flex items-center justify-center space-x-3">
                                <Checkbox v-model="soldByRetail" :value:boolean="true" id="sold_by_retail" name="sold_by_retail" :tabindex="2"/>
                                 <!-- <input type="checkbox" v-model="soldByRetail" id="sold_by_retail" name="sold_by_retail" :tabindex="2"/> -->
                                <span>Vendido al menudeo</span>
                            </Label>
                            <InputError :message="errors.sold_by_retail" />
                        </div>
                        <!-- Category combo box -->
                        <div class="grid gap-2 w-full">
                            <Label for="category_id">Categoría</Label>
                            <Popover v-model:open="openCategoryComboBox">
                                <PopoverTrigger as-child>
                                <Button
                                    id="category_id"
                                    variant="outline"
                                    role="combobox"
                                    :aria-expanded="openCategoryComboBox"
                                    class="w-[200px] justify-between"
                                    :tabindex="3"
                                    name="category_id"
                                >
                                    {{
                                    selectedCategoryId
                                        ? props.categories.find(category => category.id === selectedCategoryId)?.name
                                        : 'Escoge una categoría...'
                                    }}
                                    <ChevronsUpDownIcon class="ml-2 h-4 w-4 shrink-0 opacity-50" />
                                </Button>
                                </PopoverTrigger>
                                <PopoverContent class="w-[200px] p-0">
                                <Command>
                                    <CommandInput placeholder="Buscar categoría..." />
                                    <CommandList>
                                    <CommandEmpty>Sin resultados</CommandEmpty>
                                    <CommandGroup>
                                        <CommandItem
                                            v-for="category in props.categories"
                                            :key="category.id"
                                            :value="category.id"
                                            @select="() => {
                                                selectedCategoryId = selectedCategoryId === category.id ? '' : category.id
                                                openCategoryComboBox = false
                                            }"
                                        >
                                            <CheckIcon
                                                :class="cn(
                                                'mr-2 h-4 w-4',
                                                selectedCategoryId === category.id ? 'opacity-100' : 'opacity-0',
                                                )"
                                            />
                                            {{ category.name }}
                                        </CommandItem>
                                    </CommandGroup>
                                    </CommandList>
                                </Command>
                                </PopoverContent>
                            </Popover>
                            <InputError :message="errors.category_id" />
                        </div>
                        <!-- Measure unit combo box -->
                        <div class="grid gap-2 w-full">
                            <Label for="measure_unit_id">Presentación al mayoreo</Label>
                            <Popover v-model:open="openMeasureUnitComboBox">
                                <PopoverTrigger as-child>
                                <Button
                                    id="measure_unit_id"
                                    variant="outline"
                                    role="combobox"
                                    :aria-expanded="openMeasureUnitComboBox"
                                    class="w-[200px] justify-between"
                                    :tabindex="4"
                                    name="measure_unit_id"
                                >
                                    {{
                                    selectedMeasureUnitId
                                        ? props.measureUnits.find(measureUnit => measureUnit.id === selectedMeasureUnitId)?.name
                                        : 'Escoge una unidad...'
                                    }}
                                    <ChevronsUpDownIcon class="ml-2 h-4 w-4 shrink-0 opacity-50" />
                                </Button>
                                </PopoverTrigger>
                                <PopoverContent class="w-[200px] p-0">
                                <Command>
                                    <CommandInput placeholder="Buscar unidad de medida..." />
                                    <CommandList>
                                    <CommandEmpty>Sin resultados</CommandEmpty>
                                    <CommandGroup>
                                        <CommandItem
                                            v-for="measureUnit in props.measureUnits"
                                            :key="measureUnit.id"
                                            :value="measureUnit.id"
                                            @select="() => {
                                                selectedMeasureUnitId = selectedMeasureUnitId === measureUnit.id ? '' : measureUnit.id
                                                openMeasureUnitComboBox = false
                                            }"
                                        >
                                            <CheckIcon
                                                :class="cn(
                                                'mr-2 h-4 w-4',
                                                selectedMeasureUnitId === measureUnit.id ? 'opacity-100' : 'opacity-0',
                                                )"
                                            />
                                            {{ measureUnit.name }}
                                        </CommandItem>
                                    </CommandGroup>
                                    </CommandList>
                                </Command>
                                </PopoverContent>
                            </Popover>
                            <InputError :message="errors.measure_unit_id" />
                        </div>
                        <!-- Retail measure unit combo box -->
                        <div v-if="soldByRetail" class="grid gap-2 w-full">
                            <Label for="retail_measure_unit_id">Presentación al menudeo</Label>
                            <Popover v-model:open="openRetailMeasureUnitComboBox">
                                <PopoverTrigger as-child>
                                <Button
                                    id="retail_measure_unit_id"
                                    variant="outline"
                                    role="combobox"
                                    :aria-expanded="openRetailMeasureUnitComboBox"
                                    class="w-[200px] justify-between"
                                    :tabindex="5"
                                    name="retail_measure_unit_id"
                                >
                                    {{
                                    selectedRetailMeasureUnitId
                                        ? props.measureUnits.find(measureUnit => measureUnit.id === selectedRetailMeasureUnitId)?.name
                                        : 'Escoge una unidad...'
                                    }}
                                    <ChevronsUpDownIcon class="ml-2 h-4 w-4 shrink-0 opacity-50" />
                                </Button>
                                </PopoverTrigger>
                                <PopoverContent class="w-[200px] p-0">
                                <Command>
                                    <CommandInput placeholder="Buscar unidad de medida..." />
                                    <CommandList>
                                    <CommandEmpty>Sin resultados</CommandEmpty>
                                    <CommandGroup>
                                        <CommandItem
                                            v-for="measureUnit in props.measureUnits"
                                            :key="measureUnit.id"
                                            :value="measureUnit.id"
                                            @select="() => {
                                                selectedRetailMeasureUnitId = selectedRetailMeasureUnitId === measureUnit.id ? '' : measureUnit.id
                                                openRetailMeasureUnitComboBox = false
                                            }"
                                        >
                                            <CheckIcon
                                                :class="cn(
                                                'mr-2 h-4 w-4',
                                                selectedRetailMeasureUnitId === measureUnit.id ? 'opacity-100' : 'opacity-0',
                                                )"
                                            />
                                            {{ measureUnit.name }}
                                        </CommandItem>
                                    </CommandGroup>
                                    </CommandList>
                                </Command>
                                </PopoverContent>
                            </Popover>
                            <InputError :message="errors.retail_measure_unit_id" />
                        </div>
                    </div>

                    <Button
                        type="submit"
                        class="w-full"
                        :tabindex="6"
                        :disabled="processing"
                    >
                        <LoaderCircle
                            v-if="processing"
                            class="h-4 w-4 animate-spin"
                        />
                        {{ productsStore.idToEdit === null ? 'Guardar' : 'Actualizar' }}
                    </Button>
                </Form>
            </div>

            <!-- List box -->
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border p-4">
                <h2 class="font-medium text-lg">Productos</h2>
                <DataTable :columns="columns" :data="props.products"/>
            </div>

            <!-- Dialog alert -->
            <AlertDialog v-model:open="showDestroyAlert">
                <AlertDialogContent>
                    <AlertDialogHeader>
                        <AlertDialogTitle>¿Estás seguro de eliminar este producto?</AlertDialogTitle>
                        <AlertDialogDescription>
                            Esta acción no se puede deshacer<br>Esto eliminará permanentemente el producto seleccionado
                        </AlertDialogDescription>
                    </AlertDialogHeader>
                    <AlertDialogFooter>
                        <AlertDialogCancel @click="productsStore.clearIdToDelete()">Cancelar</AlertDialogCancel>
                        <Form
                            v-bind="ProductController.destroy.form({ id: productsStore.idToDelete })"
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
                        <AlertDialogAction @click="productsStore.clearIdToDelete()">Aceptar</AlertDialogAction>
                    </AlertDialogFooter>
                </AlertDialogContent>
            </AlertDialog>
        </div>
    </AppLayout>
</template>