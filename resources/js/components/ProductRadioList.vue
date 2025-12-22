<script setup lang="ts">

interface Product {
  id: string | number;
  name: string;
  code?: string;
  price?: number;
  [key: string]: any;
}

const props = defineProps<{
  products: Product[];
  name?: string;
  valueKey?: string;
  labelKey?: string;
  subLabelKey?: string | null;
  showPrice?: boolean;
  locale?: string;
  currency?: string;
  disabled?: boolean;
}>();

const model = defineModel<string | number | null>('modelValue', {
  type: [String, Number, null],
  required: true,
});

const valueKey = props.valueKey ?? 'id';
const labelKey = props.labelKey ?? 'name';
const subLabelKey = props.subLabelKey ?? 'code';
const showPrice = props.showPrice ?? false;
const locale = props.locale ?? 'es-419';
const currency = props.currency ?? 'USD';

function formatPrice(value: any) {
  const n = Number(value);
  if (Number.isNaN(n)) return '';
  try {
    return new Intl.NumberFormat(locale, { style: 'currency', currency }).format(n);
  } catch (e) {
    console.log('Error formatting price:', e);
    return n.toFixed(2);
  }
}
</script>

<template>
  <ul role="radiogroup" class="space-y-2">
    <li v-for="(p, index) in products" :key="`${p[valueKey]}-${index}`">
      <label class="group flex items-center justify-between w-full cursor-pointer rounded-md border px-3 py-2 transition-colors duration-150 has-checked:border-indigo-500 has-checked:bg-indigo-50">
        <div class="flex items-center gap-3">
          <div class="text-left">
            <div class="text-sm font-medium text-gray-900">{{ p[labelKey] }}</div>
            <div v-if="subLabelKey && p[subLabelKey]" class="text-xs text-gray-500">{{ p[subLabelKey] }}</div>
          </div>
        </div>

        <div class="flex items-center gap-4">
          <div v-if="showPrice && p.price !== undefined" class="text-sm text-gray-700">{{ formatPrice(p.price) }}</div>

          <input
            :autofocus="index === 0"
            type="radio"
            :value="p[valueKey]"
            v-model="model"
            :disabled="props.disabled"
          />
        </div>
      </label>
    </li>
  </ul>
</template>
