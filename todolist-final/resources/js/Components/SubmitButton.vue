<script setup lang="ts">
defineOptions({ inheritAttrs: false });
</script>

<template>
    <button
        type="submit"
        v-bind="$attrs"
        class="px-4 py-2 bg-green-500 hover:bg-green-700 rounded-md mr-2 text-white"
    >
        <slot>Submit</slot>
    </button>
</template>
