<script setup>

import {differenceInDays, format, parseISO} from 'date-fns';
import { Link } from '@inertiajs/vue3';
const props = defineProps({
    id: Number,
    user_id: Number,
    name: String,
    title: String,
    description: String,
    avatar: String,
    time: String,
    difficulty: String,
    created_at: String,
    likes_count: Number
});


const formatDate = (dateString) => {
    const date = parseISO(dateString);
    const today = new Date();
    const diffDays = differenceInDays(today, date);

    if (diffDays === 0) {
        return '¡Hoy!';
    } else if (diffDays === 1) {
        return '¡Ayer!';
    } else if (diffDays <= 7) {
        return `¡Hace ${diffDays} días!`;
    } else {
        return format(date, 'dd/MM/yyyy');
    }
};
</script>

<template>
  <div class="mt-10">
              <div class="bg-gradient-to-r from-yellow-100 via-orange-200 to-red-200 overflow-hidden shadow-xl sm:rounded-lg max-w-md mx-auto">
                  <div class="rounded overflow-hidden shadow-lg">
                      <img
                          class="w-full h-56 object-cover"
                          :src="props.avatar ? '/storage/' + props.avatar : '/storage/recipes/photo.jpg'"
                          alt="Imagen de la receta"
                      >
                      <div class="px-6 py-4">

                          <div class="flex justify-between">
                              <div class="font-bold text-xl mb-2">{{props.title}}</div>
                              <p class="truncate max-w-[120px]"
                                 style="max-width: 120px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"> {{ formatDate(props.created_at) }}</p>
                          </div>
                          <p class="font-bold text-gray-700 text-base">
                             {{props.description}}
                          </p>
                          <div class="flex justify-between">
                              <div>
                                  <p class="text-gray-700 text-base">
                                      Duracion :
                                      {{props.time}}h
                                  </p>
                              </div>
                              <div class=" flex text-red-400 text-base font-bold">
                                  <div class=" text-red-500 text-xl">
                                      {{props.likes_count}}
                                  </div>
                                  <div class="mt-1.5 ml-1.5">
                                      <svg class="text-red-500 w-4 h-auto fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                          <path d="M0 190.9V185.1C0 115.2 50.52 55.58 119.4 44.1C164.1 36.51 211.4 51.37 244 84.02L256 96L267.1 84.02C300.6 51.37 347 36.51 392.6 44.1C461.5 55.58 512 115.2 512 185.1V190.9C512 232.4 494.8 272.1 464.4 300.4L283.7 469.1C276.2 476.1 266.3 480 256 480C245.7 480 235.8 476.1 228.3 469.1L47.59 300.4C17.23 272.1 .0003 232.4 .0003 190.9L0 190.9z"/>
                                      </svg>
                                  </div>
                              </div>

                          </div>

                          <p :class="{
                            'text-green-500 font-bold': props.difficulty === 'Fácil',
                            'text-orange-500 font-bold': props.difficulty === 'Media',
                            'text-red-500 font-bold': props.difficulty === 'Difícil'
                             }"
                             class="text-base"
                          >
                              {{ props.difficulty }}
                          </p>
                          <div class="flex justify-between">
                              <Link class="mt-5 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150" :href="route('recipes.show', props.id)">
                                  Ver Receta
                              </Link>
                              <Link
                                  :href="route('users.show', props.user_id)"
                                   class="mt-7 underline text-black-600 truncate max-w-[120px]"
                                   style="max-width: 60px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"
                              >
                                  {{props.name}}
                              </Link>
                          </div>

                      </div>
                  </div>
              </div>
  </div>
</template>

