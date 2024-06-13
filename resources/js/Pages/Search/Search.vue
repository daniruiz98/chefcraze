<script setup>
import { ref, computed } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { usePage } from '@inertiajs/vue3';
import UserSearch from '@/Pages/Search/UserSearch.vue';

const page = usePage();
const users = page.props.users;
const searchQuery = ref('');


const filteredUsers = computed(() => {
    if (!searchQuery.value) {
        return users;
    }
    return users.filter(user =>
        user.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});
</script>

<template>
    <AppLayout title="Buscar Usuarios">
        <template #header>
            <div class="flex justify-between">
                <h1 class="text-xl">Buscar Usuarios</h1>
            </div>
        </template>
        <div class="flex justify-center mt-4">
            <input
                v-model="searchQuery"
                type="text"
                placeholder="Buscar usuario por nombre..."
                class="p-2 border rounded mb-4 w-1/2"
            />
        </div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-wrap justify-center -mx-4">
                <div v-if="filteredUsers.length" class="w-full flex flex-wrap justify-center">
                    <UserSearch
                        v-for="user in filteredUsers"
                        :key="user.id"
                        :user_id="user.id"
                        :user_name="user.name"
                        :user_photo="user.profile_photo_path"
                    />
                </div>
                <div v-else class="w-full mt-5 px-4 mb-4">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg max-w-3xl mx-auto p-6 text-center">
                        <p class="text-xl">No se encontraron usuarios</p>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
