<script setup>
import {Link} from '@inertiajs/vue3';
import ChatWindow from "@/Pages/Messages/ChatWindow.vue";
import {ref} from "vue";

const props = defineProps({
  id: Number,
  id_usuario: Number,
  name: String,
  photo: String,
});

const showChatWindow = ref(false);

const openChat = () => {
  showChatWindow.value = true;
};

const close = () => {
  showChatWindow.value = false;
};

</script>

<template>
  <div class="mt-5 w-full justify-center">
    <div class="  transition-transform transform-gpu hover:scale-105 bg-gradient-to-r from-red-200 via-orange-200 to-yellow-100 overflow-hidden shadow-xl sm:rounded-lg max-w-3xl mx-auto">
      <div class="rounded overflow-hidden shadow-lg p-6 justify-center">
        <div class="flex items-center bg-gray-100 p-4 mb-4 rounded-lg">
          <div class="w-16 h-16 rounded-full overflow-hidden mr-4">
            <img :src=" photo ? `/storage/${photo}` : '/storage/profile-photos/img.jpg'" alt="Profile Picture" class="w-full h-full object-cover">
          </div>
          <div class="flex-1">
              <Link class="underline" :href="route('users.show', id_usuario)"><strong>{{name}}</strong></Link>
          </div>
          <div>
            <a @click.prevent="openChat" class="text-blue-500 hover:text-blue-700">
             Abrir chat
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <ChatWindow v-if="showChatWindow" :conversationPartnerId="id_usuario" :conversationId="id" :conversationPartnerName="name" :conversationPartnerPhoto="photo" @close="close" />
</template>

