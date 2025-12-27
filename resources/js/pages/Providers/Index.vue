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

import type { Provider } from '@/types/main';
import ProviderController from '@/actions/App/Http/Controllers/ProviderController';
import { columns } from '@/components/tables/providers/columns';
import { useProvidersStore } from '@/stores/providers';

const props = defineProps<{
  providers: Provider[];
}>();

const providerToEdit = ref<Provider | null>(null);
const showDestroyAlert = ref<boolean>(false);
const showMessageAlert = ref<boolean>(false);

const handleMainFormFinish = () => {
    providersStore.clearIdToEdit();
    showMessageAlert.value = true;
};

const handleDestroyFormFinish = () => {
    providersStore.clearIdToDelete();
    showMessageAlert.value = true;
};

const providersStore = useProvidersStore();
providersStore.$subscribe((mutation, state) => {
    if (state.idToEdit !== null) {
        const provider = props.providers.find(p => p.id === state.idToEdit) || null;
        providerToEdit.value = provider;
    } else {
        providerToEdit.value = null;
    }

    if (state.idToDelete !== 0) {
        showDestroyAlert.value = true;
    } else {
        showDestroyAlert.value = false;
    }
});
</script>

<template>
    <Head title="Proveedores" />

    <AppLayout>
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <!-- Form box -->
            <div class="relative overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border p-4 space-y-4">
                <h2 class="font-medium text-lg">Registrar nuevo proveedor</h2>
                <Form
                    v-bind="providersStore.idToEdit === null ? ProviderController.store.form() : ProviderController.update.form(providersStore.idToEdit)"
                    :reset-on-success="true"
                    :onFinish="handleMainFormFinish"
                    v-slot="{ errors, processing }"
                    class="flex flex-col gap-4 w-full mt-4"
                >
                    <div class="flex gap-4 w-full items-start">
                        <div class="grid gap-2 w-full">
                            <Label for="name">Nombre<span class="text-red-500">*</span></Label>
                            <Input
                                id="name"
                                :model-value="providerToEdit?.name"
                                type="text"
                                required
                                autofocus
                                :tabindex="1"
                                autocomplete="name"
                                name="name"
                                placeholder="Nombre del proveedor"
                            />
                            <InputError :message="errors.name" />
                        </div>
                        <div class="grid gap-2 w-full">
                            <Label for="email">Correo electrónico</Label>
                            <Input
                                id="email"
                                :model-value="providerToEdit?.email"
                                type="email"
                                :tabindex="2"
                                autocomplete="email"
                                name="email"
                                placeholder="Correo electrónico del proveedor"
                            />
                            <InputError :message="errors.email" />
                        </div>
                        <div class="grid gap-2 w-full">
                            <Label for="phone">Teléfono</Label>
                            <Input
                                id="phone"
                                :model-value="providerToEdit?.phone"
                                type="tel"
                                :tabindex="3"
                                autocomplete="tel"
                                name="phone"
                                placeholder="Teléfono del proveedor"
                            />
                            <InputError :message="errors.phone" />
                        </div>
                        <div class="grid gap-2 w-full">
                            <Label for="address">Dirección</Label>
                            <Input
                                id="address"
                                :model-value="providerToEdit?.address"
                                type="text"
                                :tabindex="4"
                                autocomplete="address"
                                name="address"
                                placeholder="Dirección del proveedor"
                            />
                            <InputError :message="errors.address" />
                        </div>
                        <div class="grid gap-2 w-full">
                            <Label for="contact_person">Persona de Contacto</Label>
                            <Input
                                id="contact_person"
                                :model-value="providerToEdit?.contact_person"
                                type="text"
                                :tabindex="5"
                                autocomplete="contact_person"
                                name="contact_person"
                                placeholder="Persona de contacto del proveedor"
                            />
                            <InputError :message="errors.contact_person" />
                        </div>
                    </div>

                    <Button
                        type="submit"
                        class="w-full"
                        :tabindex="5"
                        :disabled="processing"
                        data-test="register-user-button"
                    >
                        <LoaderCircle
                            v-if="processing"
                            class="h-4 w-4 animate-spin"
                        />
                        {{ providersStore.idToEdit === null ? 'Guardar' : 'Actualizar' }}
                    </Button>
                </Form>
            </div>

            <!-- List box -->
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border p-4">
                <h2 class="font-medium text-lg">Proveedores</h2>
                <DataTable :columns="columns" :data="props.providers"/>
            </div>

            <!-- Dialog alert -->
            <AlertDialog v-model:open="showDestroyAlert">
                <AlertDialogContent>
                    <AlertDialogHeader>
                        <AlertDialogTitle>¿Estás seguro de eliminar este proveedor?</AlertDialogTitle>
                        <AlertDialogDescription>
                            Esta acción no se puede deshacer<br>Esto eliminará permanentemente el proveedor seleccionado
                        </AlertDialogDescription>
                    </AlertDialogHeader>
                    <AlertDialogFooter>
                        <AlertDialogCancel @click="providersStore.clearIdToDelete()">Cancelar</AlertDialogCancel>
                        <Form
                            v-bind="ProviderController.destroy.form({ id: providersStore.idToDelete })"
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
                        <AlertDialogAction @click="providersStore.clearIdToDelete()">Aceptar</AlertDialogAction>
                    </AlertDialogFooter>
                </AlertDialogContent>
            </AlertDialog>
        </div>
    </AppLayout>
</template>