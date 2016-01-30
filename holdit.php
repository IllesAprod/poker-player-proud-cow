<?php

function holdIfCheap($game_state, $multiplier = 1) {
    if ($game_state['current_buy_in'] > $game_state['small_blind'] * 2 * $multiplier) {
        return 0;
    }

    return (int)$game_state['current_buy_in'];
}

function maxDoubleBet($game_state) {
    return holdIfCheap($game_state, 2);
}

function minBet($game_state, $maxMultiplier = 4) {
    return max($game_state['small_blind'] * 2, holdIfCheap($game_state, $maxMultiplier));
}
