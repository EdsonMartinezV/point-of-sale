import { Provider } from '@/types/main'
import { ColumnDef } from '@tanstack/vue-table'
import DropdownAction from '@/components/tables/providers/data-table-dropdown.vue'
import { ArrowUpDown, ChevronDown, ChevronUp } from 'lucide-vue-next'
import Button from '@/components/ui/button/Button.vue'
import { h } from 'vue'

export const columns: ColumnDef<Provider>[] = [
  {
    accessorKey: 'name',
    header: ({ column }) => {
      return h(Button, {
        variant: 'ghost',
        onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
      }, () => ['Nombre', h(column.getIsSorted() ? (column.getIsSorted() === 'asc' ? ChevronUp : ChevronDown) : ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
    },
    cell: ({ row }) => {
        /* You can format here */
        return h('div', { class: 'text-left font-medium' }, row.getValue('name'))
    },
    sortingFn: 'alphanumeric',
  },
  {
    accessorKey: 'email',
    header: ({ column }) => {
      return h(Button, {
        variant: 'ghost',
        onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
      }, () => ['Correo Electrónico', h(column.getIsSorted() ? (column.getIsSorted() === 'asc' ? ChevronUp : ChevronDown) : ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
    },
    cell: ({ row }) => {
        return h('div', { class: 'text-left' }, row.getValue('email') || '—')
    },
    sortingFn: 'alphanumeric',
  },
  {
    accessorKey: 'phone',
    header: ({ column }) => {
      return h(Button, {
        variant: 'ghost',
        onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
      }, () => ['Teléfono', h(column.getIsSorted() ? (column.getIsSorted() === 'asc' ? ChevronUp : ChevronDown) : ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
    },
    cell: ({ row }) => {
        return h('div', { class: 'text-left' }, row.getValue('phone') || '—')
    },
    sortingFn: 'alphanumeric',
  },
  {
    accessorKey: 'address',
    header: ({ column }) => {
      return h(Button, {
        variant: 'ghost',
        onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
      }, () => ['Dirección', h(column.getIsSorted() ? (column.getIsSorted() === 'asc' ? ChevronUp : ChevronDown) : ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
    },
    cell: ({ row }) => {
        return h('div', { class: 'text-left' }, row.getValue('address') || '—')
    },
    sortingFn: 'alphanumeric',
  },
  {
    accessorKey: 'contact_person',
    header: ({ column }) => {
      return h(Button, {
        variant: 'ghost',
        onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
      }, () => ['Persona de Contacto', h(column.getIsSorted() ? (column.getIsSorted() === 'asc' ? ChevronUp : ChevronDown) : ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
    },
    cell: ({ row }) => {
        return h('div', { class: 'text-left' }, row.getValue('contact_person') || '—')
    },
    sortingFn: 'alphanumeric',
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