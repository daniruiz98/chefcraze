<script setup>

import {Link} from '@inertiajs/vue3';
import {ref} from "vue";
import moment from 'moment';

const props = defineProps({
    id: Number,
    user_id: Number,
    user_name: String,
    notifica_id: Number,
    notifica_name: String,
    type: String,
    recipe_id: Number,
    title: String,
    created_at: String,

});

const timeSince = (date) => {
    const now = moment();
    const createdAt = moment(date);
    const seconds = now.diff(createdAt, 'seconds');
    const minutes = now.diff(createdAt, 'minutes');
    const hours = now.diff(createdAt, 'hours');
    const days = now.diff(createdAt, 'days');

    if (days > 0) {
        return `${days} ${days === 1 ? 'día' : 'días'}`;
    } else if (hours > 0) {
        return `${hours} ${hours === 1 ? 'hora' : 'horas'}`;
    } else if (minutes > 0) {
        return `${minutes} ${minutes === 1 ? 'minuto' : 'minutos'}`;
    } else {
        return `${seconds} ${seconds === 1 ? 'segundo' : 'segundos'}`;
    }
};

const formatDateWithTime = (date) => {
    return moment(date).format('DD MMM YYYY - HH:mm:ss');
};

</script>

<template>
    <div class="mt-5 w-full justify-center">
        <div class="  transition-transform transform-gpu hover:scale-105 bg-gradient-to-r from-red-200 via-orange-200 to-yellow-100 overflow-hidden shadow-xl sm:rounded-lg max-w-3xl mx-auto">
            <div class="rounded overflow-hidden shadow-lg p-6 justify-center">
                <!-- Notification Content -->
                <div  class="bg-white rounded-2xl border-l-4 border-pink-300s text-gray-900 p-2" role="alert">
                    <p class="font-bold">Notificación</p>
                    <p v-if="type === 'like'">{{ notifica_name }} te ha dado <strong>LIKE</strong> en tu receta de <Link class="underline text-yellow-700" :href="route('recipes.show',recipe_id)"> {{title}}</Link></p>
                    <p v-else-if="type=== 'comment'"> {{ notifica_name }} te ha <strong>COMENTADO </strong> en tu receta de <Link class="underline text-yellow-700" :href="route('recipes.show',recipe_id)"> {{title}}</Link>
                       </p>
                    <p v-else-if="type === 'follower'">{{ notifica_name }} te ha <strong>SEGUIDO</strong></p>
                    <p v-else-if="type === 'message'"> {{ notifica_name }} te ha enviado un <strong>MENSAJE</strong> hace  {{ timeSince(created_at)}}</p>
                </div>
                <!-- Optional: Additional Notification Details -->
                <div class="mt-4 text-sm text-gray-600">
                    <Link class="underline" :href="route('users.show', notifica_id)"><strong>{{ notifica_name }}</strong></Link>
                    <p>{{ formatDateWithTime(created_at) }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

