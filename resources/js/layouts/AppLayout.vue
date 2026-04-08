<script setup lang="ts">
import CategoryController from '@/actions/App/Http/Controllers/CategoryController';
import MeasureUnitController from '@/actions/App/Http/Controllers/MeasureUnitController';
import ProductController from '@/actions/App/Http/Controllers/ProductController';
import ProviderController from '@/actions/App/Http/Controllers/ProviderController';
import PurchaseController from '@/actions/App/Http/Controllers/PurchaseController';
import SaleController from '@/actions/App/Http/Controllers/SaleController';
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItemType } from '@/types';
import { router } from '@inertiajs/vue3';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});

window.addEventListener('keydown',(event: KeyboardEvent) => {
    if (event.key === 'F1') {
        event.preventDefault();
        router.visit(PurchaseController.index())
    }
    if (event.key === 'F2') {
        event.preventDefault();
        router.visit(SaleController.index())
    }
    if (event.key === 'F3') {
        event.preventDefault();
        router.visit(ProviderController.index())
    }
    if (event.key === 'F4') {
        event.preventDefault();
        router.visit(CategoryController.index())
    }
    if (event.key === 'F6') {
        event.preventDefault();
        router.visit(ProductController.index())
    }
    if (event.key === 'F7') {
        event.preventDefault();
        router.visit(MeasureUnitController.index())
    }
});
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <slot />
    </AppLayout>
</template>
