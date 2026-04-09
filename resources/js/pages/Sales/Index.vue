<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Form, router } from '@inertiajs/vue3';
import { computed, onMounted, onUpdated, ref, watch, nextTick } from 'vue';

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

import type { FormSaleItem, PrintActions, Product, Sale } from '@/types/main';
import SaleController from '@/actions/App/Http/Controllers/SaleController';
import ProductController from '@/actions/App/Http/Controllers/ProductController';
import { columns } from '@/components/tables/sales/columns';
import { useSalesStore } from '@/stores/sales';
import { Checkbox } from '@/components/ui/checkbox';
import { RadioGroup, RadioGroupItem } from '@/components/ui/radio-group';
import { ScrollArea } from '@/components/ui/scroll-area';
import { Item, ItemContent, ItemDescription, ItemTitle } from '@/components/ui/item';
import { Card, CardContent, CardTitle } from '@/components/ui/card';
import CardHeader from '@/components/ui/card/CardHeader.vue';
import ProductRadioList from '@/components/ProductRadioList.vue';

const posPrinterApiUrl = import.meta.env.VITE_POS_PRINTER_API_URL;

const props = defineProps<{
  sales: Sale[];
  saleToPrint: Sale | null;
}>();

const salesStore = useSalesStore();
const saleToEdit = ref<Sale | null>(null);
const saleToShow = ref<Sale | null>(null);
const showMessageAlert = ref<boolean>(false);
const showSaleAlert = ref<boolean>(false);
const showDeleteModal = ref<boolean>(false);

/* Search */
const q = ref<string>('');
const searchResultProducts = ref<any[]>([]);
const loading = ref<boolean>(false);
const error = ref<string | null>(null);

/* Form */
const selectedProducts = ref<Product[]>([]);
const formSaleItems = ref<FormSaleItem[]>([]);

/* Money */
const total = computed(() => {
    return formSaleItems.value.reduce((carry, item) => carry + (item.total ?? 0), 0);
});
const formattedTotal = computed(() => {
    return new Intl.NumberFormat('es-MX', {
        style: 'currency',
        currency: 'MXN',
    }).format(total.value)
})
const paidAmount = ref<number | undefined>(0);
const changeAmount = computed(() => {
    return paidAmount.value ? (paidAmount.value ?? 0) - total.value : 0;
});
const formattedChangeAmount = computed(() => {
    return moneyFormat(changeAmount.value)
})

const handleMainFormFinish = () => {
    salesStore.clearIdToEdit();
    showMessageAlert.value = true;
    selectedProducts.value = [];
    formSaleItems.value = [];
    paidAmount.value = 0;
    searchInput.value?.inputElement?.focus();
};

const spanishPercentages: Record<string, string> = {
    'first_wholesale_percentage': 'Margen 1',
    'second_wholesale_percentage': 'Margen 2',
    'third_wholesale_percentage': 'Margen 3',
    'retail_percentage': 'Margen menudeo',
}

const addProduct = (productId: number) => {
    const product = selectedProducts.value.find(product => product.id === productId);

    if (!product ) {
        const product = searchResultProducts.value.find(product => product.id === productId);
        selectedProducts.value.unshift(product);
        formSaleItems.value.unshift({
            quantity: null,
            is_retail_sale: false,
            selected_percentage: 'first_wholesale_percentage',
            total: 0,
            product_id: productId,
            price_modification_id: product.current_price_modification.id,
        });
        searchResultProducts.value = [];
    }
}

const removeProduct = (productId: number) => {
    selectedProducts.value = selectedProducts.value.filter(product => product.id !== productId);
    formSaleItems.value = formSaleItems.value.filter(item => item.product_id !== productId);
    productToDeleteId.value = null;
}

watch(formSaleItems, (newItems) => {
    newItems.forEach((item, index) => {
        const product = selectedProducts.value.find(product => product.id === item.product_id);
        if (product) {
            if (item.is_retail_sale) {
                formSaleItems.value[index].selected_percentage = 'retail_percentage';
            }

            let price:number | undefined = 0;
            switch (item.selected_percentage) {
                case 'first_wholesale_percentage':
                    price = product.first_wholesale_price;
                    break;
                case 'second_wholesale_percentage':
                    price = product.second_wholesale_price;
                    break;
                case 'third_wholesale_percentage':
                    price = product.third_wholesale_price;
                    break;
                case 'retail_percentage':
                    price = product.retail_price;
                    break;
            }
            formSaleItems.value[index].total = (price ?? 0) * (item.quantity ?? 0);
        }
    });
}, { deep: true } );

const moneyFormat = (amount: number | undefined) => {
    return new Intl.NumberFormat('es-MX', {
        style: 'currency',
        currency: 'MXN',
    }).format(amount ?? 0)
}

salesStore.$subscribe((mutation, state) => {
    /* if (state.idToEdit !== null) {
        const sale = props.sales.find(s => s.id === state.idToEdit) || null;
        saleToEdit.value = sale;
    } else {
        saleToEdit.value = null;
    } */

    /* if (state.idToDelete !== 0) {
        showDestroyAlert.value = true;
    } else {
        showDestroyAlert.value = false;
    } */

    if (state.idToShow !== null) {
        const sale = props.sales.find(s => s.id === state.idToShow) || null;
        if (sale) {
            saleToShow.value = sale;
            showSaleAlert.value = true;
        }
    } else {
        saleToShow.value = null;
    }
});

const search = async () => {
    loading.value = true;
    error.value = null;
    try {
        const res = await fetch(ProductController.search.url({ query: { q: q.value, available: true } }), {
            headers: {
                Accept: 'application/json',
            }
        });

        if (!res.ok) throw new Error(`${res.status} ${res.statusText}`);

        const json = await res.json();
        // If using a resource collection it may be { data: [...] }
        searchResultProducts.value = json.data ?? json;
    } catch (err: any) {
        error.value = err?.message ?? String(err);
    } finally {
        loading.value = false;
        q.value = '';
    }
};

/* BUTTONS NAVIGATION */
const searchInput = ref<typeof Input | null>(null);
const clientInput = ref<typeof Input | null>(null);

onMounted(async () => {
    await nextTick();
    searchInput.value?.inputElement?.focus();

    window.addEventListener('keydown', (event: KeyboardEvent) => {
        if (event.key === 'F8') {
            event.preventDefault();
            searchInput.value?.inputElement?.focus();
        }
    })

    window.addEventListener('keydown', (event: KeyboardEvent) => {
        if (event.key === 'F9') {
            event.preventDefault();
            clientInput.value?.inputElement?.focus();
        }
    })

    window.addEventListener('keydown', (event: KeyboardEvent) => {
        if (event.key === 'F10' && formSaleItems.value.length > 0) {
            event.preventDefault();
            showDeleteModal.value = !showDeleteModal.value;
        }
    })

    window.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && showSaleAlert.value) {
            handleEscapeOnSaleAlert()
        }
    })
});

onUpdated(async () => {
    // Re-focus on any updates to handle Inertia navigation
    await nextTick();
    if (formSaleItems.value.length === 0) {
        searchInput.value?.inputElement?.focus();
    }

    if (props.saleToPrint) {
        printReceipt(props.saleToPrint);
    }
});

const selectedProductId = ref<string | number | null>(null);

watch(selectedProductId, (newId) => {
    if (newId) {
        addProduct(Number(newId));
    };
    /* selectedProductId.value = null; */
})

const handleEnterOnProductRadioList = (event: KeyboardEvent, productId: string | number | null) => {
    console.log({ event, productId });
    const existingProduct = selectedProducts.value.find(product => product.id === Number(productId));
    if (event.key === 'Enter' && existingProduct) {
        event.preventDefault();

        const quantityInputs = document.querySelectorAll('.quantity-input') as NodeListOf<HTMLInputElement>;
        quantityInputs[0]?.focus();
    }
    selectedProductId.value = null;
}

const handleEnterOnPercentagesRadios = (index: number, percentageKey: 'first_wholesale_percentage' | 'second_wholesale_percentage' | 'third_wholesale_percentage' | 'retail_percentage') => {
    formSaleItems.value[index].selected_percentage = percentageKey;
    searchInput.value?.inputElement?.focus();
}

/* DELETE PRODUCTS BY NAVIGATION */

const productToDeleteId = ref<number | null>(null);

watch(productToDeleteId, (newId) => {
    if (newId) {
        showDeleteModal.value = false;
        removeProduct(Number(newId));
    }
})

const printReceipt = async (sale: Sale | null) => {
    const printActions: PrintActions = {
        header: [
            {
                action: 'text',
                content: 'Abarrotes MG\n',
            },{
                action: 'text',
                content: `${new Date().toLocaleString()}\n`,
            },{
                action: 'text',
                content: '--------------------------------\n'
            }
        ],
        articles: [],
        totals: [
            {
                action: 'text',
                content: '--------------------------------\n'
            },{
                action: 'text',
                content: `Total: ${moneyFormat(sale?.total)}\n`
            },{
                action: 'text',
                content: `Paga con: ${moneyFormat(sale?.paid_amount)}\n`
            },{
                action: 'text',
                content: `Cambio: ${moneyFormat(sale?.change_amount)}\n`
            },
        ],
        footer: [
            {
                action: 'text',
                content: `${sale?.client}`,
            },
            {
                action: 'text',
                content: '¡Gracias por su compra!\n',
            }
        ],
    }

    sale?.sale_items.forEach(item => {
        const quantity = item.quantity;
        const name = item.product.name.length > 16 ? item.product.name.substring(0, 13) + '...' : item.product.name;
        const unitPrice = moneyFormat(item.total / item.quantity);
        const total = moneyFormat(item.total);

        printActions.articles.push({
            quantity,
            name,
            unitPrice,
            total
        });
    });

    try {
        const response = await fetch(`${posPrinterApiUrl}/print`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(printActions),
        });

        if (!response.ok) {
            throw new Error(`Error ${response.status}: ${response.statusText}`);
        }
        console.log('Print response:', await response.json());

        router.visit(SaleController.index());
    } catch (error) {
        console.error('Error printing receipt:', error);
    } finally {
        salesStore.clearIdToShow();
        showSaleAlert.value = false;
    }
};

const handleEscapeOnSaleAlert = async () => {
    salesStore.clearIdToShow();
    showSaleAlert.value = false;
    await nextTick();
    searchInput.value?.inputElement?.focus();
}
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
                            ref="searchInput"
                            id="q"
                            type="string"
                            name="q"
                            v-model="q"
                            required
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

                <template v-if="searchResultProducts.length > 0">
                    <Separator class="mt-8 mb-6"/>
                    <h2 class="font-medium text-lg">Selecciona un producto</h2>
                    <!-- <div class="flex flex-col gap-2 w-full">
                        <Button
                            v-for="(product, index) in searchResultProducts"
                            :key="product.id"
                            @click.prevent="addProduct(product.id)"
                            :tabindex="index + 3"
                            variant="outline"
                            class="w-full text-left product-buttons"
                        >
                            {{ product.name }}
                        </Button>
                    </div> -->

                    <ProductRadioList
                        ref="productRadioList"
                        @keydown.enter="handleEnterOnProductRadioList($event, selectedProductId)"
                        v-if="searchResultProducts.length > 0"
                        :products="searchResultProducts"
                        :selected-products-ids="selectedProducts.map(product => product.id)"
                        v-model="selectedProductId"
                        value-key="id"
                        label-key="name"
                        show-price
                        :disabled="false"
                        from-sales-page
                        locale="es-419"
                        currency="USD"
                        class="max max-h-72 overflow-y-auto"
                    />
                </template>

                <Form
                    v-if="selectedProducts.length > 0"
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
                    <Separator class="my-6"/>
                    <h2 class="font-medium text-lg">Productos</h2>
                    <div class="flex flex-col gap-2 w-full" @keydown.enter.prevent>
                        <div
                            v-for="(product, index) in selectedProducts"
                            :key="product.id"
                            class="flex gap-4 w-full items-start justify-evenly border rounded-2xl p-4"
                        >
                            <p class="self-center text-lg w-full">{{ product.name }}</p>

                            <div v-if="product.current_price_modification?.sold_by_retail" class="flex flex-col gap-2 items-center justify-center w-2xl self-center">
                                <Label class="flex items-center justify-center space-x-3">
                                    <Checkbox v-model="formSaleItems[index].is_retail_sale" :value:boolean="true" :name="`sale_items.${index}.is_retail_sale`" :tabindex="searchResultProducts.length + index + 3"/>
                                    <span>¿Venta al menudeo?</span>
                                </Label>
                                <InputError :message="errors[`sale_items.${index}.is_retail_sale`]" />
                            </div>
                            <!-- Just for aestethic spacing -->
                            <div v-else class="w-2xl"></div>

                            <div class="grid gap-2 w-full">
                                <Label :for="`quantity_${index}`">Cantidad - {{ formSaleItems[index].is_retail_sale ? `${product.retail_measure_unit?.name}(s)` : `${product.measure_unit?.name}(s)` }}</Label>
                                <Input
                                    :id="`quantity_${index}`"
                                    v-model="formSaleItems[index].quantity"
                                    type="number"
                                    step="1"
                                    :tabindex="searchResultProducts.length + index + 2"
                                    autocomplete="quantity"
                                    :name="`sale_items.${index}.quantity`"
                                    :placeholder="formSaleItems[index].is_retail_sale ? `${product.retail_measure_unit?.name}(s)` : `${product.measure_unit?.name}(s)`"
                                    class="quantity-input"
                                />
                                <InputError :message="errors[`sale_items.${index}.quantity`]" />
                            </div>

                            <div class="grid gap-2 w-[120rem] h-full mt-1">
                                <RadioGroup class="flex gap-2 w-full h-full" v-model="formSaleItems[index].selected_percentage" :name="`sale_items.${index}.selected_percentage`">
                                    <div class="flex flex-col items-center w-full h-full space-y-2 border rounded-lg p-2 has-checked:border-2 has-checked:bg-gray-300">
                                        <Label class="text-center leading-snug">Margen 1<br>{{ moneyFormat(product.first_wholesale_price) }}
                                            <RadioGroupItem value="first_wholesale_percentage" :tabindex="searchResultProducts.length + index + 3" @keydown.enter.prevent="handleEnterOnPercentagesRadios(index, 'first_wholesale_percentage')"/>
                                        </Label>
                                    </div>
                                    <div class="flex flex-col items-center w-full h-full space-y-2 border rounded-lg p-2 has-checked:border-2 has-checked:bg-gray-300">
                                        <Label class="text-center leading-snug">Margen 2<br>{{ moneyFormat(product.second_wholesale_price) }}
                                            <RadioGroupItem value="second_wholesale_percentage" :tabindex="searchResultProducts.length + index + 3" @keydown.enter.prevent="handleEnterOnPercentagesRadios(index, 'second_wholesale_percentage')"/>
                                        </Label>
                                    </div>
                                    <div class="flex flex-col items-center w-full h-full space-y-2 border rounded-lg p-2 has-checked:border-2 has-checked:bg-gray-300">
                                        <Label class="text-center leading-snug">Margen 3<br>{{ moneyFormat(product.third_wholesale_price) }}
                                            <RadioGroupItem value="third_wholesale_percentage" :tabindex="searchResultProducts.length + index + 3" @keydown.enter.prevent="handleEnterOnPercentagesRadios(index, 'third_wholesale_percentage')"/>
                                        </Label>
                                    </div>
                                    <div v-if="formSaleItems[index].is_retail_sale" class="flex flex-col items-center w-full h-full space-y-2 border rounded-lg p-2 has-checked:border-2 has-checked:bg-gray-300">
                                        <Label class="text-center leading-snug">Margen menudeo<br>{{ moneyFormat(product.retail_price) }}
                                            <RadioGroupItem value="retail_percentage" :tabindex="searchResultProducts.length + index + 3" @keydown.enter.prevent="handleEnterOnPercentagesRadios(index, 'retail_percentage')"/>
                                        </Label>
                                    </div>
                                </RadioGroup>
                                <InputError :message="errors[`sale_items.${index}.selected_percentage`]" />
                            </div>

                            <div class="grid gap-2">
                                <Input
                                    hidden
                                    :model-value="formSaleItems[index].product_id"
                                    type="number"
                                    step="1"
                                    readonly
                                    :name="`sale_items.${index}.product_id`"
                                />
                                <InputError :message="errors[`sale_items.${index}.product_id`]" />
                            </div>

                            <div class="grid gap-2">
                                <Input
                                    hidden
                                    :model-value="formSaleItems[index].price_modification_id"
                                    type="number"
                                    step="1"
                                    readonly
                                    :name="`sale_items.${index}.price_modification_id`"
                                />
                                <InputError :message="errors[`sale_items.${index}.price_modification_id`]" />
                            </div>

                            <p class="text-lg self-center me-4 w-2xl text-right">{{ moneyFormat(formSaleItems[index].total) }}</p>

                            <Button
                                @click.prevent="removeProduct(product.id)"
                                variant="destructive"
                                :tabindex="searchResultProducts.length + index + 3"
                                class="self-center"
                            >
                                <XCircle />
                            </Button>
                        </div>
                    </div>

                    <!-- Summary section -->
                    <Separator class="my-4"/>
                    <h2 class="font-medium text-lg">Resumen</h2>
                    <div class="flex gap-4 w-full items-start" @keydown.enter.prevent>
                        <div class="grid gap-2 w-full">
                            <Label for="client">Cliente</Label>
                            <Input
                                ref="clientInput"
                                id="client"
                                :model-value="saleToEdit?.client"
                                type="text"
                                :tabindex="searchResultProducts.length + selectedProducts.length + 3"
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
                                    :tabindex="searchResultProducts.length + selectedProducts.length + 4"
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
                                        <NumberFieldInput :tabindex="searchResultProducts.length + selectedProducts.length + 5" class="text-right pr-2.5" />
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
                                    :tabindex="searchResultProducts.length + selectedProducts.length + 6"
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
                        :tabindex="searchResultProducts.length + selectedProducts.length + 7"
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

            <!-- Delete alert -->
            <AlertDialog v-model:open="showDeleteModal" @keydown.escape="showDeleteModal = false">
                <AlertDialogContent>
                    <AlertDialogHeader>
                        <AlertDialogTitle>Eliminar producto</AlertDialogTitle>
                        <AlertDialogDescription>
                            Selecciona producto a eliminar
                        </AlertDialogDescription>
                    </AlertDialogHeader>
                    <ProductRadioList
                        ref="deleteProductRadioList"
                        v-if="selectedProducts.length > 0"
                        :products="selectedProducts"
                        v-model="productToDeleteId"
                        value-key="id"
                        label-key="name"
                        show-price
                        :disabled="false"
                        from-sales-page
                        class="max max-h-72 overflow-y-auto"
                    />
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
                        <AlertDialogAction @click="salesStore.clearIdToDelete()">Aceptar</AlertDialogAction>
                    </AlertDialogFooter>
                </AlertDialogContent>
            </AlertDialog>

            <!-- Show sale alert -->
            <AlertDialog v-model:open="showSaleAlert">
                <AlertDialogContent class="w-full max-w-3xl">
                    <AlertDialogHeader class="flex flex-row justify-between items-center">
                        <div>
                            <AlertDialogTitle>{{ saleToShow?.created_at }}</AlertDialogTitle>
                            <AlertDialogDescription>Detalles de la venta</AlertDialogDescription>
                        </div>
                        <Button @click.prevent="printReceipt(saleToShow)" :tabindex="searchResultProducts.length + selectedProducts.length + 8">Imprimir Recibo</Button>
                    </AlertDialogHeader>
                    <div class="flex gap-2 w-full">
                        <Item variant="outline" class="w-full">
                            <ItemContent>
                                <ItemTitle>Cliente:</ItemTitle>
                                <ItemDescription>{{ saleToShow?.client || '—' }}</ItemDescription>
                            </ItemContent>
                        </Item>
                        <Item variant="outline" class="w-full">
                            <ItemContent>
                                <ItemTitle>Registrado por:</ItemTitle>
                                <ItemDescription>{{ saleToShow?.created_by || '—' }}</ItemDescription>
                            </ItemContent>
                        </Item>
                        <Item variant="outline" class="w-full">
                            <ItemContent>
                                <ItemTitle>Total:</ItemTitle>
                                <ItemDescription>{{ moneyFormat(saleToShow?.total) }}</ItemDescription>
                            </ItemContent>
                        </Item>
                        <Item variant="outline" class="w-full">
                            <ItemContent>
                                <ItemTitle>Pagado con:</ItemTitle>
                                <ItemDescription>{{ moneyFormat(saleToShow?.paid_amount) }}</ItemDescription>
                            </ItemContent>
                        </Item>
                        <Item variant="outline" class="w-full">
                            <ItemContent>
                                <ItemTitle>Cambio:</ItemTitle>
                                <ItemDescription>{{ moneyFormat(saleToShow?.change_amount) }}</ItemDescription>
                            </ItemContent>
                        </Item>
                    </div>
                    <ScrollArea class="w-full max-h-64 flex flex-col gap-2">
                        <Card v-for="saleItem in saleToShow?.sale_items" :key="saleItem.id" class="flex flex-row justify-between w-full mb-2">
                            <CardHeader class="w-1/2">
                                <CardTitle>{{ saleItem.product.name }}</CardTitle>
                            </CardHeader>
                            <CardContent class="flex gap-2 w-full justify-end">
                                <p>{{ saleItem.quantity }} {{ saleItem.is_retail_sale ? saleItem.product.retail_measure_unit?.name + '(s)' : saleItem.product.measure_unit?.name + '(s)' }} x</p>
                                <p>{{ moneyFormat(saleItem.total / saleItem.quantity) }} =</p>
                                <p>{{ moneyFormat(saleItem.total) }}</p>
                                <p>{{ spanishPercentages[saleItem.selected_percentage] }}</p>
                            </CardContent>
                        </Card>
                    </ScrollArea>
                    <AlertDialogFooter class="items-center">
                        <p class="text-xl mr-4">Total: {{ moneyFormat(saleToShow?.total) }}</p>
                        <AlertDialogAction @click="handleEscapeOnSaleAlert" :tabindex="searchResultProducts.length + selectedProducts.length + 9">
                            Cerrar
                        </AlertDialogAction>
                    </AlertDialogFooter>
                </AlertDialogContent>
            </AlertDialog>
        </div>
    </AppLayout>
</template>