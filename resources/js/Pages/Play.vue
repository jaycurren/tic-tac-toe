<script setup lang="ts">
    import { onMounted, ref } from "vue";
    import { router, usePage } from "@inertiajs/vue3";

    import IconLoader from "@/Components/IconLoader.vue";
    // @ts-ignore
    import TicTacGrid from "@/Components/TicTacGrid.vue";

    interface FromData {
        invite_name: String;
        user_name: String;
        slug: String;
    }

    interface GameData {
        slug: String;
        winner: String;
    }

    interface GamePlayData {
        game: Array<Number>;
        turn: Number;
        winner: String;
    }

    interface UserData {
        name: String;
    }

    const { props } = usePage();

    const playProps = defineProps<{
        game: GameData,
        user: UserData
    }>();

    let formData = ref<FromData>({ invite_name: "", user_name: "", slug: "" });
    let gamePlay = ref<GamePlayData>({
        game: [0, 0, 0, 0, 0, 0, 0, 0, 0],
        turn: 1,
        winner: "none"
    });
    let inviteSent = ref<String>("");
    let onlineUsers = ref<Array<string>>([]);

    onMounted(() => {
        if (playProps.game.winner) gamePlay.value.winner = playProps.game.winner;

        formData.value.user_name = playProps.user.name;
        formData.value.slug = playProps.game.slug;

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

        // @ts-ignore
        Echo.channel(`games.accept.${playProps.user.name}`)
        .listen("BroadcastGamerAccept", data => {
            inviteSent.value = "accepted";

            // @ts-ignore
            Echo.private(`games.play.${playProps.game.slug}`)
            .listen("BroadcastGamerPlay", data => {
                gamePlay.value.game = data.game;
                gamePlay.value.turn = data.turn;
                gamePlay.value.winner = data.winner;
            });
        });
    });

    const inviteUser = userName => {
        inviteSent.value = "sent";
        formData.value.invite_name = userName;
        // @ts-ignore
        router.post("/game/invite", formData.value);
    }

    const pickSquare = data => {
        gamePlay.value.game[data.square] = 1;
        gamePlay.value.turn = 2;
        gamePlay.value.winner = data.winner !== "none" ? "player_one" : "none";

        // @ts-ignore
        router.post(`/game/${playProps.game.slug}/turn`, gamePlay.value);
    }
</script>

<template>
    <main class="bg-black min-h-screen">
        <div class="container py-8 lg:flex lg:items-baseline lg:gap-4">
            <section class="border border-white flex flex-col gap-4 mb-8 p-4 rounded w-full">
                <TicTacGrid
                    v-if="inviteSent === 'accepted'"
                    @pick-square="pickSquare"
                    :game="game"
                    :gameData="gamePlay.game"
                    :player="1"
                    :turn="gamePlay.turn"
                    :user="user"
                    :winner="gamePlay.winner"
                />

                <p
                    v-else-if="!game.player_two && inviteSent === 'sent'"
                    class="font-bold text-center text-xl text-white"
                >
                    Invite pending...
                </p>

                <p
                    v-else-if="!game.player_two"
                    class="font-bold text-center text-xl text-white"
                >
                    Invite a player from the list below
                </p>

                <p
                    v-if="!onlineUsers.length"
                    class="text-white"
                >
                    No users are currently online
                </p>

                <div
                    v-else-if="!inviteSent"
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