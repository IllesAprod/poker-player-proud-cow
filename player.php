<?php
require_once 'api.php';
require_once 'holdit.php';

class Player {
    const VERSION = "Default PHP folding player";

    public function betRequest($game_state) {

        $rainman = getRainman($game_state);

        if (count($game_state['community_cards']) == 0){
          return $this->preFlop($rainman, $game_state);
        } else {
          return $this->postFlop($rainman, $game_state);
        }

    }


    private function me($game_state) {
        foreach ($game_state['players'] as $player) {
            if ($player["id"] == 2) {
                return $player;
            }
        }
        $this->log("NEVER HAPPEN.");
        return null;
    }

    public function showdown($game_state) {
    }

    private function log($message) {
        file_put_contents("php://stderr", $message);
    }

    private function preFlopCardStrength($game_state)
    {
        $cards = $this->me($game_state)["hole_cards"];
        $smallCards = ["2", "3", "4", "5", "6", "7", "8"];
        foreach ($cards as $card) {
            if (in_array($card['rank'], $smallCards)) {
                $this->log("SMALL CARD " .json_encode($cards));
                return 0;
            }
        }
        $this->log("GOOD CARDS " . json_encode($cards));
        return 1;
    }

    public function preFlop($rainman, $game_state){
      $me = $this->me($game_state);
      if ($rainman['rank'] >= 1) {
          return 1000000;
      } elseif ($this->preFlopCardStrength($game_state) == 0) {
          return 0;
      } else {
          return $game_state['current_buy_in'];
      }
    }

    public function postFlop($rainman, $game_state){
        if ($this->headsUp($game_state)) {
            if ($rainman['rank'] == 2 && $rainman['value'] > 7) {
                return holdIfCheap($game_state, 4);
            } elseif ($rainman['rank'] == 2) {
                return maxDoubleBet($game_state);
            }

            if ($rainman['rank'] == 3 && $rainman['value'] <= 7){
                if ($this->isRiver($game_state)) {

                }
                return minBet($game_state, 6);
            }

            if ($rainman['rank'] >= 3) {
                return 100000;
            }

            return holdIfCheap($game_state);
        }

      if ($rainman['rank'] == 0){
        return 0;
      }

      if ($rainman['rank'] == 2 && $rainman['value'] > 7){
        return maxDoubleBet($game_state);
      }

      if ($rainman['rank'] == 3 && $rainman['value'] <= 7){
        return minBet($game_state);
      } elseif ($rainman['rank'] == 3) {
        return minBet($game_state, 6);
      }

        if ($rainman['rank'] > 3) {
            return 100000;
        }

      return holdIfCheap($game_state);
    }

    private function headsUp($game_state) {
        $playersIn = 0;
        foreach ($game_state['players'] as $player) {
            if ($player['status'] === 'active') {
                $playersIn += 1;
            }
        }
        return $playersIn === 2;
    }

    private function isRiver($game_state) {
        return count($game_state['community_cards']) == 5;
    }

}
