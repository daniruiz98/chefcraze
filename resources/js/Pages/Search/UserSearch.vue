<script setup>
import { defineProps } from 'vue';
import {Link} from '@inertiajs/vue3';

const props = defineProps({
    user_id: Number,
    user_name: String,
    user_photo: String
});
</script>
<template>
    <div class="mt-5 w-full justify-center flex">
        <div class="w-2/3 transition-transform transform-gpu hover:scale-105 bg-gradient-to-r from-red-200 via-orange-200 to-yellow-100 overflow-hidden shadow-xl sm:rounded-lg mx-auto">
            <div class="rounded overflow-hidden shadow-lg p-6 justify-center">
                <div class="bg-white rounded-2xl text-gray-900 p-2 flex items-center">
                    <img
                        :src="user_photo ? '/storage/' + user_photo : '/storage/profile-photos/img.jpg'"
                        alt="Profile Picture"
                        class="w-16 h-16 rounded-full object-cover mr-4"
                    />
                    <div>
                        <Link :href="route('users.show', user_id)" class="underline font-bold">{{ user_name }}</Link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
