<script setup lang="ts">
    import { onMounted, ref } from "vue";
    import { router } from "@inertiajs/vue3";

    import IconLoader from "@/Components/IconLoader.vue";

    interface FromData {
        name: string;
    }

    interface GameData {
        invited_from: string;
        game_slug: string;
    }

    interface UserData {
        name: string;
    }

    const props = defineProps<{
        user: UserData
    }>();

    let formData = ref<FromData>({ name: "" });
    let gameInvites = ref<GameData>({ invited_from: "", game_slug: "" });
    let isLoading = ref<Boolean>(false);
    let onlineUsers = ref<Array<string>>([]);

    onMounted(() => {
        isLoading.value = true;

        formData.value.name = props.user.name;

        // @ts-ignore
        Echo.join("games.room")
        .here(data => {
            isLoading.value = false;

            onlineUsers.value = data.map(d => d.name);
        })
        .joining(data => {
            onlineUsers.value.push(data.name);
        })
        .leaving(data => {
            const position = onlineUsers.value.indexOf(data.name);

            if (position > -1) onlineUsers.value.splice(position, 1);
        });

        // @ts-ignore
        Echo.private(`games.invite.${props.user.name}`)
        .listen("BroadcastGamerInvite", data => {
            gameInvites.value = data;
        });
    });

    const acceptInvite = slug => {
        router.get(`/game/${slug}/player-two`, { name: props.user.name });
    }

    const postForm = () => {
        router.post("/game/create", formData.value);
    }
</script>

<template>
    <main class="bg-black min-h-screen">
        <section class="container py-8 lg:flex lg:gap-4">
            <form
                @submit.prevent="postForm()"
                class="border border-white flex flex-col gap-4 mb-8 p-4 rounded w-full lg:w-1/3"
            >
                <h1
                    class="font-bold text-center text-xl text-white"
                >
                    Create a new game and invite other players
                </h1>

                <input
                    @click.prevent="postForm()"
                    class="bg-white cursor-pointer rounded py-4"
                    type="submit"
                    value="Create game"
                >
            </form>

            <aside class="border border-white flex flex-col gap-4 mb-8 p-4 rounded w-full lg:w-1/3">
                <h1
                    class="font-bold text-center text-xl text-white"
                >
                    Online users
                </h1>

                <IconLoader
                    v-if="isLoading"
                />

                <p
                    v-else-if="!onlineUsers.length"
                    class="text-white"
                >
                    No users are currently online
                </p>

                <template
                    v-for="(gamer, index) in onlineUsers"
                    :key="`user-${index}`"
                >
                    <p class="capitalize text-white">
                        {{ gamer }}

                        <span v-if="user.name === gamer">
                            (You)
                        </span>
                    </p>
                </template>
            </aside>

            <form
                @submit.prevent="postForm()"
                class="border border-white flex flex-col gap-4 mb-8 p-4 rounded w-full lg:w-1/3"
            >
                <h1
                    class="font-bold text-center text-xl text-white"
                >
                    Game invites
                </h1>

                <p
                    v-if="gameInvites.invite_from"
                    class="text-white"
                >
                    You have a game invite request from {{ gameInvites.invite_from }}
                </p>

                <button
                    v-if="gameInvites.game_slug"
                    @click.prevent="acceptInvite(gameInvites.game_slug)"
                    class="bg-white rounded text-center py-4 px-8"
                >
                    Play Game
                </button>

                <p
                    v-else
                    class="text-white"
                >
                    No Game invites
                </p>
            </form>
        </section>
    </main>
</template>