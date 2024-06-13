<script setup>
import { ref, onMounted, defineProps, onUnmounted } from 'vue';
import { usePage, router } from '@inertiajs/vue3';

const props = defineProps({
  conversationId: Number,
  conversationPartnerName: String,
  conversationPartnerId: Number,
  conversationPartnerPhoto: String,
  id_usuario: Number,
});


const emit = defineEmits(['close'])
const messages = ref([]);
const newMessage = ref('');
const page = usePage();
const authUserId = page.props.auth.user.id;
console.log(props)
const loadMessages = async () => {
  try {
    let response = await fetch(route('messages.index', { conversation: props.conversationId }), {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
      },
    });

    if (response.ok) {
      let data = await response.json();
      messages.value = data.messages;
    } else {
      console.error("Error loading messages", response.statusText);
    }
  } catch (error) {
    console.error("Error loading messages", error);
  }
};

const sendMessage = async () => {
  if (newMessage.value.trim() === '') return;

  let contenido = {
    conversationPartnerId: props.conversationPartnerId,
    conversationPartnerName: props.conversationPartnerName,
    content: newMessage.value,
    user_id: authUserId
  }
  console.log(contenido)
  console.log(newMessage.value);
  try {
    let response = await fetch(route('messages.store', { conversation: props.conversationId }), {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: JSON.stringify(contenido),
    });

    if (response.ok) {
      let data = await response.json();
      console.log(data.message);
      messages.value.push(data.message);
      newMessage.value = '';
    } else {
      console.error("Error sending message", response.statusText);
    }
  } catch (error) {
    console.error("Error sending message", error);
  }
};

onMounted(async () => {
  await loadMessages();
});

const close = () => {
    emit('close')
};

onUnmounted(() => {
  // Lógica de limpieza o destrucción
});
</script>

<template>
  <div class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl">
      <!-- Encabezado -->
      <div class="p-4 flex items-center justify-between border-b">
        <div class="flex items-center">
          <div class="w-12 h-12 rounded-full overflow-hidden mr-4">
            <img :src=" props.conversationPartnerPhoto ? `/storage/${props.conversationPartnerPhoto}` : '/storage/profile-photos/img.jpg'" alt="Profile Picture" class="w-full h-full object-cover">
          </div>
          <h2 class="font-semibold text-lg">{{ props.conversationPartnerName }}</h2>
        </div>
        <button @click="close" class="text-gray-500 hover:text-gray-700 focus:outline-none">
          X
        </button>
      </div>
      <!-- Lista de mensajes -->
      <div class=" container p-4 h-96 overflow-y-auto">
        <div v-for="message in messages" :key="message.id">
          <!-- Mensaje enviado por el usuario actual -->
          <div v-if="message.sender_id === authUserId" class="flex items-start justify-end mb-4">
            <p class="bg-blue-100 text-sm p-2 rounded mr-2 inline-block">{{ message.content }}</p>
            <div class="w-8 h-8 rounded-full overflow-hidden">
              <img :src="page.props.auth.user.profile_photo_path ? `/storage/${page.props.auth.user.profile_photo_path}` : '/storage/profile-photos/img.jpg'" alt="Profile Picture" class="w-full h-full object-cover">
            </div>
          </div>
          <!-- Mensaje recibido -->
          <div v-else class="flex items-start mb-4">
            <div class="w-8 h-8 rounded-full overflow-hidden mr-2">
              <img :src=" props.conversationPartnerPhoto ? `/storage/${props.conversationPartnerPhoto}` : '/storage/profile-photos/img.jpg'" alt="Profile Picture" class="w-full h-full object-cover">
            </div>
            <p class="bg-gray-100 text-sm p-2 rounded ml-2 inline-block">{{ message.content }}</p>
          </div>
        </div>
      </div>
      <!-- Cuadro de entrada de mensaje -->
      <div class="p-4 border-t">
        <input v-model="newMessage" type="text" placeholder="Escribe un mensaje..." class="w-full p-2 border rounded-lg" />
        <button @click="sendMessage" class="bg-yellow-600 text-white px-4 py-2 rounded-lg mt-2">Enviar</button>
      </div>
    </div>
  </div>
</template>
<style scoped>
.container {
    background-image: linear-gradient(to right, #ffcccc, #ffe6cc, #ffffcc);
}
</style>
