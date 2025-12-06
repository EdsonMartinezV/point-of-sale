<script setup>
const props = defineProps({
  items: {
    type: Array,
    default: ['Si', 'No']
  }
})

const model = defineModel({
  type: [String, Boolean, Number]
});
</script>

<template>
  <div class="flexjustify-center">
    <button v-for="item in props.items" :key="item.key"
      @click="model = item.key"
      class="bg-slate-200 border-2 border-gray-800 border-dashed p-4 py-2 first:rounded-s-xl first:border-e-0 last:rounded-e-xl last:border-s-0 hover:bg-slate-300 transition-all ease-in-out duration-200 active:bg-slate-400"
      :class="[model === item.key ? '!bg-gray-800 text-gray-100' : '']">
      {{ item.label }}
    </button>
  </div>
</template>