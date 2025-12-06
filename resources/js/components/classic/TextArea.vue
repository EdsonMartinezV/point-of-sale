<script setup>
import { onMounted, ref } from 'vue';

const props = defineProps({
    rows: {
        type: Number,
        default: 5
    }
})

const model = defineModel({
    type: [String, null],
    required: true,
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <textarea
        class="border-gray-300 focus:border-gray-800 focus:ring-gray-800 rounded-md shadow-sm"
        :rows="props.rows"
        v-model="model"
        ref="input">
    </textarea>
</template>
