import { MeasureUnit } from '@/types/main'
import { ColumnDef } from '@tanstack/vue-table'
import DropdownAction from '@/components/tables/measureUnits/data-table-dropdown.vue'
import { ArrowUpDown, ChevronDown, ChevronUp } from 'lucide-vue-next'
import Button from '@/components/ui/button/Button.vue'
import { h } from 'vue'

export const columns: ColumnDef<MeasureUnit>[] = [
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
    accessorKey: 'abbreviation',
    header: ({ column }) => {
      return h(Button, {
        variant: 'ghost',
        onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
      }, () => ['Abreviatura', h(column.getIsSorted() ? (column.getIsSorted() === 'asc' ? ChevronUp : ChevronDown) : ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
    },
    cell: ({ row }) => {
        return h('div', { class: 'text-left' }, row.getValue('abbreviation') || '—')
    },
    sortingFn: 'alphanumeric',
  },
  {
    accessorKey: 'actions',
    enableHiding: false,
    header: () => h('div', { class: '' }, ''),
    cell: ({ row }) => {
        const measureUnit = row.original
        return h('div', { class: 'text-center' }, h(DropdownAction,  {
            measureUnitId: measureUnit.id
        }))
    },
  }
]