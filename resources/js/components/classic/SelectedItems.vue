<script setup>
const props = defineProps({
  results: {
    type: Array,
    required: true
  },
  displayFields: {
    type: Array,
    required: true
  },
  idName: {
    type: String, Number,
    required:true
  },
  isFirstWordBold: {
    type: Boolean,
    default: false
  }
});

const emit = defineEmits(['itemClicked']);

function setTitle(result) {
  let title = ''
  props.displayFields.forEach((field, index) => {
    if (props.isFirstWordBold && index === 0)
      title += `<b>${result[field]}</b> `
    else
      title += `${result[field]} `
  })

  return title
};
</script>

<template>
    <div class="absolute z-50 mt-1 rounded-md shadow-md overflow-y-auto bg-gray-200 text-gray-800 h-fit max-h-60">
        <div v-for="result in props.results" :key="result[props.idName]" @click="$emit('itemClicked', result)" class="ring-1 ring-black ring-opacity-5 p-2 hover:bg-gray-800 hover:text-gray-200">
          <span v-html="setTitle(result)"></span>
        </div>
    </div>
</template>