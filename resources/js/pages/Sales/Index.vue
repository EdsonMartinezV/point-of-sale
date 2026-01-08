<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Form } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';
import DataTable from '@/components/ui/data-table.vue';
import {
  AlertDialog,
  AlertDialogAction,
  AlertDialogContent,
  AlertDialogDescription,
  AlertDialogFooter,
  AlertDialogHeader,
  AlertDialogTitle,
} from '@/components/ui/alert-dialog'
import {
    NumberField,
    NumberFieldContent,
    NumberFieldInput
} from '@/components/ui/number-field';
import { Separator } from '@/components/ui/separator';
import InputError from '@/components/InputError.vue';
import { LoaderCircle, XCircle } from 'lucide-vue-next';

import { FormSaleItem, type Product, type Sale } from '@/types/main';
import SaleController from '@/actions/App/Http/Controllers/SaleController';
import ProductController from '@/actions/App/Http/Controllers/ProductController';
import { columns } from '@/components/tables/sales/columns';
import { useSalesStore } from '@/stores/sales';
import { Checkbox } from '@/components/ui/checkbox';

const props = defineProps<{
  sales: Sale[];
}>();

const salesStore = useSalesStore();
const saleToEdit = ref<Sale | null>(null);
const showMessageAlert = ref<boolean>(false);

/* Search */
const q = ref<string>('');
const searchProducts = ref<any[]>([]);
const loading = ref<boolean>(false);
const error = ref<string | null>(null);

/* Form */
const selectedProducts = ref<Product[]>([]);
const formSaleItems = ref<FormSaleItem[]>([]);

/* Money */
const total = computed(() => {
    return 0;
});
const formattedTotal = computed(() => {
    return new Intl.NumberFormat('es-MX', {
        style: 'currency',
        currency: 'MXN',
    }).format(total.value)
})
const paidAmount = ref<number | undefined>(0);
const changeAmount = computed(() => {
    return total.value - (paidAmount.value ?? 0);
});
const formattedChangeAmount = computed(() => {
    return new Intl.NumberFormat('es-MX', {
        style: 'currency',
        currency: 'MXN',
    }).format(changeAmount.value)
})

const handleMainFormFinish = () => {
    salesStore.clearIdToEdit();
    showMessageAlert.value = true;
};

const addProduct = (productId: number) => {
    const exists = selectedProducts.value.find(product => product.id = productId) ? true : false;
    if (!exists) {
        const product = searchProducts.value.find(product => product.id = productId);
        selectedProducts.value.push(product);
        formSaleItems.value.push({
            quantity: 0,
            is_retail_sale: false,
            selected_percentage: 'first_wholesome_percentage',
            total: 0,
            product_id: productId,
        });
        searchProducts.value = [];
        console.log(selectedProducts.value, formSaleItems.value);
    } else {
        console.log('no')
    }
}

const removeProduct = (productId: number) => {
    selectedProducts.value = selectedProducts.value.filter(product => product.id !== productId);
}

salesStore.$subscribe((mutation, state) => {
    if (state.idToEdit !== null) {
        const sale = props.sales.find(s => s.id === state.idToEdit) || null;
        saleToEdit.value = sale;
    } else {
        saleToEdit.value = null;
    }

    /* if (state.idToDelete !== 0) {
        showDestroyAlert.value = true;
    } else {
        showDestroyAlert.value = false;
    } */
});

const search = async () => {
    loading.value = true;
    error.value = null;
    try {
        const res = await fetch(ProductController.search.url({ query: { q: q.value } }), {
            headers: {
                Accept: 'application/json',
            }
        });

        if (!res.ok) throw new Error(`${res.status} ${res.statusText}`);

        const json = await res.json();
        // If using a resource collection it may be { data: [...] }
        searchProducts.value = json.data ?? json;
    } catch (err: any) {
        error.value = err?.message ?? String(err);
    } finally {
        loading.value = false;
    }
};
</script>

<template>
    <Head title="Ventas" />

    <AppLayout>
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <!-- Form box -->
            <div class="relative overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border p-4 space-y-4">
                <!-- Search -->
                <h2 class="font-medium text-lg">Nueva venta</h2>
                <Form
                    :reset-on-success="['q']"
                    class="flex items-end gap-4"
                >
                    <div class="grid gap-2 w-full">
                        <Label for="q">Buscar producto</Label>
                        <Input
                            id="q"
                            type="string"
                            name="q"
                            v-model="q"
                            required
                            autofocus
                            :tabindex="1"
                            autocomplete="q"
                            placeholder="Nombre"
                        />
                    </div>

                    <Button
                        type="submit"
                        class="mt-4"
                        :tabindex="2"
                        :disabled="loading"
                        @click.prevent="search"
                    >
                        <LoaderCircle
                            v-if="loading"
                            class="h-4 w-4 animate-spin"
                        />
                        Buscar
                    </Button>
                </Form>

                <template v-if="searchProducts.length > 0">
                    <Separator class="my-12"/>
                    <h2 class="font-medium text-lg">Selecciona un producto</h2>
                    <div class="flex flex-col gap-2 w-full">
                        <Button
                            v-for="(product, index) in searchProducts"
                            :key="product.id"
                            @click="addProduct(product.id)"
                            :tabindex="index + 3"
                            variant="outline"
                            class="w-full text-left"
                        >
                            {{ product.name }} {{ product.id }}
                        </Button>
                    </div>
                </template>

                <Form
                    v-bind="SaleController.store.form()"
                    :reset-on-success="true"
                    :onSuccess="handleMainFormFinish"
                    v-slot="{ errors, processing }"
                    :transform="data => {
                        return {
                            ...data,
                            paid_amount: paidAmount,
                            sale_items: formSaleItems
                        }
                    }"
                    class="flex flex-col gap-4 w-full mt-4"
                >
                    <!-- Sale Items -->
                    <template v-if="selectedProducts.length > 0">
                        <Separator class="my-12"/>
                        <h2 class="font-medium text-lg">Productos</h2>
                        <div class="flex flex-col gap-2 w-full">
                            <div
                                v-for="(product, index) in selectedProducts"
                                :key="product.id"
                                class="flex gap-4 w-full items-start justify-between border rounded-2xl p-4"
                            >
                                <Label class="self-center text-lg w-full">{{ product.name }}</Label>

                                <div class="flex flex-col gap-2 items-center justify-center w-full self-center">
                                    <Label class="flex items-center justify-center space-x-3">
                                        <Checkbox v-model="formSaleItems[index].is_retail_sale" :value:boolean="true" :name="`sale_items.${index}.is_retail_sale`" :tabindex="searchProducts.length + index + 3"/>
                                        <span>Venta al menudeo?</span>
                                    </Label>
                                    <InputError :message="errors[`sale_items.${index}.is_retail_sale`]" />
                                </div>

                                <div class="grid gap-2 w-full">
                                    <Label :for="`quantity_${index}`">Cantidad</Label>
                                    <Input
                                        :id="`quantity_${index}`"
                                        :model-value="formSaleItems[index].quantity"
                                        type="number"
                                        step="1"
                                        :tabindex="searchProducts.length + index + 3"
                                        autocomplete="quantity"
                                        :name="`sale_items.${index}.quantity`"
                                        :placeholder="formSaleItems[index].is_retail_sale ? `${product.retail_measure_unit}(s)` : `${product.measure_unit}(s)`"
                                    />
                                    <InputError :message="errors[`sale_items.${index}.quantity`]" />
                                </div>

                                <div class="grid gap-2 w-full">
                                    <Input
                                        hidden
                                        :id="`product_id_${index}`"
                                        :model-value="formSaleItems[index].product_id"
                                        type="number"
                                        step="1"
                                        readonly
                                        :name="`sale_items.${index}.product_id`"
                                    />
                                    <InputError :message="errors[`sale_items.${index}.product_id`]" />
                                </div>

                                <Button
                                    @click="removeProduct(product.id)"
                                    variant="destructive"
                                    :tabindex="searchProducts.length + index + 3"
                                    class="self-end"
                                >
                                    <XCircle />
                                </Button>
                            </div>
                        </div>
                    </template>

                    <!-- Summary section -->
                    <Separator class="my-6"/>
                    <h2 class="font-medium text-lg">Resumen</h2>
                    <div class="flex gap-4 w-full items-start">
                        <div class="grid gap-2 w-full">
                            <Label for="client">Cliente</Label>
                            <Input
                                id="client"
                                :model-value="saleToEdit?.client"
                                type="text"
                                autofocus
                                :tabindex="searchProducts.length + selectedProducts.length + 3"
                                autocomplete="client"
                                name="client"
                                placeholder="Nombre del cliente"
                            />
                            <InputError :message="errors.client" />
                        </div>
                        <!-- Money section -->
                        <div class="flex flex-col flex-2/3 gap-2">
                            <div class="grid gap-2 w-full">
                                <Label for="total">Total<span class="text-red-500">*</span></Label>
                                <Input
                                    id="total"
                                    v-model="total"
                                    type="number"
                                    step="0.01"
                                    required
                                    hidden
                                    readonly
                                    name="total"
                                    autocomplete="total"
                                />
                                <Input
                                    v-model="formattedTotal"
                                    :tabindex="searchProducts.length + selectedProducts.length + 4"
                                    type="text"
                                    class="text-right"
                                    readonly
                                />
                                <InputError :message="errors.total" />
                            </div>
                            <div class="grid gap-2 w-full">
                                <Label for="paid_ammount">Paga con<span class="text-red-500">*</span></Label>
                                <NumberField
                                    class="gap-2 text-right"
                                    :min="0"
                                    :format-options="{
                                        style: 'currency',
                                        currency: 'MXN',
                                        currencyDisplay: 'narrowSymbol',
                                        currencySign: 'accounting',
                                    }"
                                    :model-value="paidAmount"
                                    @update:model-value="(v) => {
                                        if (v) {
                                            paidAmount = v
                                        }
                                        else {
                                            paidAmount = undefined
                                        }
                                    }"
                                >
                                    <NumberFieldContent>
                                        <NumberFieldInput :tabindex="searchProducts.length + selectedProducts.length + 5" class="text-right pr-2.5" />
                                    </NumberFieldContent>
                                </NumberField>
                                <InputError :message="errors.paid_amount" />
                            </div>
                            <div class="grid gap-2 w-full">
                                <Label for="change_amount">Cambio<span class="text-red-500">*</span></Label>
                                <Input
                                    id="change_amount"
                                    v-model="changeAmount"
                                    type="number"
                                    step="0.01"
                                    required
                                    hidden
                                    readonly
                                    :tabindex="2"
                                    name="change_amount"
                                    autocomplete="change_amount"
                                />
                                <Input
                                    v-model="formattedChangeAmount"
                                    :tabindex="searchProducts.length + selectedProducts.length + 6"
                                    type="text"
                                    class="text-right"
                                    readonly
                                />
                                <InputError :message="errors.change_amount" />
                            </div>
                        </div>
                    </div>

                    <Button
                        type="submit"
                        class="w-full"
                        :tabindex="searchProducts.length + selectedProducts.length + 7"
                        :disabled="processing"
                    >
                        <LoaderCircle
                            v-if="processing"
                            class="h-4 w-4 animate-spin"
                        />
                        Guardar
                    </Button>
                </Form>
            </div>

            <!-- List box -->
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border p-4">
                <h2 class="font-medium text-lg">Ventas</h2>
                <DataTable :columns="columns" :data="props.sales"/>
            </div>

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
                        <AlertDialogAction @click="salesStore.clearIdToDelete()">Aceptar</AlertDialogAction>
                    </AlertDialogFooter>
                </AlertDialogContent>
            </AlertDialog>
        </div>
    </AppLayout>
</template>