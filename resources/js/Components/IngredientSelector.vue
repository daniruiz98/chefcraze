<template>
    <div>
        <v-select v-model="selectedIngredient" :options="filteredIngredients" placeholder="Selecciona un ingrediente">
            <template #option="{ option }">
                {{ option.name }}
            </template>
        </v-select>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const selectedIngredient = ref(null);
const ingredients = []; // Aquí irán los datos de tus ingredientes

const filteredIngredients = computed(() => {
    return ingredients.filter(ingredient =>
        ingredient.name.toLowerCase().includes(selectedIngredient.value?.toLowerCase() ?? '')
    );
});
</script>
