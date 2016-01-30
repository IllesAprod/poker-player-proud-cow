<?php
require_once 'api.php';
require_once 'holdit.php';

class Player {
    const VERSION = "Default PHP folding player";

    public function betRequest($game_state) {
        $cards = array();

        foreach ($game_state['community_cards'] as $node) {
            $card['rank'] = $node['rank'];
            $card['suit'] = $node['suit'];
            $cards += $card;
        }

        $me = $this->me($game_state);
        $playerCards = $me["hole_cards"];
        //   $encoded = http_build_query(array('cards' => $cards));
        //   $opts = array('http' =>
        //     array(
        //       'method' => 'GET',
        //       'header' => 'Content-type: application/x-www-form-urlencoded',
        //       'content' => $encoded
        //     )
        //   );
        //
        //   $context = stream_context_create($opts);
        //
        //   $response = file_get_contents('http://rainman.leanpoker.org/rank', false, $opts);
        $smallBlind = $game_state['small_blind'];

        // if (smallBlind < 200) {
        //   return 0
        // } else {
        //   return 1000000
        // }

        $rainman = getRainman($game_state);

        if (count($game_state['community_cards']) == 0){
          return $this->preFlop($rainman, $game_state);
        } else {
            if ($val = $this->postFlop($rainman, $game_state)) {
                return $val;
            }

            return holdIfCheap($game_state);
        }

    }


    private function me($game_state) {
        foreach ($game_state['players'] as $player) {
            if ($player["id"] == 2) {
                return $player;
            }
        }
        $this->log("NEVER HAPPEN.")
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
      $me = $this->me($game_state);
      if ($rainman['rank'] >= 1) {
          return 1000000;
      } elseif ($me["stack"] > 1800) {
          return 0;
          // } elseif ($game_state['community_cards']) {
          //     return 1000000;
          //
      } else {
          if (rand(0, 100) < 50) {
              return 0;
          } else {
              return 1000000;
          }
      }
    }
}
