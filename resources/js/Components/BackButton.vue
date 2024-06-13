<script setup>
import {Link} from "@inertiajs/vue3";

const goBack = () => {
    window.history.back();
};
</script>

<template>
    <Link  @click.prevent="goBack" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-400 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-2 transition ease-in-out duration-150" :href="route('dashboard')">
        Volver
    </Link>
</template>
