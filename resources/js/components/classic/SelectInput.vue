<script setup>
const props = defineProps({
  isFromDb: {
    type: Boolean,
    default: true
  },
  items: {
    type: Array,
    required: true
  },
  idName: {
    type: String,
    default: 'id',
  },
  displayFields: {
    type: Array,
    required: false
  }
})

const model = defineModel({
  type: [Number, null, String],
  required: true
})

const getDisplayLabel = (item) => {
  let label = ''
  if (props.isFromDb) {
    props.displayFields.forEach((field) => {
      label += item[field] + ' '
    })
  }

  return label
};
</script>

<template>
  <select v-if="props.isFromDb" v-model.number="model" class="border-gray-300 focus:border-gray-800 focus:ring-gray-800 rounded-md shadow-sm">
    <option v-for="item in props.items" :key="item[props.idName]" :value="item[props.idName]" class="ring-1 ring-black ring-opacity-5 p-2 hover:bg-indigo-100">{{ getDisplayLabel(item) }}</option>
  </select>
  <select v-else v-model="model" class="border-gray-300 focus:border-gray-800 focus:ring-gray-800 rounded-md shadow-sm">
    <option v-for="item in props.items" :key="item" :value="item" class="ring-1 ring-black ring-opacity-5 p-2 hover:bg-indigo-100 capitalize">{{ item }}</option>
  </select>
</template>