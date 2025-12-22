import { defineStore } from 'pinia';
import { ref } from 'vue';

export const useProvidersStore = defineStore('providers', () => {
    const idToEdit = ref<string | number | null>(null);
    const idToDelete = ref<string | number | null>(null);

    function setIdToEdit(id: string | number | null) {
        idToEdit.value = id; 
    }

    function setIdToDelete(id: string | number | null) {
        idToDelete.value = id;
    }

    function clearIdToEdit() {
        idToEdit.value = null;
    }

    function clearIdToDelete() {
        idToDelete.value = null;
    }

    return {
        idToEdit,
        idToDelete,
        setIdToEdit,
        setIdToDelete,
        clearIdToEdit,
        clearIdToDelete
    }
})