<script setup lang="ts">
import { onMounted } from 'vue';


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
  fromSalesPage?: boolean;
}>();

const model = defineModel<string | number | null>('modelValue', {
  type: [String, Number, null],
  required: true,
});

const valueKey = props.valueKey ?? 'id';
const labelKey = props.labelKey ?? 'name';
const subLabelKey = props.subLabelKey ?? 'code';
const showPrice = props.showPrice ?? false;

const moneyFormat = (amount: number | undefined) => {
    return new Intl.NumberFormat('es-MX', {
        style: 'currency',
        currency: 'MXN',
    }).format(amount ?? 0)
}

const handleSelectFromSales = (event: KeyboardEvent, value: string | number) => {
  if (event.key === 'Enter') {
    model.value = value;
  }
};

onMounted(() => {
  const radios = document.querySelectorAll('input[type="radio"]') as NodeListOf<HTMLInputElement>;

  /* TO DO: set focus on first radio when fromSalesPage is true */
  if (model.value !== null) {
    radios.forEach((radio) => {
      if (radio.value === String(model.value)) {
        radio.focus();
      }
    })
  }

  if (props.fromSalesPage) {
    radios[0]?.focus();
  }
})
</script>

<template>
  <ul role="radiogroup" class="space-y-2">
    <li v-for="(p, index) in products" :key="`${p[valueKey]}-${index}`">
      <label class="group flex items-center justify-between w-full cursor-pointer rounded-md border px-3 py-2 transition-colors duration-150 has-checked:border-indigo-500 has-checked:bg-indigo-50 has-focus:border-gray-500 has-focus:bg-gray-100">
        <div class="flex items-center gap-3">
          <div class="text-left">
            <div class="text-sm font-medium text-gray-900">{{ p[labelKey] }}</div>
            <div v-if="subLabelKey && p[subLabelKey]" class="text-xs text-gray-500">{{ p[subLabelKey] }}</div>
            <div v-if="showPrice" class="text-sm text-gray-700">{{ moneyFormat(p.first_wholesale_price) }}</div>
          </div>
        </div>

        <div class="flex items-center gap-4">

          <input
            v-if="props.fromSalesPage"
            :ref="`radio-${index}`"
            :tabindex="index + 2"
            type="radio"
            :value="p[valueKey]"
            v-model="model"
            :disabled="props.disabled"
            @keydown="handleSelectFromSales($event, p[valueKey])"
            @click.prevent
          />
          <input
            v-else
            :ref="`radio-${index}`"
            :tabindex="index + 2"
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
