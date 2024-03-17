<script lang="ts" setup>
    import { defineEmits, onMounted, ref } from "vue";

    interface GameData {
        slug: String;
        winner: String;
    }

    interface UserData {
        name: String;
    }

    const emit = defineEmits(["pick-square"]);

    const props = defineProps<{
        game: GameData,
        gameData: Array<Number>,
        player: Number,
        turn: Number,
        user: UserData,
        winner: String
    }>();

    const winningPatterns = [
        [1, 1, 1, 0, 0, 0, 0, 0, 0],
        [0, 0, 0, 1, 1, 1, 0, 0, 0],
        [0, 0, 0, 0, 0, 0, 1, 1, 1],

        [1, 0, 0, 1, 0, 0, 1, 0, 0],
        [0, 1, 0, 0, 1, 0, 0, 1, 0],
        [0, 0, 1, 0, 0, 1, 0, 0, 1],

        [1, 0, 0, 0, 1, 0, 0, 0, 1],
        [0, 0, 1, 0, 1, 0, 1, 0, 0],
    ];

    let gameWon = ref<Boolean>(false);

    onMounted(() => {
        if (props.game.winner) gameWon.value = true;
    });

    const checkWinningPatterns = (currentPlaces): Boolean => {
        let playerWinMatch = 0;

        for (let i = 0; i < winningPatterns.length; i ++) {
            for (let x = 0; x < winningPatterns[i].length; x++) {
                for (let y = 0; y < currentPlaces.length; y++) {
                    // Reset winning pattern count again on new array
                    if (!y) playerWinMatch = 0;

                    if (winningPatterns[i][y] === 1 && currentPlaces[y] === props.player) {
                        playerWinMatch++;

                        // Check has matched, so return
                        if (playerWinMatch === 3) return true;
                    }
                }
            }
        }

        return false;
    }

    const pickSquare = square => {
        let currentPlaces: Array<Number> = props.gameData.map(data => data);

        currentPlaces[square] = props.player;

        const winner = checkWinningPatterns(currentPlaces) ? props.player : "none";

        if (props.turn === props.player && !gameWon.value) {
            emit("pick-square", { square, winner });

            if (winner !== "none") gameWon.value = false;
        }
    }
</script>

<template>
    <section class="border border-white flex flex-col gap-4 p-4 rounded w-full">
        <header class="lg:flex lg:justify-center lg:relative">
            <p class="font-bold mb-4 text-center text-xl text-white">
                Tic Tac Toe Game
            </p>

            <p class="right-0 text-center mb-4 text-white lg:absolute">
                {{ turn === player ? "Your": "Opponents" }} turn (You are {{ player === 1 ? 'O' : 'X' }})
            </p>
        </header>

        <p
            v-if="winner !== 'none'"
            class="font-bold mb-4 text-4xl text-center text-white"
        >
            <span v-if="winner === 'player_one' && player === 1 || winner === 'player_two' && player === 2">
                You won
            </span>

            <span v-else>You lost</span>
        </p>

        <ul class="flex flex-wrap mx-auto relative w-60 lg:w-96">
            <li
                v-for="(square, index) in 9"
                @click.prevent="pickSquare(index)"
                :class="[
                    { 'border-b-4 border-r-4': square === 1 },
                    { 'border-b-4 border-r-4': square === 2 },
                    { 'border-b-4': square === 3 },
                    { 'border-b-4 border-r-4': square === 4 },
                    { 'border-b-4 border-r-4': square === 5 },
                    { 'border-b-4': square === 6 },
                    { 'border-r-4': square === 7 },
                    { 'border-r-4': square === 8 },
                    turn === player && winner === 'none' ? 'cursor-pointer' : 'cursor-not-allowed'
                ]"
                :key="`square-${square}`"
                class="border-white flex-shrink-0 flex font-bold items-center justify-center h-20 text-4xl text-white w-20 lg:h-32 lg:text-6xl lg:w-32"
            >
                <span v-if="gameData[index] && gameData[index] === 1">
                    O
                </span>

                <span v-else-if="gameData[index] && gameData[index] === 2">
                    X
                </span>
            </li>
        </ul>
    </section>
</template>