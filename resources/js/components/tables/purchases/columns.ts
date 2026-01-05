import { Provider, Purchase, PurchaseItem } from '@/types/main'
import { ColumnDef } from '@tanstack/vue-table'
import DropdownAction from '@/components/tables/purchases/data-table-dropdown.vue'
import { ArrowUpDown, Check, ChevronDown, ChevronUp, X } from 'lucide-vue-next'
import Button from '@/components/ui/button/Button.vue'
import { h } from 'vue'

export const columns: ColumnDef<Purchase>[] = [
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
    accessorKey: 'provider',
    header: ({ column }) => {
      return h(Button, {
        variant: 'ghost',
        onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
      }, () => ['Proveedor', h(column.getIsSorted() ? (column.getIsSorted() === 'asc' ? ChevronUp : ChevronDown) : ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
    },
    cell: ({ row }) => {
        /* You can format here */
        const provider:Provider = row.getValue('provider');

        return h('div', { class: 'text-center font-medium' }, provider.name || '—')
    },
    sortingFn: 'alphanumeric',
  },
  {
    accessorKey: 'quantity',
    header: ({ column }) => {
      return h(Button, {
        variant: 'ghost',
        onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
      }, () => ['Cantidad', h(column.getIsSorted() ? (column.getIsSorted() === 'asc' ? ChevronUp : ChevronDown) : ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
    },
    cell: ({ row }) => {
        /* You can format here */
        const purchaseItems:PurchaseItem[] = row.original.purchase_items;
        const firstItem = purchaseItems[0];

        return h('div', { class: 'text-right font-medium' }, firstItem.quantity)
    },
    sortingFn: 'alphanumeric',
  },
  {
    accessorKey: 'product',
    header: ({ column }) => {
      return h(Button, {
        variant: 'ghost',
        onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
      }, () => ['Producto', h(column.getIsSorted() ? (column.getIsSorted() === 'asc' ? ChevronUp : ChevronDown) : ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
    },
    cell: ({ row }) => {
        /* You can format here */
        const purchaseItems:PurchaseItem[] = row.original.purchase_items;
        const firstItem = purchaseItems[0];

        return h('div', { class: 'text-center font-medium' }, firstItem.product.name || '—')
    },
    sortingFn: 'alphanumeric',
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
        /* You can format here */
        const purchaseItems:PurchaseItem[] = row.original.purchase_items;
        const firstItem = purchaseItems[0];

        return h(firstItem.price_modification.sold_by_retail ? Check : X, { class: 'm-auto' })
    },
    sortingFn: 'alphanumeric',
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
        const purchaseItems:PurchaseItem[] = row.original.purchase_items;
        const firstItem = purchaseItems[0];

        return h('div', { class: 'text-right font-medium' }, firstItem.price_modification.retail_units_per_box || '—')
    },
    sortingFn: 'alphanumeric',
  },
  {
    accessorKey: 'remaining_stock',
    header: ({ column }) => {
      return h(Button, {
        variant: 'ghost',
        onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
      }, () => ['Unidades disponibles', h(column.getIsSorted() ? (column.getIsSorted() === 'asc' ? ChevronUp : ChevronDown) : ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
    },
    cell: ({ row }) => {
        /* You can format here */
        const purchaseItems:PurchaseItem[] = row.original.purchase_items;
        const firstItem = purchaseItems[0];

        return h('div', { class: 'text-right font-medium' }, firstItem.price_modification.remaining_stock || '—')
    },
    sortingFn: 'alphanumeric',
  },
  {
    accessorKey: 'retail_remaining_stock',
    header: ({ column }) => {
      return h(Button, {
        variant: 'ghost',
        onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
      }, () => ['Unidades sueltas disponibles', h(column.getIsSorted() ? (column.getIsSorted() === 'asc' ? ChevronUp : ChevronDown) : ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
    },
    cell: ({ row }) => {
        /* You can format here */
        const purchaseItems:PurchaseItem[] = row.original.purchase_items;
        const firstItem = purchaseItems[0];

        return h('div', { class: 'text-right font-medium' }, firstItem.price_modification.retail_remaining_stock || '—')
    },
    sortingFn: 'alphanumeric',
  },
  {
    accessorKey: 'cost_price',
    header: ({ column }) => {
      return h(Button, {
        variant: 'ghost',
        onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
      }, () => ['Precio de costo', h(column.getIsSorted() ? (column.getIsSorted() === 'asc' ? ChevronUp : ChevronDown) : ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
    },
    cell: ({ row }) => {
        /* You can format here */
        const purchaseItems:PurchaseItem[] = row.original.purchase_items;
        const firstItem = purchaseItems[0];

        const cost_price = firstItem.price_modification.cost_price
        const formatted = new Intl.NumberFormat('es-MX', {
          style: 'currency',
          currency: 'MXN',
        }).format(cost_price)

        return h('div', { class: 'text-right font-medium' }, formatted || '—')
    },
    sortingFn: 'alphanumeric',
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
        const purchaseItems:PurchaseItem[] = row.original.purchase_items;
        const firstItem = purchaseItems[0];

        const first_wholesale_percentage = firstItem.price_modification.first_wholesale_percentage;
        const formatted = new Intl.NumberFormat('es-MX', {
          style: 'percent',
          minimumFractionDigits: 0,
        }).format(first_wholesale_percentage);

        return h('div', { class: 'text-right font-medium' }, formatted || '—')
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
        const purchaseItems:PurchaseItem[] = row.original.purchase_items;
        const firstItem = purchaseItems[0];

        const second_wholesale_percentage = firstItem.price_modification.second_wholesale_percentage;
        const formatted = new Intl.NumberFormat('es-MX', {
          style: 'percent',
          minimumFractionDigits: 0,
        }).format(second_wholesale_percentage);

        return h('div', { class: 'text-right font-medium' }, formatted || '—')
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
        const purchaseItems:PurchaseItem[] = row.original.purchase_items;
        const firstItem = purchaseItems[0];

        const third_wholesale_percentage = firstItem.price_modification.third_wholesale_percentage;
        const formatted = new Intl.NumberFormat('es-MX', {
          style: 'percent',
          minimumFractionDigits: 0,
        }).format(third_wholesale_percentage);

        return h('div', { class: 'text-right font-medium' }, formatted || '—')
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
        const purchaseItems:PurchaseItem[] = row.original.purchase_items;
        const firstItem = purchaseItems[0];

        const retail_percentage = firstItem.price_modification.retail_percentage;
        const formatted = new Intl.NumberFormat('es-MX', {
          style: 'percent',
          minimumFractionDigits: 0,
        }).format(retail_percentage);

        return h('div', { class: 'text-right font-medium' }, retail_percentage != null ? formatted : '—')
    },
    sortingFn: 'alphanumeric',
  },
  {
    accessorKey: 'created_at',
    header: ({ column }) => {
      return h(Button, {
        variant: 'ghost',
        onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
      }, () => ['Fecha de compra', h(column.getIsSorted() ? (column.getIsSorted() === 'asc' ? ChevronUp : ChevronDown) : ArrowUpDown, { class: 'ml-2 h-4 w-4' })])
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
        const purchase = row.original
        return h('div', { class: 'text-center' }, h(DropdownAction,  {
            purchaseId: purchase.id
        }))
    },
  }
]