<template>
        <header class="bg-white shadow">
      <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <h1 v-if="title" class="text-3xl font-bold text-gray-900">
            {{  title }}
            <slot name="header"></slot>
        </h1>
      </div>
    </header>
    <main>
      <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
        <!-- Your content -->
        <div class="px-4 py-6 sm:px:0">
          <slot></slot>
        </div>
      </div>
    </main>
</template>

<script setup>
    const props = defineProps({
        title: String,
    });
</script>

<style lang="scss" scoped>

</style>