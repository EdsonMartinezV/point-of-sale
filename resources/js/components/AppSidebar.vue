<script setup lang="ts">
/* import NavFooter from '@/components/NavFooter.vue'; */
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
/* import { dashboard } from '@/routes'; */
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { /*BookOpen, Folder, LayoutGrid, */ BaggageClaim, ShoppingBasket, ContactRound, ListTree, Milk, Weight } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

import PurchaseController from '@/actions/App/Http/Controllers/PurchaseController';
import SaleController from '@/actions/App/Http/Controllers/SaleController';
import ProviderController from '@/actions/App/Http/Controllers/ProviderController';
import CategoryController from '@/actions/App/Http/Controllers/CategoryController';
import ProductController from '@/actions/App/Http/Controllers/ProductController';
import MeasureUnitController from '@/actions/App/Http/Controllers/MeasureUnitController';

const mainNavItems: NavItem[] = [
    {
        title: 'Compras',
        href: PurchaseController.index(),
        icon: BaggageClaim,
    },
    {
        title: 'Ventas',
        href: SaleController.index(),
        icon: ShoppingBasket,
    },
    {
        title: 'Proveedores',
        href: ProviderController.index(),
        icon: ContactRound,
    },
    {
        title: 'Categorías',
        href: CategoryController.index(),
        icon: ListTree,
    },
    {
        title: 'Productos',
        href: ProductController.index(),
        icon: Milk,
    },
    {
        title: 'Presentaciones',
        href: MeasureUnitController.index(),
        icon: Weight,
    },
];

/* const footerNavItems: NavItem[] = [
    {
        title: 'Github Repo',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: Folder,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#vue',
        icon: BookOpen,
    },
]; */
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="SaleController.index()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <!-- <NavFooter :items="footerNavItems" /> -->
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
