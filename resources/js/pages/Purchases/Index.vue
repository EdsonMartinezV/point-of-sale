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
import { Separator } from '@/components/ui/separator';
import InputError from '@/components/InputError.vue';
import { CheckIcon, ChevronsUpDownIcon, LoaderCircle } from 'lucide-vue-next';
import ProductRadioList from '@/components/ProductRadioList.vue';

import { Provider, Purchase } from '@/types/main';
import ProductController from '@/actions/App/Http/Controllers/ProductController';
import PurchaseController from '@/actions/App/Http/Controllers/PurchaseController';
import { columns } from '@/components/tables/purchases/columns';
import { usePurchasesStore } from '@/stores/purchases';
import { cn } from '@/lib/utils';
import { Checkbox } from '@/components/ui/checkbox';

const props = defineProps<{
  purchases: Purchase[];
  providers: Provider[];
}>();

const purchasesStore = usePurchasesStore();
const purchaseToEdit = ref<Purchase | null>(null);
const showMessageAlert = ref<boolean>(false);

const q = ref<string>('');
const products = ref<any[]>([]);
const loading = ref<boolean>(false);
const error = ref<string | null>(null);
const selectedProductId = ref<string | number | null>(null);
const selectedProduct = computed(() => {
    return products.value.find(p => p.id === selectedProductId.value) || null;
});
const costPrice = ref<number | null>(null);
const quantity = ref<number | null>(null);
const total = computed(() => {
    return (costPrice.value ?? 0)* (quantity.value ?? 0);
});
const soldByRetail = ref<boolean>(false);
const retailUnitsPerBox = ref<number | null>(null);
const firstWholesalePercentage = ref<number | null>(null);
const secondWholesalePercentage = ref<number | null>(null);
const thirdWholesalePercentage = ref<number | null>(null);
const retailPercentage = ref<number | null>(null);

/* Combo boxes */
const openProviderComboBox = ref<boolean>(false);

const selectedProviderId = ref<number | null | ''>(null);

const handleMainFormFinish = () => {
    purchasesStore.clearIdToEdit();
    showMessageAlert.value = true;
};

purchasesStore.$subscribe((mutation, state) => {
    if (state.idToEdit !== null) {
        const purchase = props.purchases.find(p => p.id === state.idToEdit) || null;
        purchaseToEdit.value = purchase;
    } else {
        purchaseToEdit.value = null;
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
        products.value = json.data ?? json;
    } catch (err: any) {
        error.value = err?.message ?? String(err);
    } finally {
        loading.value = false;
    }
};
</script>

<template>
    <Head title="Compras" />

    <AppLayout>
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <!-- Round box -->
            <div class="relative scroll-auto rounded-xl border border-sidebar-border/70 dark:border-sidebar-border p-4 space-y-4">
                <h2 class="font-medium text-lg">Nueva compra</h2>
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

                <ProductRadioList
                    v-if="products.length > 0"
                    :products="products"
                    v-model="selectedProductId"
                    value-key="id"
                    label-key="name"
                    :show-price="false"
                    :disabled="false"
                    locale="es-419"
                    currency="USD"
                    class="max-h-24 overflow-y-auto"
                />

                <template v-if="selectedProduct">
                    <Separator />

                    <Form
                        v-bind="PurchaseController.store.form()"
                        :reset-on-success="true"
                        :onSuccess="handleMainFormFinish"
                        v-slot="{ errors, processing }"
                        :transform="data => {
                            return {
                                ...data,
                                provider_id: selectedProviderId || null,
                                purchase_items: [{
                                    quantity,
                                    product_id: selectedProduct.id,
                                    sold_by_retail: soldByRetail,
                                    retail_units_per_box: retailUnitsPerBox,
                                    cost_price: costPrice,
                                    first_wholesale_percentage: firstWholesalePercentage,
                                    second_wholesale_percentage: secondWholesalePercentage,
                                    third_wholesale_percentage: thirdWholesalePercentage,
                                    retail_percentage: soldByRetail ? retailPercentage : null,
                                }],
                            }
                        }"
                        class="flex flex-col gap-4 w-full mt-4"
                    >

                        <div class="flex gap-4 w-full items-start">
                            <div class="grid gap-2 w-full">
                                <Label for="total">Total {{ total }}</Label>
                                <Input
                                    id="total"
                                    v-model="total"
                                    type="number"
                                    step="0.01"
                                    required
                                    readonly
                                    :tabindex="products.length + 3"
                                    name="total"
                                    placeholder="Total de la compra"
                                />
                                <InputError :message="errors.total" />
                            </div>
                            <!-- Provider combo box -->
                            <div class="grid gap-2 w-full">
                                <Label for="provider_id">Proveedor</Label>
                                <Popover v-model:open="openProviderComboBox">
                                    <PopoverTrigger as-child>
                                    <Button
                                        id="provider_id"
                                        variant="outline"
                                        role="combobox"
                                        :aria-expanded="openProviderComboBox"
                                        class="w-[200px] justify-between"
                                        :tabindex="products.length + 4"
                                        name="provider_id"
                                    >
                                        {{
                                        selectedProviderId
                                            ? props.providers.find(provider => provider.id === selectedProviderId)?.name
                                            : 'Escoge un proveedor...'
                                        }}
                                        <ChevronsUpDownIcon class="ml-2 h-4 w-4 shrink-0 opacity-50" />
                                    </Button>
                                    </PopoverTrigger>
                                    <PopoverContent class="w-[200px] p-0">
                                    <Command>
                                        <CommandInput placeholder="Buscar proveedor..." />
                                        <CommandList>
                                        <CommandEmpty>Sin resultados</CommandEmpty>
                                        <CommandGroup>
                                            <CommandItem
                                                v-for="provider in props.providers"
                                                :key="provider.id"
                                                :value="provider.id"
                                                @select="() => {
                                                    selectedProviderId = selectedProviderId === provider.id ? '' : provider.id
                                                    openProviderComboBox = false
                                                }"
                                            >
                                                <CheckIcon
                                                    :class="cn(
                                                    'mr-2 h-4 w-4',
                                                    selectedProviderId === provider.id ? 'opacity-100' : 'opacity-0',
                                                    )"
                                                />
                                                {{ provider.name }}
                                            </CommandItem>
                                        </CommandGroup>
                                        </CommandList>
                                    </Command>
                                    </PopoverContent>
                                </Popover>
                                <InputError :message="errors.provider_id" />
                            </div>

                            <div class="grid gap-2 w-full">
                                <Label for="quantity">Cantidad {{ quantity }}</Label>
                                <Input
                                    id="quantity"
                                    v-model="quantity"
                                    type="number"
                                    step="1"
                                    required
                                    :tabindex="products.length + 5"
                                    autocomplete="quantity"
                                    name="purchase_items.0.quantity"
                                    placeholder="Cantidad comprada"
                                />
                                <InputError :message="errors['purchase_items.0.quantity']" />
                            </div>

                            <div class="flex flex-col gap-2 items-center justify-center w-full self-center">
                                <Label for="sold_by_retail" class="flex items-center justify-center space-x-3">
                                    <Checkbox v-model="soldByRetail" :value:boolean="true" id="sold_by_retail" name="purchase_items.0.sold_by_retail" :tabindex="products.length + 6"/>
                                    <span>Vendido al menudeo</span>
                                </Label>
                                <InputError :message="errors['purchase_items.0.sold_by_retail']" />
                            </div>

                            <div class="grid gap-2 w-full">
                                <Label for="retail_units_per_box">Unidades por caja</Label>
                                <Input
                                    id="retail_units_per_box"
                                    v-model="retailUnitsPerBox"
                                    type="number"
                                    step="1"
                                    required
                                    :tabindex="products.length + 7"
                                    autocomplete="retail_units_per_box"
                                    name="purchase_items.0.retail_units_per_box"
                                    placeholder="Unidades por caja"
                                />
                                <InputError :message="errors['purchase_items.0.retail_units_per_box']" />
                            </div>
                        </div>

                        <div class="flex gap-4 w-full items-start">
                            <div class="grid gap-2 w-full">
                                <Label for="cost_price">Precio de costo {{ costPrice }}</Label>
                                <Input
                                    id="cost_price"
                                    v-model="costPrice"
                                    type="number"
                                    step="0.01"
                                    required
                                    :tabindex="products.length + 8"
                                    autocomplete="cost_price"
                                    name="purchase_items.0.cost_price"
                                    placeholder="$"
                                />
                                <InputError :message="errors['purchase_items.0.cost_price']" />
                            </div>

                            <div class="grid gap-2 w-full">
                                <Label for="first_wholesale_percentage">Primer margen de ganancia <span class="text-red">(%)</span></Label>
                                <Input
                                    id="first_wholesale_percentage"
                                    v-model="firstWholesalePercentage"
                                    type="number"
                                    step="1"
                                    required
                                    :tabindex="products.length + 9"
                                    autocomplete="first_wholesale_percentage"
                                    name="purchase_items.0.first_wholesale_percentage"
                                    placeholder="Porcentaje %"
                                />
                                <InputError :message="errors['purchase_items.0.first_wholesale_percentage']" />
                            </div>

                            <div class="grid gap-2 w-full">
                                <Label for="second_wholesale_percentage">Segundo margen de ganancia <span class="text-red">(%)</span></Label>
                                <Input
                                    id="second_wholesale_percentage"
                                    v-model="secondWholesalePercentage"
                                    type="number"
                                    step="1"
                                    required
                                    :tabindex="products.length + 10"
                                    autocomplete="second_wholesale_percentage"
                                    name="purchase_items.0.second_wholesale_percentage"
                                    placeholder="Porcentaje %"
                                />
                                <InputError :message="errors['purchase_items.0.second_wholesale_percentage']" />
                            </div>

                            <div class="grid gap-2 w-full">
                                <Label for="third_wholesale_percentage">Tercer margen de ganancia <span class="text-red">(%)</span></Label>
                                <Input
                                    id="third_wholesale_percentage"
                                    v-model="thirdWholesalePercentage"
                                    type="number"
                                    step="1"
                                    required
                                    :tabindex="products.length + 11"
                                    autocomplete="third_wholesale_percentage"
                                    name="purchase_items.0.third_wholesale_percentage"
                                    placeholder="Porcentaje %"
                                />
                                <InputError :message="errors['purchase_items.0.third_wholesale_percentage']" />
                            </div>

                            <div v-if="soldByRetail" class="grid gap-2 w-full">
                                <Label for="retail_percentage">Margen de ganancia al menudeo <span class="text-red">(%)</span></Label>
                                <Input
                                    id="retail_percentage"
                                    v-model="retailPercentage"
                                    type="number"
                                    step="1"
                                    required
                                    :tabindex="products.length + 11"
                                    autocomplete="retail_percentage"
                                    name="purchase_items.0.retail_percentage"
                                    placeholder="Porcentaje %"
                                />
                                <InputError :message="errors['purchase_items.0.retail_percentage']" />
                            </div>
                        </div>

                        <Button
                            type="submit"
                            class="w-full"
                            :tabindex="products.length + 13"
                            :disabled="processing"
                        >
                            <LoaderCircle
                                v-if="processing"
                                class="h-4 w-4 animate-spin"
                            />
                            Guardar
                        </Button>
                    </Form>
                </template>
            </div>

            <!-- List box -->
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border p-4">
                <h2 class="font-medium text-lg">Compras</h2>
                <DataTable :columns="columns" :data="props.purchases"/>
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
                        <AlertDialogAction @click="purchasesStore.clearIdToDelete()">Aceptar</AlertDialogAction>
                    </AlertDialogFooter>
                </AlertDialogContent>
            </AlertDialog>
        </div>
    </AppLayout>
</template>