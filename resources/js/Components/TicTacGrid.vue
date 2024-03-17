<script lang="ts" setup>
    interface GameData {
        slug: string
    }

    interface UserData {
        name: string;
    }

    const props = defineProps<{
        game: GameData,
        user: UserData
    }>();
</script>

<template>
    <section class="border border-white flex flex-col gap-4 mb-8 p-4 rounded w-full">
        <p class="font-bold text-center text-xl text-white">
            Tic Tac Toe Game
        </p>
    </section>
</template>
