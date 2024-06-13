<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {Head, Link, useForm, usePage} from '@inertiajs/vue3';
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import {ref} from 'vue';
import PrimaryButton from "@/Components/PrimaryButton.vue";
import FileInput from "@/Components/FileInput.vue";
import BackButton from "@/Components/BackButton.vue";


const user = ref(usePage().props.auth.user.id);
const ingredients = usePage().props.ingredients;

const selectedIngredient = ref(null);
const selectedIngredients = ref([]);
const selectedUnit = ref(null);
const quantity = ref(null);


const initialValues = {
    user_id: user.value,
    title: "",
    description: "",
    avatar: "",
    time: "00:00",
    ingredients: [],
    custom_ingredients: "",
    instructions: "",
    difficulty: "Media",
    removed: 0
}

const form = useForm(initialValues);

const onSelectAvatar = (e) => {
    const files = e.target.files;
    if(files.length){
        form.avatar = files[0]
    }
}


const addIngredient = () => {
    if (selectedIngredient.value && selectedUnit.value && quantity.value) {
        let ingredient_name;
        for(const ingredient of ingredients){
            if(selectedIngredient.value === ingredient.id){
                ingredient_name = ingredient.name;
            }
        }
        selectedIngredients.value.push({
            id: selectedIngredient.value,
            name: ingredient_name ? ingredient_name : null,
            quantity: quantity.value,
            unit: selectedUnit.value
        });

        selectedIngredient.value = null;
        selectedUnit.value = null;
        quantity.value = null;
    }

};

const removeIngredient = (index) => {
    console.log(index)
    selectedIngredients.value.splice(index, 1);

};

const submit = () => {
   form.ingredients = selectedIngredients.value
   form.post(window.route('recipes.store'))
}

</script>

<template>
    <AppLayout title="">
        <template #header>
          <BackButton />
        </template>

        <div class="py-7">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class=" flex justify-center bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <form  enctype="multipart/form-data" class="w-2/3 py-5  mt-7 space-y-3" @submit.prevent="submit">
                        <div>
                            <InputLabel for="title" value="Título" />
                            <TextInput
                                id="title"
                                v-model="form.title"
                                type="text"
                                class="mt-1 block w-full"
                                required
                                autofocus
                                autocomplete="title"
                                placeholder="Título"
                            />
                            <InputError class="mt-2" :message="form.errors.title" />
                        </div>
                        <div>
                            <InputLabel for="description" value="Descripción" />
                            <TextInput
                                id="description"
                                v-model="form.description"
                                type="text"
                                class="mt-1 block w-full"
                                required
                                autofocus
                                autocomplete="description"
                                placeholder="Descripción..."
                            />
                            <InputError class="mt-2" :message="form.errors.description" />
                        </div>
                        <div>

                        </div>
                        <div>
                            <InputLabel for="time" value="Duración" />
                            <TextInput
                                id="time"
                                v-model="form.time"
                                type="time"
                                class="mt-1 block w-full"
                                required
                                autofocus
                                autocomplete="time"
                                placeholder="00:00h"
                            />
                            <InputError class="mt-2" :message="form.errors.time" />
                        </div>

                        <div>
                            <div class="flex space-x-3 mb-1">
                                <InputLabel  class="w-1/3" for="ingredient" value="Selecciona un ingrediente: " />
                                <InputLabel v-if="selectedIngredient" class="w-1/3" for="quantity" value="Cantidad: " />
                                <InputLabel v-if="selectedIngredient && quantity" class="w-1/3" for="unit" value="Unidad de Medida: " />
                            </div>

                            <div class="flex space-x-0.5 mb-3">
                                <select  class="w-1/3 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                         id="ingredientename"
                                         v-model="selectedIngredient">

                                    <option disabled value="">Selecciona un ingrediente</option>
                                    <option v-for="(ingredient) in ingredients" :key="ingredient.id" :value="ingredient.id">
                                        {{ ingredient.name }} - {{ ingredient.category }}
                                    </option>
                                </select>
                               <TextInput v-if="selectedIngredient" v-model="quantity" type="number" required class="block w-1/3"></TextInput>
                                <select v-if="selectedIngredient && quantity"
                                        class="w-1/3 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                         v-model="selectedUnit"
                                         @change="addIngredient" required>

                                    <option disabled value="">Volúmen: </option>
                                    <option value="mililitros">Mililitros</option>
                                    <option value="litros">Litros</option>
                                    <option value="centilitros">Centilitros</option>
                                    <option value="tazas">Tazas</option>
                                    <option value="cda">Cucharadas</option>
                                    <option value="cdta">Cucharadita</option>

                                    <option disabled>Peso: </option>
                                    <option value="mg">Miligramos</option>
                                    <option value="g">Gramos</option>
                                    <option value="g">Kilogramos</option>
                                    <option value="lb">Libras</option>

                                    <option disabled>Cantidad: </option>
                                    <option value="u">Unidades</option>
                                    <option value="pza">Piezas</option>
                                    <option value="ración">Raciones</option>
                                    <option value="porción">Porciones</option>
                                </select>
                            </div>
                            <div class="mt-5"
                                 v-if="selectedIngredients.length">
                                <h3>Ingredientes seleccionados:</h3>
                                <ul>
                                    <li v-for="(ingredient, index) in selectedIngredients" :key="index">
                                        {{ ingredient.name }} - {{ingredient.quantity }} {{ingredient.unit}}
                                        <button @click.prevent="removeIngredient(index)" class="ml-2 text-red-600">&#10006;</button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div>
                            <InputLabel for="custom_ingredients" value="Ingredientes Personalizados" />
                            <textarea
                                id="custom_ingredients"
                                v-model="form.custom_ingredients"
                                class="mt-1 block w-full h-40 resize-none border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                required
                                autofocus
                                autocomplete="custom_ingredients"
                                placeholder="¡Pon aquí tus ingredientes personalizados!">
                            </textarea>

                            <InputError class="mt-2" :message="form.errors.instructions" />
                        </div>
                        <div>
                            <InputLabel for="instruccionts" value="Instrucciones" />
                            <textarea
                                id="instruccionts"
                                v-model="form.instructions"
                                class="mt-1 block w-full h-56 resize-none border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                required
                                autofocus
                                autocomplete="instruccionts"
                                placeholder="Instrucciones">
                            </textarea>

                            <InputError class="mt-2" :message="form.errors.instructions" />
                        </div>

                        <div>
                            <InputLabel for="avatar" value="Imagen" />
                            <FileInput name="avatar" @change="onSelectAvatar"/>
                            <InputError class="mt-2" :message="form.errors.avatar" />
                        </div>

                        <div>
                            <InputLabel for="difficulty" value="Dificultad" />
                            <select v-model="form.difficulty" name="difficulty" id="difficulty"
                                class="mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                                <option value="Fácil">Fácil</option>
                                <option value="Media">Media</option>
                                <option value="Difícil">Difícil</option>
                            </select>
                            <InputError class="mt-2" :message="form.errors.difficulty" />
                        </div>
                        <PrimaryButton class="justify-center">
                            Publicar
                        </PrimaryButton>

                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
