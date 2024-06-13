<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {Link, usePage } from '@inertiajs/vue3';
import Recipes from "@/Components/Recipes.vue";

const page = usePage();
const recipes = page.props.recipes;

</script>
<template>
    <AppLayout title="Inicio">

        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Últimas recetas
                </h2>
                <Link class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150" :href="route('recipes.create')">
                    Crear Receta
                </Link>
            </div>
        </template>
            <div>
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="flex flex-wrap -mx-4">
                        <div class=" transition-transform transform-gpu hover:scale-105 w-full md:w-1/3 px-4 mb-4" v-for="(recipe, index) in recipes" :key="recipe.id">
                            <Recipes
                                :id="recipe.id"
                                :user_id="recipe.user_id"
                                :name="recipe.name"
                                :title="recipe.title"
                                :description="recipe.description"
                                :avatar="recipe.avatar"
                                :time="recipe.time"
                                :difficulty="recipe.difficulty"
                                :created_at="recipe.created_at"
                                :likes_count = "recipe.likes_count ? recipe.likes_count : 0"

                            />
                        </div>
                    </div>
                </div>
            </div>

    </AppLayout>
</template>
