<template>
    <div class="bg-gradient-to-r from-red-200 via-orange-200 to-yellow-100 min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div>
            <slot name="logo" />
        </div>

        <div class=" bg-gradient-to-r from-yellow-100 via-orange-100 to-red-200 w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <slot />
        </div>
    </div>
</template>
