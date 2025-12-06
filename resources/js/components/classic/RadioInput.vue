<script setup>
const props = defineProps({
  value: {
    type: [Number, String, Boolean],
    required: true
  }
})

const model = defineModel({
  type: [Number, String, null, Boolean],
  required: true
});
</script>

<template>
  <input
    type="radio"
    v-model="model"
    :value="props.value"
    class="border-gray-300 text-gray-800 shadow-sm focus:ring-gray-800" >
</template>