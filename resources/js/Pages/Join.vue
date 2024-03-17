<script setup lang="ts">
    import { onMounted, ref } from "vue";
    import { router } from "@inertiajs/vue3";

    import IconLoader from "@/Components/IconLoader.vue";
    // @ts-ignore
    import TicTacGrid from "@/Components/TicTacGrid.vue";

    interface FromData {
        invite_name: string;
        user_name: string;
        slug: string;
    }

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

    let onlineUsers = ref<Array<string>>([]);

    onMounted(() => {
        // @ts-ignore
        Echo.join("games.room")
        .here(data => {
            onlineUsers.value = data.map(d => d.name);
        })
        .joining(data => {
            onlineUsers.value.push(data.name);
        })
        .leaving(data => {
            const position = onlineUsers.value.indexOf(data.name);

            if (position > -1) onlineUsers.value.splice(position, 1);
        });
    });
</script>

<template>
    <main class="bg-black min-h-screen">
        <div class="container py-8 lg:flex lg:items-baseline lg:gap-4">
            <section class="border border-white flex flex-col gap-4 mb-8 p-4 rounded w-full">
                <TicTacGrid
                    :game="game"
                    :user="user"
                />
            </section>
        </div>
    </main>
</template>