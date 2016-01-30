<?php

function holdIfCheap($game_state) {
    if ($game_state['current_buy_in'] > $game_state['small_blind'] * 2) {
        return 0;
    }

    return (int)$game_state['current_buy_in'];
}

function maxDoubleBet($game_state) {
    if ($game_state['current_buy_in'] > $game_state['small_blind'] * 4) {
        return 0;
    }

    return (int)$game_state['current_buy_in'];
}
