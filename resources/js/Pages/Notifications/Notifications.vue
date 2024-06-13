<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { usePage } from '@inertiajs/vue3';
import Notification from "@/Pages/Notifications/Notification.vue";


const page = usePage();
const notifications = page.props.notifications;
console.log(notifications);

</script>
<template>
    <AppLayout title="Inicio">

        <template #header>
            <div class="flex justify-between">
                <h1 class="text-xl">Tus notificaciones</h1>
            </div>
        </template>
        <div v-if="notifications.length > 0">
            <div class="max-w-full mx-auto sm:px-6 lg:px-8 justify-center">
                <div class="flex flex-wrap -mx-4">
                    <div class="w-full px-4 mb-4" v-for="notification in notifications" :key="notification.id">
                        <Notification
                            :id="notification.id"
                            :user_id="notification.user_id"
                            :user_name="notification.user_name"
                            :notifica_id="notification.notifica_id"
                            :notifica_name="notification.notifica_name"
                            :type="notification.type"
                            :recipe_id="notification.recipe_id"
                            :title="notification.title"
                            :created_at="notification.created_at"
                        />
                    </div>
                </div>
            </div>
        </div>
        <div v-else class="w-full mt-5 px-4 mb-4">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg max-w-3xl mx-auto p-6 text-center">
                <p class="text-xl">No tienes notificaciones</p>
            </div>
        </div>

    </AppLayout>
</template>
