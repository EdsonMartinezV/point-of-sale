import { defineStore } from 'pinia';
import { ref } from 'vue';

export const usePurchasesStore = defineStore('purchases', () => {
    const idToShow = ref<string | number | null>(null);
    const idToEdit = ref<string | number | null>(null);
    const idToDelete = ref<string | number>(0);

    function setIdToShow(id: string | number | null) {
        idToShow.value = id; 
    }

    function setIdToEdit(id: string | number | null) {
        idToEdit.value = id; 
    }

    function setIdToDelete(id: string | number) {
        idToDelete.value = id;
    }

    function clearIdToShow() {
        idToShow.value = null;
    }

    function clearIdToEdit() {
        idToEdit.value = null;
    }

    function clearIdToDelete() {
        idToDelete.value = 0;
    }

    return {
        idToShow,
        idToEdit,
        idToDelete,
        setIdToShow,
        setIdToEdit,
        setIdToDelete,
        clearIdToShow,
        clearIdToEdit,
        clearIdToDelete
    }
})