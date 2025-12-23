import { Provider } from '@/types/main'
import { ColumnDef } from '@tanstack/vue-table'
import DropdownAction from '@/components/tables/providers/data-table-dropdown.vue'
import { ArrowUpDown } from 'lucide-vue-next'
import Button from '@/components/ui/button/Button.vue'
import { h } from 'vue'

export const columns: ColumnDef<Provider>[] = [
  {
    accessorKey: 'name',
    header: ({ column }) => {
      return h(Button, {
        variant: 'ghost',
        onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
      }, () => ['Nombre', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
    },
    cell: ({ row }) => {
        /* You can format here */
        return h('div', { class: 'text-left font-medium' }, row.getValue('name'))
    },
  },
  {
    accessorKey: 'email',
    header: ({ column }) => {
      return h(Button, {
        variant: 'ghost',
        onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
      }, () => ['Correo Electrónico', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
    },
    cell: ({ row }) => {
        return h('div', { class: 'text-left' }, row.getValue('email') || '—')
    },
  },
  {
    accessorKey: 'phone',
    header: ({ column }) => {
      return h(Button, {
        variant: 'ghost',
        onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
      }, () => ['Teléfono', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
    },
    cell: ({ row }) => {
        return h('div', { class: 'text-left' }, row.getValue('phone') || '—')
    },
  },
  {
    accessorKey: 'address',
    header: ({ column }) => {
      return h(Button, {
        variant: 'ghost',
        onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
      }, () => ['Dirección', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
    },
    cell: ({ row }) => {
        return h('div', { class: 'text-left' }, row.getValue('address') || '—')
    },
  },
  {
    accessorKey: 'contact_person',
    header: ({ column }) => {
      return h(Button, {
        variant: 'ghost',
        onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
      }, () => ['Persona de Contacto', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
    },
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
    },
  }
]