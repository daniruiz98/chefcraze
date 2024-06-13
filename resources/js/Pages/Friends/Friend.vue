<script setup>

import {Link} from '@inertiajs/vue3';

const props = defineProps({
    user_id: Number,
    name: String,
    profile_photo: String
});


</script>

<template>

        <div class="mt-5 w-full justify-center">
            <div class=" transition-transform transform-gpu hover:scale-105 bg-gradient-to-r from-red-200 via-orange-200 to-yellow-100 overflow-hidden shadow-xl sm:rounded-lg max-w-3xl mx-auto">
                <div class=" rounded overflow-hidden shadow-lg p-6 justify-center">
                    <div class=" flex items-center bg-gray-100 p-4 mb-4 rounded-lg">
                        <img  :src="profile_photo ? '/storage/' + profile_photo : '/storage/profile-photos/img.jpg'" alt="Profile Picture" class="w-16 h-16 rounded-full object-cover mr-4">
                        <div class="flex-1">
                          <Link class="underline" :href="route('users.show', user_id)"><strong>{{name}}</strong></Link>
                        </div>
                        <div>
                            <Link :href="route('conversation.find', user_id)" class="text-blue-500 hover:text-blue-700">
                              <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" width="100" height="40" viewBox="0 0 231.84 218.542" style="overflow:visible;enable-background:new 0 0 231.84 218.542;" xml:space="preserve"><rect x="45.006" y="77.415" style="fill:#FFFFFF;" width="149" height="75"/><g><g><path style="fill:#3399CC;" d="M120.581,0C59.132,0,9.319,48.921,9.319,109.271c0,7.032,0.676,13.908,1.969,20.57    c8.073,34.111-0.533,57.668-9.801,71.887c0,0-8.892,16.813,16.814,16.813h102.28c61.448,0,111.262-48.924,111.262-109.271    C231.84,48.921,182.029,0,120.581,0z M70.247,129.791c-9.919,0-17.959-8.041-17.959-17.961c0-9.918,8.041-17.958,17.959-17.958    s17.959,8.04,17.959,17.958C88.206,121.75,80.166,129.791,70.247,129.791z M120.534,129.791c-9.917,0-17.958-8.041-17.958-17.961    c0-9.918,8.041-17.958,17.958-17.958c9.918,0,17.961,8.04,17.961,17.958C138.495,121.75,130.452,129.791,120.534,129.791z     M172.018,129.791c-9.919,0-17.96-8.041-17.96-17.961c0-9.918,8.041-17.958,17.96-17.958c9.918,0,17.959,8.04,17.959,17.958    C189.977,121.75,181.936,129.791,172.018,129.791z"/></g></g></svg>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</template>
<style scoped>
.container {
    background-image: linear-gradient(to right, #ffcccc, #ffe6cc, #ffffcc);
}
</style>
