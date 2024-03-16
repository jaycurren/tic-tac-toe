<script setup lang="ts">
    import { onMounted, ref } from "vue";
    import { router } from "@inertiajs/vue3";

    import IconLoader from "@/Components/IconLoader.vue";

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

    let formData = ref<FromData>({ invite_name: "", user_name: "", slug: "" });
    let inviteSent = ref<Boolean>(false);
    let onlineUsers = ref<Array<string>>([]);

    onMounted(() => {
        formData.value.user_name = props.user.name;
        formData.value.slug = props.game.slug;

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

    const inviteUser = userName => {
        inviteSent.value = true;
        formData.value.invite_name = userName;
        router.post("/game/invite", formData.value);
    }
</script>

<template>
    <main class="bg-black min-h-screen">
        <div class="container py-8 lg:flex lg:items-baseline lg:gap-4">
            <section class="border border-white flex flex-col gap-4 mb-8 p-4 rounded w-full">
                <p
                    v-if="!game.player_two && inviteSent"
                    class="font-bold text-center text-xl text-white"
                >
                    Waiting for player to accept
                </p>

                <p
                    v-else-if="!game.player_two"
                    class="font-bold text-center text-xl text-white"
                >
                    Invite a player from the list below
                </p>

                <p
                    v-if="inviteSent"
                    class="text-white"
                >
                    Invite pending...
                </p>

                <p
                    v-else-if="!onlineUsers.length"
                    class="text-white"
                >
                    No users are currently online
                </p>

                <div
                    v-else
                    v-for="(gamer, index) in onlineUsers"
                    :key="`user-${index}`"
                    class="border-b border-white flex items-center justify-between mb-4 pb-4"
                >
                    <p class="capitalize text-white">
                        {{ gamer }}

                        <span v-if="user.name === gamer">
                            (You)
                        </span>
                    </p>

                    <button
                        v-if="user.name !== gamer"
                        @click.prevent="inviteUser(gamer)"
                        class="bg-white rounded py-4 px-8"
                    >
                        Invite
                    </button>
                </div>
            </section>
        </div>
    </main>
</template>