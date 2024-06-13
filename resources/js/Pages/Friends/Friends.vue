<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { usePage } from '@inertiajs/vue3';
import Friend from "@/Pages/Friends/Friend.vue";
import { computed, ref } from "vue";

const page = usePage();
const friends = page.props.friends || [];
console.log(friends);
const searchQuery = ref('');

const filteredUsers = computed(() => {
    if (!searchQuery.value) {
        return friends;
    }
    return friends.filter(friend =>
        friend.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});
</script>

<template>
    <AppLayout title="Tus amigos">
        <template #header>
            <div class="flex justify-between">
                <h1 class="text-xl">Tus amigos</h1>
            </div>
        </template>

        <div v-if="friends.length > 0">
            <div class="flex justify-center mt-4">
                <input
                    v-model="searchQuery"
                    type="text"
                    placeholder="Buscar usuario por nombre..."
                    class="p-2 border rounded mb-4 w-1/2"
                />
            </div>
            <div v-if="filteredUsers.length > 0">
                <div class="max-w-full mx-auto sm:px-6 lg:px-8 justify-center">
                    <div class="flex flex-wrap -mx-4">
                        <div v-for="friend in filteredUsers" :key="friend.id" class="w-full px-4 mb-4">
                            <Friend
                                :user_id="friend.id"
                                :name="friend.name"
                                :profile_photo="friend.profile_photo_path"
                            />
                        </div>
                    </div>
                </div>
            </div>
            <div v-else class="w-full mt-5 px-4 mb-4">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg max-w-3xl mx-auto p-6 text-center">
                    <p class="text-xl">No encontraron resultados</p>
                </div>
            </div>
        </div>
        <div v-else class="w-full mt-5 px-4 mb-4">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg max-w-3xl mx-auto p-6 text-center">
                <p class="text-xl">Todavía no tienes amigos</p>
            </div>
        </div>
    </AppLayout>
</template>
