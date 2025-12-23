import { defineStore } from 'pinia';
import { ref } from 'vue';

export const useProvidersStore = defineStore('providers', () => {
    const idToEdit = ref<string | number | null>(null);
    const idToDelete = ref<string | number>(0);

    function setIdToEdit(id: string | number | null) {
        idToEdit.value = id; 
    }

    function setIdToDelete(id: string | number) {
        idToDelete.value = id;
    }

    function clearIdToEdit() {
        idToEdit.value = null;
    }

    function clearIdToDelete() {
        idToDelete.value = 0;
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