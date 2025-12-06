<script setup>
import TextInput from './TextInput.vue';
import InputLabel from './InputLabel.vue';
import InputError from './InputError.vue';
import SelectedItems from './SelectedItems.vue';
import SearchResult from './SearchResult.vue';
import { ref } from 'vue';
import axios from 'axios';

const props = defineProps({
  routeToSearch: {
    type: String,
    required: true
  },
  paramsToSearch: {
    type: Array,
    required: true
  },
  /* userToken: {
    type: String,
    required: true
  }, */
  label: {
    type: String,
    required: true
  },
  placeholder: {
    type: String,
    default: 'Buscar'
  },
  haveMultipleResults: {
    type: Boolean,
    required: true
  },
  idName: {
    type: String,
    default: 'id'
  },
  displayFields: {
    type: Array,
    required: true
  },
  isFirstWordBold: {
    type: Boolean,
    default: false
  },
  minLengthOfQ: {
    type: Number,
    default: 6
  },
  type: {
    type: String,
    required: false
  }
})

const emit = defineEmits([
  'resultsSelected',
  'queryError'
])

const queryResults = ref()
const q = ref('')
const errorMessage = ref()
const selectedResults = ref([])
const showSelect = ref(false)

async function search(event) {
  if (q.value.length >= props.minLengthOfQ) {
    try {
      queryResults.value = await axios.get(props.routeToSearch, {
        params: {
          fields: props.paramsToSearch,
          q: q.value,
          type: props.type
        }/* ,
        headers: {
          Authorization: `Bearer ${props.userToken}`
        } */
      })
      showSelect.value = true
    } catch (error) {
      emit('queryError', error + "\nDeberías recargar la página")
    }
  }
}

function showResult(result) {
  if (props.haveMultipleResults) {
    if (!selectedResults.value.find((value) => value[props.idName] === result[props.idName])) {
      selectedResults.value.push(result)
    }
  } else {
    selectedResults.value = [result]
    showSelect.value = false
  }
  emit('resultsSelected', selectedResults)
}

function setTitle(result) {
  let title = ''
  props.displayFields.forEach((field, index) => {
    if (props.isFirstWordBold && index === 0)
      title += `<b>${result[field]}</b> `
    else
      title += `${result[field]} `
  })
  return title
}

function removeResult(resultId) {
  selectedResults.value = selectedResults.value.filter((result) => {
    return result[props.idName] != resultId
  })
  emit('resultsSelected', selectedResults)
}
</script>

<template>
  <div class="w-full mt-4 rounded-md p-2 shadow-md" @mouseleave="showSelect = false">
    <InputLabel :for="'search_input' + props.routeToSearch" :value="props.label" />
    <TextInput
        :id="'search_input' + props.routeToSearch"
        type="text"
        class="mt-1 block w-full"
        :placeholder="props.placeholder"
        v-model="q"
        @input="search"/>
    <InputError class="mt-2" :message="errorMessage" />

    <Transition
      enter-active-class="transition ease-out duration-200"
      enter-from-class="opacity-0 scale-95"
      enter-to-class="opacity-100 scale-100"
      leave-active-class="transition ease-in duration-75"
      leave-from-class="opacity-100 scale-100"
      leave-to-class="opacity-0 scale-95" >
        <SelectedItems v-if="showSelect" :results="queryResults.data" :display-fields="props.displayFields" :id-name="props.idName" @item-clicked="showResult" :is-first-word-bold="props.isFirstWordBold"/>
    </Transition>
    <div v-if="selectedResults.length > 0" class="flex flex-wrap gap-2 mt-2">
      <TransitionGroup
        enter-active-class="transition ease-out duration-200"
        enter-from-class="opacity-0 scale-95"
        enter-to-class="opacity-100 scale-100"
        leave-active-class="transition ease-in duration-75"
        leave-from-class="opacity-100 scale-100"
        leave-to-class="opacity-0 scale-95" >
          <SearchResult v-for="result in selectedResults" :key="result[props.idName]" :title="setTitle(result)" :result-id="result[props.idName]" :is-first-word-bold="props.isFirstWordBold" @removed-result="removeResult" />
      </TransitionGroup>
    </div>
  </div>
</template>
