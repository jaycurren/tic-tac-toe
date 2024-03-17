<script setup lang="ts">
    import { onMounted, ref } from "vue";
    import { router } from "@inertiajs/vue3";

    import IconLoader from "@/Components/IconLoader.vue";
    // @ts-ignore
    import TicTacGrid from "@/Components/TicTacGrid.vue";

    interface GameData {
        slug: String
    }

    interface GamePlayData {
        game: Array<Number>;
        turn: Number;
        winner: String
    }

    interface UserData {
        name: String;
    }

    const props = defineProps<{
        game: GameData,
        user: UserData
    }>();

    let gamePlay = ref<GamePlayData>({
        game: [0, 0, 0, 0, 0, 0, 0, 0, 0],
        turn: 1,
        winner: "none"
    });
    let onlineUsers = ref<Array<String>>([]);

    onMounted(() => {
        // @ts-ignore
        Echo.private(`games.play.${props.game.slug}`)
        .listen("BroadcastGamerPlay", data => {
            gamePlay.value.game = data.game;
            gamePlay.value.turn = data.turn;
            gamePlay.value.winner = data.winner;
        });
    });

    const pickSquare = square => {
        gamePlay.value.game[square] = 2;
        gamePlay.value.turn = 1;

        // @ts-ignore
        router.post(`/game/${props.game.slug}/turn`, gamePlay.value);
    }
</script>

<template>
    <main class="bg-black min-h-screen">
        <div class="container py-8 lg:flex lg:items-baseline lg:gap-4">
            <section class="border border-white flex flex-col gap-4 mb-8 p-4 rounded w-full">
                <TicTacGrid
                    @pick-square="pickSquare"
                    :game="game"
                    :gameData="gamePlay.game"
                    :player="2"
                    :turn="gamePlay.turn"
                    :user="user"
                />
            </section>
        </div>
    </main>
</template>