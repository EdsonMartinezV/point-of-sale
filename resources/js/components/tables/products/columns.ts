import { Product } from '@/types/main'
import { ColumnDef } from '@tanstack/vue-table'
import DropdownAction from '@/components/tables/products/data-table-dropdown.vue'
import { ArrowUpDown, ChevronDown, ChevronUp, X, Check } from 'lucide-vue-next'
import Button from '@/components/ui/button/Button.vue'
import { h } from 'vue'
import { sortingFn } from '@/lib/utils'

export const columns: ColumnDef<Product>[] = [
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
    sortUndefined: 'last',
  },
  {
    accessorKey: 'sold_by_retail',
    header: ({ column }) => {
      return h(Button, {
        variant: 'ghost',
        onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
      }, () => ['Vendido al menudeo', h(column.getIsSorted() ? (column.getIsSorted() === 'asc' ? ChevronUp : ChevronDown) : ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
    },
    cell: ({ row }) => {
        return h(row.getValue('sold_by_retail') ? Check : X, { class: 'm-auto' });
    },
  },
  {
    accessorKey: 'retail_units_per_box',
    header: ({ column }) => {
      return h(Button, {
        variant: 'ghost',
        onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
      }, () => ['Unidades por caja', h(column.getIsSorted() ? (column.getIsSorted() === 'asc' ? ChevronUp : ChevronDown) : ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
    },
    cell: ({ row }) => {
        /* You can format here */
        return h('div', { class: 'text-right font-medium' }, row.getValue('retail_units_per_box'))
    },
  },
  {
    accessorKey: 'cost_price',
    header: ({ column }) => {
      return h(Button, {
        variant: 'ghost',
        onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
      }, () => ['Costo', h(column.getIsSorted() ? (column.getIsSorted() === 'asc' ? ChevronUp : ChevronDown) : ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
    },
    cell: ({ row }) => {
        /* You can format here */
        const cost_price = Number.parseFloat(row.getValue('cost_price'))
        const formatted = new Intl.NumberFormat('es-MX', {
          style: 'currency',
          currency: 'MXN',
        }).format(cost_price)

        return h('div', { class: 'text-right font-medium' }, cost_price ? formatted : '-');
    },
  },
  {
    accessorKey: 'first_wholesale_percentage',
    header: ({ column }) => {
      return h(Button, {
        variant: 'ghost',
        onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
      }, () => ['Margen 1', h(column.getIsSorted() ? (column.getIsSorted() === 'asc' ? ChevronUp : ChevronDown) : ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
    },
    cell: ({ row }) => {
        /* You can format here */
        const first_wholesale_percentage = Number.parseFloat(row.getValue('first_wholesale_percentage'));
        const formatted = new Intl.NumberFormat('es-MX', {
          style: 'percent',
          minimumFractionDigits: 0,
        }).format(first_wholesale_percentage);

        return h('div', { class: 'text-right font-medium' }, first_wholesale_percentage ? formatted : '-')
    },
    sortingFn: 'alphanumeric',
  },
  {
    accessorKey: 'second_wholesale_percentage',
    header: ({ column }) => {
      return h(Button, {
        variant: 'ghost',
        onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
      }, () => ['Margen 2', h(column.getIsSorted() ? (column.getIsSorted() === 'asc' ? ChevronUp : ChevronDown) : ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
    },
    cell: ({ row }) => {
        /* You can format here */
        const second_wholesale_percentage = Number.parseFloat(row.getValue('second_wholesale_percentage'));
        const formatted = new Intl.NumberFormat('es-MX', {
          style: 'percent',
          minimumFractionDigits: 0,
        }).format(second_wholesale_percentage);

        return h('div', { class: 'text-right font-medium' }, second_wholesale_percentage ? formatted : '-');
    },
    sortingFn: 'alphanumeric',
  },
  {
    accessorKey: 'third_wholesale_percentage',
    header: ({ column }) => {
      return h(Button, {
        variant: 'ghost',
        onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
      }, () => ['Margen 3', h(column.getIsSorted() ? (column.getIsSorted() === 'asc' ? ChevronUp : ChevronDown) : ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
    },
    cell: ({ row }) => {
        /* You can format here */
        const third_wholesale_percentage = Number.parseFloat(row.getValue('third_wholesale_percentage'));
        const formatted = new Intl.NumberFormat('es-MX', {
          style: 'percent',
          minimumFractionDigits: 0,
        }).format(third_wholesale_percentage);

        return h('div', { class: 'text-right font-medium' }, third_wholesale_percentage ? formatted : '-');
    },
    sortingFn: 'alphanumeric',
  },
  {
    accessorKey: 'retail_percentage',
    header: ({ column }) => {
      return h(Button, {
        variant: 'ghost',
        onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
      }, () => ['Margen al menudeo', h(column.getIsSorted() ? (column.getIsSorted() === 'asc' ? ChevronUp : ChevronDown) : ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
    },
    cell: ({ row }) => {
        /* You can format here */
        const retail_percentage = Number.parseFloat(row.getValue('retail_percentage'));
        const formatted = new Intl.NumberFormat('es-MX', {
          style: 'percent',
          minimumFractionDigits: 0,
        }).format(retail_percentage);

        return h('div', { class: 'text-right font-medium' }, retail_percentage ? formatted : '-');
    },
    sortingFn: 'alphanumeric',
  },
  {
    accessorKey: 'category',
    header: ({ column }) => {
      return h(Button, {
        variant: 'ghost',
        onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
      }, () => ['Categoría', h(column.getIsSorted() ? (column.getIsSorted() === 'asc' ? ChevronUp : ChevronDown) : ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
    },
    cell: ({ row }) => {
        /* You can format here */
        const category:{
          id: string | number,
          name: string
          description: string | null
        } = row.getValue('category');

        return h('div', { class: 'text-right font-medium' }, category.name);
    },
    sortingFn
  },
  {
    accessorKey: 'measure_unit',
    header: ({ column }) => {
      return h(Button, {
        variant: 'ghost',
        onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
      }, () => ['Presentación al mayoreo', h(column.getIsSorted() ? (column.getIsSorted() === 'asc' ? ChevronUp : ChevronDown) : ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
    },
    cell: ({ row }) => {
        /* You can format here */
        const measure_unit:{
          id: string | number,
          name: string,
          abbreviation: string
        } = row.getValue('measure_unit');

        return h('div', { class: 'text-right font-medium' }, measure_unit?.name)
    },
    sortingFn
  },
  {
    accessorKey: 'retail_measure_unit',
    header: ({ column }) => {
      return h(Button, {
        variant: 'ghost',
        onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
      }, () => ['Presentación al menudeo', h(column.getIsSorted() ? (column.getIsSorted() === 'asc' ? ChevronUp : ChevronDown) : ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
    },
    cell: ({ row }) => {
        /* You can format here */
        const retail_measure_unit:{
          id: string | number,
          name: string,
          abbreviation: string
        } = row.getValue('retail_measure_unit');

        return h('div', { class: 'text-right font-medium' }, retail_measure_unit?.name)
    },
    sortingFn
  },
  {
    accessorKey: 'actions',
    enableHiding: false,
    header: () => h('div', { class: '' }, ''),
    cell: ({ row }) => {
        const product = row.original
        return h('div', { class: 'text-center' }, h(DropdownAction,  {
            productId: product.id
        }))
    },
  }
]