import { Sale } from '@/types/main'
import { ColumnDef } from '@tanstack/vue-table'
import DropdownAction from '@/components/tables/sales/data-table-dropdown.vue'
import { ArrowUpDown, ChevronDown, ChevronUp } from 'lucide-vue-next'
import Button from '@/components/ui/button/Button.vue'
import { h } from 'vue'

export const columns: ColumnDef<Sale>[] = [
  {
    accessorKey: 'total',
    header: ({ column }) => {
      return h(Button, {
        variant: 'ghost',
        onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
      }, () => ['Total', h(column.getIsSorted() ? (column.getIsSorted() === 'asc' ? ChevronUp : ChevronDown) : ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
    },
    cell: ({ row }) => {
        /* You can format here */
        const total = Number.parseFloat(row.getValue('total'))
        const formatted = new Intl.NumberFormat('es-MX', {
          style: 'currency',
          currency: 'MXN',
        }).format(total)

        return h('div', { class: 'text-right font-medium' }, formatted)
    },
  },
  {
    accessorKey: 'created_by',
    header: ({ column }) => {
      return h(Button, {
        variant: 'ghost',
        onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
      }, () => ['Registrado por', h(column.getIsSorted() ? (column.getIsSorted() === 'asc' ? ChevronUp : ChevronDown) : ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
    },
    cell: ({ row }) => {
        /* You can format here */
        return h('div', { class: 'text-center font-medium' }, row.getValue('created_by'))
    },
    sortingFn: 'alphanumeric',
  },
  {
    accessorKey: 'client',
    header: ({ column }) => {
      return h(Button, {
        variant: 'ghost',
        onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
      }, () => ['Cliente', h(column.getIsSorted() ? (column.getIsSorted() === 'asc' ? ChevronUp : ChevronDown) : ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
    },
    cell: ({ row }) => {
        /* You can format here */

        return h('div', { class: 'text-center font-medium' }, row.getValue('client') || '—')
    },
    sortingFn: 'alphanumeric',
  },
  {
    accessorKey: 'created_at',
    header: ({ column }) => {
      return h(Button, {
        variant: 'ghost',
        onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
      }, () => ['Fecha de venta', h(column.getIsSorted() ? (column.getIsSorted() === 'asc' ? ChevronUp : ChevronDown) : ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
    },
    cell: ({ row }) => {
        return h('div', { class: 'text-right' }, row.getValue('created_at') || '—')
    },
    sortingFn: 'datetime',
  },
  {
    accessorKey: 'actions',
    enableHiding: false,
    header: () => h('div', { class: '' }, ''),
    cell: ({ row }) => {
        const sale = row.original
        return h('div', { class: 'text-center' }, h(DropdownAction,  {
            saleId: sale.id
        }))
    },
  }
]