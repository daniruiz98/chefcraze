<script setup>

import AppLayout from "@/Layouts/AppLayout.vue";
import {Link, usePage} from "@inertiajs/vue3";
import BackButton from "@/Components/BackButton.vue";
import {computed, ref} from "vue";

const recipe_id = usePage().props.recipe.id;
const ingredients = usePage().props.ingredients;
const instructions = usePage().props.recipe.instructions.split('\n');
const custom_ingredients = usePage().props.recipe.custom_ingredients ? usePage().props.recipe.custom_ingredients.split('\n') : undefined;
const isLike = ref(usePage().props.isLike);
const total_likes = ref(usePage().props.likes_count.total_likes ? usePage().props.likes_count.total_likes : 0);
const comments = ref(usePage().props.total_comments);
const name_author = usePage().props.name_author.name

async function like(){

    if(isLike.value === false){
        isLike.value = true;
        total_likes.value += 1;
    }else if(isLike.value === true){
        isLike.value = false;
        total_likes.value -= 1;
    }
    let contenido = {
        recipe_id: recipe_id,
        like: isLike.value,
        user_id: usePage().props.user_id,
        user_recipe: usePage().props.recipe.user_id
    }
    await fetch(route('like'),
        {
            method: "POST",
            headers: {
                'Accept' : 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(contenido)
        });
}



// Nuevo comentario
const newComment = ref("");

// Función para agregar un nuevo comentario
async function addComment() {

    if (newComment.value.trim() !== "") {

        const contenido = {
            user_id: usePage().props.user_id,
            recipe_id: recipe_id,
            comment: newComment.value,
            user_recipe: usePage().props.recipe.user_id
        }
        try{
            let respuesta = await fetch(route('add.comment'),
                {
                    method: "POST",
                    headers: {
                        'Accept' : 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(contenido)
                });
            if(respuesta.ok){
                let datos = await respuesta.json();

                const comment = {
                    comment: datos.comment.comment,
                    name: datos.comment.name,
                    id: datos.comment.id,
                    created_at: datos.comment.created_at
                };
                comments.value.push(comment);
                newComment.value = "";
            }else{
                console.log("algo has hecho mal");
            }
        }catch (error){
            console.log("Error al comentar")
        }
    }
}


</script>
<template>
    <AppLayout title="Inicio">
        <template #header>
            <div class="flex justify-between">
                <BackButton />
            </div>
        </template>
        <div class="py-7">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-gradient-to-r from-red-300 via-orange-100 to-yellow-200 overflow-hidden shadow-xl sm:rounded-2xl sm:m-3.5">
                    <img class="mt-7 max-w-full  mx-auto h-96 mb-1 object-cover rounded-2xl" :src="$page.props.recipe.avatar ? '/storage/' + $page.props.recipe.avatar : '/storage/recipes/photo.jpg'" alt="Imagen de la receta">
                    <div class="p-6 space-y-6 sm:space-y-8">
                        <div class="sm:flex sm:justify-between">
                            <div class="w-full sm:w-3/4">
                                <div class="font-bold text-3xl mb-2">{{$page.props.recipe.title}}</div>
                                <p :class="{
                                    'text-green-500 font-bold text-2xl': $page.props.recipe.difficulty === 'Fácil',
                                    'text-orange-500 font-bold border-black inline-block px2 text-2xl': $page.props.recipe.difficulty === 'Media',
                                    'text-red-500 font-bold text-2xl': $page.props.recipe.difficulty === 'Difícil'
                                } ">
                                    {{ $page.props.recipe.difficulty }}
                                </p>
                                <p class="mt-5 text-gray-700 text-base text-xl font-bold">'{{$page.props.recipe.description}}'</p>
                                <p class="text-gray-700 text-base italic">{{$page.props.recipe.time}} h</p>
                                <Link class="underline font-bold text-base" :href="route('users.show', usePage().props.recipe.user_id)">{{name_author}}</Link>
                            </div>
                            <div class="flex items-center justify-end w-full sm:w-1/4">
                                <button @click.prevent="like" class="heart-btn text-red-400" v-if="isLike === false">
                                    <svg class="text-red-500 w-8 h-auto fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <path d="M244 84L255.1 96L267.1 84.02C300.6 51.37 347 36.51 392.6 44.1C461.5 55.58 512 115.2 512 185.1V190.9C512 232.4 494.8 272.1 464.4 300.4L283.7 469.1C276.2 476.1 266.3 480 256 480C245.7 480 235.8 476.1 228.3 469.1L47.59 300.4C17.23 272.1 0 232.4 0 190.9V185.1C0 115.2 50.52 55.58 119.4 44.1C164.1 36.51 211.4 51.37 244 84C243.1 84 244 84.01 244 84L244 84zM255.1 163.9L210.1 117.1C188.4 96.28 157.6 86.4 127.3 91.44C81.55 99.07 48 138.7 48 185.1V190.9C48 219.1 59.71 246.1 80.34 265.3L256 429.3L431.7 265.3C452.3 246.1 464 219.1 464 190.9V185.1C464 138.7 430.4 99.07 384.7 91.44C354.4 86.4 323.6 96.28 301.9 117.1L255.1 163.9z"/>
                                    </svg>
                                </button>
                                <button @click.prevent="like" class="heart-btn" v-else>
                                    <svg class="text-red-500 w-8 h-auto fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <path d="M0 190.9V185.1C0 115.2 50.52 55.58 119.4 44.1C164.1 36.51 211.4 51.37 244 84.02L256 96L267.1 84.02C300.6 51.37 347 36.51 392.6 44.1C461.5 55.58 512 115.2 512 185.1V190.9C512 232.4 494.8 272.1 464.4 300.4L283.7 469.1C276.2 476.1 266.3 480 256 480C245.7 480 235.8 476.1 228.3 469.1L47.59 300.4C17.23 272.1 .0003 232.4 .0003 190.9L0 190.9z"/>
                                    </svg>
                                </button>
                                <p class="text-lg font-bold ml-3 text-red-500">{{total_likes}}</p>
                            </div>
                        </div>
                        <div class="mt-5">
                            <p>Ingredientes: </p>
                            <div class="font-bold bg-white border mb-3 mt-1 p-4 rounded-2xl">
                                <p v-for="ingredient in ingredients" class="text-gray-700 text-base">
                                    {{ingredient.name}} - {{ingredient.quantity}}
                                </p>
                            </div>
                        </div>
                        <div class="mt-5">
                            <p>Ingredientes Personalizados: </p>
                            <div class="font-bold bg-white border mb-3 mt-1 p-4 rounded-2xl">
                                <p v-for="ingredient in custom_ingredients" class="text-gray-700 text-base">
                                    {{ingredient}}
                                </p>
                            </div>
                        </div>
                        <div class="mt-5">
                            <p>Instrucciones: </p>
                            <div class="font-bold bg-white border mb-3 mt-1 p-4 rounded-2xl">
                                <p v-for="instruction in instructions" class="text-gray-700 text-base">
                                    {{instruction}}
                                </p>
                            </div>
                        </div>
                        <h2 class="text-2xl font-semibold mb-4 mt-5">Comentarios</h2>
                        <div v-if="comments.length > 0" class="space-y-4">
                            <div v-for="comment in comments" class="bg-gray-300 rounded-lg p-4">
                                <p class="text-gray-800 font-bold">{{ comment.comment }}</p>
                                <Link :href="route('users.show', comment.id)" class="mt-3 underline text-black-600 text-sm truncate max-w-[120px]" style="max-width: 60px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    {{comment.name}}
                                </Link>
                                <p class="text-sm text-gray-600">{{ comment.created_at }}</p>
                            </div>
                        </div>
                        <p v-else class="text-gray-600">No hay comentarios aún.</p>
                        <div class="mt-4 mb-10">
                            <textarea v-model="newComment" class="w-full border-gray-400 rounded-lg p-4 focus:outline-none focus:border-blue-500" rows="4" placeholder="Escribe tu comentario aquí..."></textarea>
                            <div class="flex justify-between">
                                <button @click="addComment" class="mt-2  mr-1.5 px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 focus:outline-none focus:bg-gray-600">Agregar comentario</button>
                                <Link
                                    v-if="usePage().props.user_id === usePage().props.recipe.user_id || usePage().props.user_id === 1"
                                    class="mt-5 inline-flex items-center px-6 py-4 bg-red-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                    :href="route('delete.recipe', recipe_id)">
                                    Borrar Receta
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

