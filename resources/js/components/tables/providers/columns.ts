import { Provider } from '@/types/main'
import { ColumnDef } from '@tanstack/vue-table'
import DropdownAction from '@/components/tables/providers/data-table-dropdown.vue'
import { h } from 'vue'

export const columns: ColumnDef<Provider>[] = [
  {
    accessorKey: 'name',
    header: () => h('div', { class: 'text-left font-bold text-base' }, 'Nombre'),
    cell: ({ row }) => {
        /* You can format here */
        return h('div', { class: 'text-left font-medium' }, row.getValue('name'))
    },
  },
  {
    accessorKey: 'email',
    header: () => h('div', { class: 'text-left font-bold text-base' }, 'Correo Electrónico'),
    cell: ({ row }) => {
        return h('div', { class: 'text-left' }, row.getValue('email') || '—')
    },
  },
  {
    accessorKey: 'phone',
    header: () => h('div', { class: 'text-left font-bold text-base' }, 'Teléfono'),
    cell: ({ row }) => {
        return h('div', { class: 'text-left' }, row.getValue('phone') || '—')
    },
  },
  {
    accessorKey: 'address',
    header: () => h('div', { class: 'text-left font-bold text-base' }, 'Dirección'),
    cell: ({ row }) => {
        return h('div', { class: 'text-left' }, row.getValue('address') || '—')
    },
  },
  {
    accessorKey: 'contact_person',
    header: () => h('div', { class: 'text-left font-bold text-base' }, 'Persona de Contacto'),
    cell: ({ row }) => {
        return h('div', { class: 'text-left' }, row.getValue('contact_person') || '—')
    },
  },
  {
    accessorKey: 'actions',
    enableHiding: false,
    header: () => h('div', { class: '' }, ''),
    cell: ({ row }) => {
        const provider = row.original
        return h('div', { class: 'text-center' }, h(DropdownAction,  {
            providerId: provider.id
        }))
        /* return h('div', { class: 'flex items-center justify-center gap-2' }, [
            h('button', {
                class: 'text-xs bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded',
                onClick: () => console.log('Edit provider', provider.id)
            }, 'Editar'),
            h('button', {
                class: 'text-xs bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded',
                onClick: () => console.log('Delete provider', provider.id)
            }, 'Eliminar')
        ]) */
    },
  }
]