<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { usePage } from '@inertiajs/vue3';
import Conversation from "@/Pages/Messages/Conversation.vue";
import { computed, ref } from "vue";

const page = usePage();
const conversations = page.props.conversations || [];
const searchQuery = ref('');

const filteredConversations = computed(() => {
    if (!searchQuery.value) {
        return conversations;
    }
    return conversations.filter(conversation =>
        conversation.conversation_partner_name.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});
</script>

<template>
    <AppLayout title="Chats">
        <template #header>
            <div class="flex justify-between">
                <h1 class="text-xl">Chats</h1>
            </div>
        </template>

        <div v-if="conversations.length > 0">
            <div class="flex justify-center mt-4">
                <input
                    v-model="searchQuery"
                    type="text"
                    placeholder="Buscar chat por nombre..."
                    class="p-2 border rounded mb-4 w-1/2"
                />
            </div>

            <div class="max-w-full mx-auto sm:px-6 lg:px-8 justify-center">
                <div v-if="filteredConversations.length > 0">
                    <div class="flex flex-wrap -mx-4">
                        <div class="w-full px-4 mb-4" v-for="chat in filteredConversations" :key="chat.id">
                            <Conversation
                                :id="chat.id"
                                :id_usuario="chat.conversation_partner_id"
                                :name="chat.conversation_partner_name"
                                :photo="chat.conversation_partner_photo"
                            />
                        </div>
                    </div>
                </div>
                <div v-else class="w-full mt-5 px-4 mb-4">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg max-w-3xl mx-auto p-6 text-center">
                        <p class="text-xl">No se encontraron resultados</p>
                    </div>
                </div>
            </div>
        </div>
        <div v-else class="w-full mt-5 px-4 mb-4">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg max-w-3xl mx-auto p-6 text-center">
                <p class="text-xl">No hay ningún chat</p>
            </div>
        </div>


    </AppLayout>
</template>
