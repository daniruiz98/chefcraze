<script setup xmlns="http://www.w3.org/1999/html">
import AppLayout from "@/Layouts/AppLayout.vue";
import {Link, router, usePage} from "@inertiajs/vue3";
import Recipes from "@/Components/Recipes.vue";
import BackButton from "@/Components/BackButton.vue";
import {ref} from "vue";

const user_recipes = usePage().props.user_recipes;
const isFollowing = usePage().props.isFollowing;
const followers = ref([]);
const following = ref([]);
const showFollowersModal = ref(false);
const showFollowingModal = ref(false);
const loadFollowers = async () => {
    showFollowersModal.value = true;
    try {
        const response = await fetch(route('users.followers', usePage().props.user.id), {
            method: 'GET',
            headers: {
                'Accept' : 'application/json'
            },
        });
        if(response.ok){
            followers.value = await response.json();
        }else{
            console.error('Error loading followers', response.statusText)
        }
    } catch (error) {
        console.error('Error loading followers', error);
    }
};

const loadFollowing = async () => {
    showFollowingModal.value = true;
    try {
        const response = await fetch(route('users.following', usePage().props.user.id), {
            method: 'GET',
            headers: {
                'Accept' : 'application/json'
            },
        });
        if(response.ok){
            following.value = await response.json();
        }else{
            console.error('Error loading followers', response.statusText)
        }
    } catch (error) {
        console.error('Error loading followers', error);
    }
};
</script>

<template>
    <AppLayout title="User">
        <template #header>
            <div class="flex justify-between">
                <BackButton />
            </div>
        </template>

        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-gradient-to-r from-red-200 via-orange-200 to-yellow-100 overflow-hidden shadow-xl sm:rounded-lg">
                    <span class="block font-bold text-gray-800 text-3xl text-center mt-8">
                            {{$page.props.user.name}}
                        </span>
                    <div class="mt-5 flex flex-col md:flex-row justify-around items-center space-y-5">
                        <!-- Nombre y foto de perfil -->
                        <div class="md:w-1/3 md:mr-6 flex justify-center">
                            <div class="flex flex-col items-center">
                                <img class="w-52 h-64 rounded-full object-cover mb-8"
                                     :src="$page.props.user.profile_photo_path ? '/storage/' + $page.props.user.profile_photo_path : '/storage/profile-photos/img.jpg'"
                                     alt="Profile Picture">
                                <span class="block font-bold text-gray-800 text-xl text-center">
                    </span>
                            </div>
                        </div>
                        <!-- Estadísticas -->
                        <div class="md:w-2/3 flex flex-col items-center">
                            <div class="flex justify-around w-full">
                                <div class="text-center">
                                    <span class="block font-bold text-xl text-gray-800">{{$page.props.user_total_recipes}}</span>
                                    <span class="text-lg   mr-2 text-gray-500">Publicaciones</span>
                                </div>
                                <div class="text-center">
                        <span class="block font-bold text-xl text-gray-800 cursor-pointer" @click="loadFollowers">
                            {{$page.props.user_total_followers}}
                        </span>
                                    <span class="text-lg mr-2 text-gray-500">Seguidores</span>
                                </div>
                                <div class="text-center">
                        <span class="block font-bold text-xl text-gray-800 cursor-pointer" @click="loadFollowing">
                            {{$page.props.user_total_following}}
                        </span>
                                    <span class="text-lg text-gray-500">Siguiendo</span>
                                </div>
                            </div>
                            <!-- Botón de Seguir / Editar perfil -->
                            <div class="mt-6 w-full flex justify-center">
                                <div v-if="$page.props.follower_id !== $page.props.user.id" class=" mt-5 w-52 flex justify-center">
                                    <Link :href="route('toggleFollow', {id: $page.props.user.id, isFollowing: isFollowing})"
                                          class="w-full inline-flex items-center justify-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-400 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-2 transition ease-in-out duration-150">
                                        {{ $page.props.isFollowing ? 'Dejar de Seguir' : 'Seguir' }}
                                    </Link>
                                </div>
                                <div v-else class="mt-5 w-52 flex justify-center">
                                    <Link :href="route('profile.show')"
                                          class=" w-full inline-flex items-center justify-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-400 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-2 transition ease-in-out duration-150">
                                        Editar mi perfil
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Descripción -->
                    <div class=" mt-8 mb-8">
                        <p v-if="$page.props.user.description" class="text-gray-800 underline text-center italic text-2xl mb-5">
                            "{{$page.props.user.description}}"
                        </p>
                    </div>
                </div>
            </div>

            <div v-if="$page.props.user_total_recipes !== 0" class="w-full mt-5 bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <span class="block font-bold text-gray-800 text-xl text-center mt-8 ml-5">¡Aquí tienes las recetas de {{$page.props.user.name}}!</span>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4 gap-3 px-4 mb-4">
                    <div class="transition-transform transform-gpu hover:scale-105 w-full" v-for="(recipe, index) in user_recipes" :key="recipe.id">
                        <Recipes
                            :id="recipe.id"
                            :user_id="recipe.user_id"
                            :title="recipe.title"
                            :description="recipe.description"
                            :avatar="recipe.avatar"
                            :time="recipe.time"
                            :difficulty="recipe.difficulty"
                            :created_at="recipe.created_at"
                            :likes_count="recipe.likes_count"
                        />
                    </div>
                </div>
            </div>
            <div v-else>
                <span class="block mb-10 font-bold text-gray-800 text-xl text-center mt-8 ml-5">¡{{$page.props.user.name}} todavía no tiene recetas!</span>
            </div>
        </div>

        <!-- Modal de seguidores -->
        <div v-if="showFollowersModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl">
                <div class="p-4 flex items-center justify-between border-b">
                    <h2 class="font-semibold text-lg">Seguidores</h2>
                    <button @click="showFollowersModal = false" class="text-gray-500 hover:text-gray-700 focus:outline-none">X</button>
                </div>
                <div class=" bg-gradient-to-r from-red-200 via-orange-100 to-yellow-100 p-4 h-96 overflow-y-auto">
                    <div v-for="follower in followers" :key="follower.id" class=" flex items-center mb-4">
                        <div class="w-12 h-12 rounded-full overflow-hidden mr-4">
                            <img :src="follower.profile_photo_path ? `/storage/${follower.profile_photo_path}` : '/storage/profile-photos/img.jpg'" alt="Profile Picture" class="w-full h-full object-cover">
                        </div>
                        <h3 class="font-semibold">
                            <Link class="underline" :href="route('users.show', follower.id)">{{ follower.name }}</Link>
                        </h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de siguiendo -->
        <div v-if="showFollowingModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl">
                <div class="p-4 flex items-center justify-between border-b">
                    <h2 class="font-semibold text-lg">Siguiendo</h2>
                    <button @click="showFollowingModal = false" class="text-gray-500 hover:text-gray-700 focus:outline-none">X</button>
                </div>
                <div class=" bg-gradient-to-r from-red-200 via-orange-100 to-yellow-100 p-4 h-96 overflow-y-auto">
                    <div v-for="followed in following" :key="followed.id" class="flex items-center mb-4">
                        <div class="w-12 h-12 rounded-full overflow-hidden mr-4">
                            <img :src="`/storage/${followed.profile_photo_path}`" alt="Profile Picture" class="w-full h-full object-cover">
                        </div>
                        <h3 class="font-semibold">
                            <Link class="underline" :href="route('users.show', followed.id)">{{ followed.name }}</Link>
                        </h3>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.container {
    background-image: linear-gradient(to right, #ffcccc, #ffe6cc, #ffffcc);
}
</style>
