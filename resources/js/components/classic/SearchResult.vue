<script setup>
import { onMounted } from 'vue';
import { ref } from 'vue';

const props = defineProps({
  title: {
    type: String,
    required: true
  },
  subtitle: {
    type: String
  },
  resultId: {
    type: Number,
    required: true
  },
  isFirstWordBold: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['removedResult'])

const show = ref(true)

function remove() {
  show.value = false
  emit('removedResult', props.resultId)
};
</script>

<template>
  <span v-if="show" class="inline-flex items-center justify-center px-4 py-2 bg-gray-800 rounded-md text-s text-gray-200 hover:text-amber-300 transition ease-in-out duration-150">
    <span class="inline-flex flex-col items-center">
      <span v-if="props.isFirstWordBold" v-html="props.title"></span>
      <span v-else class="font-semibold" >{{ props.title }}</span>
      <span v-if="props.subtitle" class="text-s font-normal -mt-2">
        {{ props.subtitle }}
      </span>
    </span>
    <button class="ml-4 font-bold" @click.prevent="remove">X</button>
  </span>
</template>