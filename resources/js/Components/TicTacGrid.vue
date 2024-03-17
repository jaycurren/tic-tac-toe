<script lang="ts" setup>
    import { defineEmits, onMounted, ref } from "vue";

    interface GameData {
        slug: string
    }

    interface UserData {
        name: string;
    }

    const emit = defineEmits(["pick-square"]);

    const props = defineProps<{
        game: GameData,
        gameData: Array<Number>,
        player: Number,
        turn: Number,
        user: UserData
    }>();

    const pickSquare = square => {
        if (props.turn === props.player) {
            emit("pick-square", square);
        }
    }
</script>

<template>
    <section class="border border-white flex flex-col gap-4 p-4 rounded w-full">
        <header class="flex justify-center relative">
            <p class="font-bold mb-8 text-center text-xl text-white">
                Tic Tac Toe Game
            </p>

            <p class="absolute right-0 text-white">
                Player {{ turn }} turn
            </p>
        </header>

        <ul class="flex flex-wrap mx-auto w-60 lg:w-96">
            <li
                v-for="square in 9"
                @click.prevent="pickSquare(square)"
                :class="[
                    { 'border-b-4 border-r-4': square === 1 },
                    { 'border-b-4 border-r-4': square === 2 },
                    { 'border-b-4': square === 3 },
                    { 'border-b-4 border-r-4': square === 4 },
                    { 'border-b-4 border-r-4': square === 5 },
                    { 'border-b-4': square === 6 },
                    { 'border-r-4': square === 7 },
                    { 'border-r-4': square === 8 },
                    turn === player ? 'cursor-pointer' : 'cursor-not-allowed'
                ]"
                :key="`square-${square}`"
                class="border-white flex-shrink-0 flex font-bold items-center justify-center h-20 text-4xl text-white w-20 lg:h-32 lg:text-6xl lg:w-32"
            >
                <span v-if="gameData[square] && gameData[square] === 1">
                    X
                </span>

                <span v-else-if="gameData[square] && gameData[square] === 2">
                    O
                </span>
            </li>
        </ul>
    </section>
</template>