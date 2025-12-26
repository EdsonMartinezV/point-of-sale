import { Category } from '@/types/main'
import { ColumnDef } from '@tanstack/vue-table'
import DropdownAction from '@/components/tables/categories/data-table-dropdown.vue'
import { ArrowUpDown } from 'lucide-vue-next'
import Button from '@/components/ui/button/Button.vue'
import { h } from 'vue'

export const columns: ColumnDef<Category>[] = [
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
    accessorKey: 'description',
    header: ({ column }) => {
      return h(Button, {
        variant: 'ghost',
        onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
      }, () => ['Descripción', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
    },
    cell: ({ row }) => {
        return h('div', { class: 'text-left' }, row.getValue('description') || '—')
    },
  },
  {
    accessorKey: 'actions',
    enableHiding: false,
    header: () => h('div', { class: '' }, ''),
    cell: ({ row }) => {
        const category = row.original
        return h('div', { class: 'text-center' }, h(DropdownAction,  {
            categoryId: category.id
        }))
    },
  }
]