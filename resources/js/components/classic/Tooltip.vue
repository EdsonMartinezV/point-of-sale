<script setup>
import { ref } from 'vue';

const props = defineProps({
  hintText: {
    type: String,
    default: '?'
  },
  text: {
    type: String,
    required: true
  }
})

const show = ref(false);
</script>

<template>
  <span class="border-gray-800 text-gray-800 border-solid border rounded-full px-1 text-xs h-4 leading-[0.92rem]" @mouseover="show = true" @mouseleave="show = false">
    {{ props.hintText }}
    <Transition
      enter-active-class="transition ease-out duration-200"
      enter-from-class="opacity-0 scale-95"
      enter-to-class="opacity-100 scale-100"
      leave-active-class="transition ease-in duration-75"
      leave-from-class="opacity-100 scale-100"
      leave-to-class="opacity-0 scale-95" >
        <span v-if="show" class="text-gray-800">{{ props.text }}</span>
    </Transition>
  </span>
</template>
