<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Form } from '@inertiajs/vue3';

import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';
import { LoaderCircle } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import ProductRadioList from '@/components/ProductRadioList.vue';
import ProductController from '@/actions/App/Http/Controllers/ProductController';
import PurchaseController from '@/actions/App/Http/Controllers/PurchaseController';

interface Provider {
    data: {
        id: string | number;
        name: string;
        [key: string]: any;
    }[];
}

const props = defineProps<{
  providers: Provider[];
}>();

const q = ref<string>('');
const products = ref<any[]>([]);
const loading = ref<boolean>(false);
const error = ref<string | null>(null);
const selectedProductId = ref<string | number | null>(null);
const selectedProduct = computed(() => {
    return products.value.find(p => p.id === selectedProductId.value) || null;
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

/* STARTS HERE */
</script>

<template>
    <Head title="Compras" />

    <AppLayout>
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <!-- Round box -->
            <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border p-4 space-y-4">
                <Form
                    :reset-on-success="['q']"
                    class="flex items-end gap-4"
                >
                    <div class="grid gap-2 w-full">
                        <Label for="q">Producto</Label>
                        <Input
                            id="q"
                            type="string"
                            name="q"
                            v-model="q"
                            required
                            autofocus
                            :tabindex="1"
                            autocomplete="q"
                            placeholder="Producto"
                        />
                    </div>

                    <Button
                        type="submit"
                        class="mt-4"
                        :tabindex="4"
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

            </div>

            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border">
                
            </div>
        </div>
    </AppLayout>
</template>