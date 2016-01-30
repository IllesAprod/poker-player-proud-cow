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

    private function cardWeight($card) {
        $cardWeight = ["A", "K", "Q", "J", "10", "9", "8", "7", "6", "5", "4", "3", "2"];
        foreach ($cardWeight as $i => $rank) {
            if ($rank == $card["rank"]) {
                return $i;
            }
        }
        return 14;
    }

    private function cardsRank($card1, $card2) {
        if ($this->cardWeight($card1) < $this->cardWeight($card2)) {
            return $card1['rank'].$card2['rank'];
        } else {
            return $card2['rank'].$card1['rank'];
        }
    }

    private function cardsStrength($card1, $card2) {
        $offsuit = [
          'AA' => 1,
          'KK' => 1,
          'QQ' => 1,
          'JJ' => 1,

          '1010' => 2,
          'AK' => 2,

          '99' => 3,
          'AQ' => 3,

          '88' => 4,
          'AJ' => 4,
          'KQ' => 4,

          '77' => 5,
          'KJ' => 5,
          'QJ' => 5,
          'J10' => 5,

          '66' => 6,
          '55' => 6,
          'A10' => 6,
          'K10' => 6,
          'Q10' => 6,

          '44' => 7,
          '33' => 7,
          '22' => 7,
          'J9' => 7,
          '109' => 7,
          '98' => 7,

          'A9' => 8,
          'K9' => 8,
          'Q9' => 8,
          'J8' => 8,
          '108' => 8,
          '87' => 8,
          '76' => 8,
          '65' => 8,
          '54' => 8,
        ];

        $suit = [
          'AK' => 1,

          'AQ' => 2,
          'AJ' => 2,
          'KQ' => 2,

          'A10' => 3,
          'KJ' => 3,
          'QJ' => 3,
          'J10' => 3,

          'K10' => 4,
          'Q10' => 4,
          'J9' => 4,
          '109' => 4,
          '98' => 4,

          'A9' => 5,
          'A8' => 5,
          'A7' => 5,
          'A6' => 5,
          'A5' => 5,
          'A4' => 5,
          'A3' => 5,
          'A2' => 5,
          'Q9' => 5,
          '108' => 5,
          '87' => 5,
          '76' => 5,

          'K9' => 6,
          'J8' => 6,
          '86' => 6,
          '75' => 6,
          '54' => 6,

          'K8' => 7,
          'K7' => 7,
          'K6' => 7,
          'K5' => 7,
          'K4' => 7,
          'K3' => 7,
          'K2' => 7,
          'Q8' => 7,
          '107' => 7,
          '64' => 7,
          '53' => 7,
          '43' => 7,

          'J7' => 8,
          '96' => 8,
          '85' => 8,
          '74' => 8,
          '42' => 8,
          '32' => 8,
      ];
      $cr = $this->cardsRank($card1, $card2);
      if ($card1['suit'] == $card2['suit']) {
          if (!isset($suit[$cr])) {
              $this->log("SUIT missing cards rank $cr");
              return 9;
          } else {
              $this->log("SUIT: ".$cr." strength: ".$suit[$cr]." ".json_encode([$card1, $card2]));
              return $suit[$cr];
          }
      } else {
          if (!isset($offsuit[$cr])) {
              $this->log("OFFSUIT missing cards rank $cr");
              return 9;
          } else {
              $this->log("OFFSUIT: ".$cr." strength: ".$offsuit[$cr]." ".json_encode([$card1, $card2]));
              return $offsuit[$cr];
          }
      }
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
      $cards = $me["hole_cards"];
      $strength = $this->cardsStrength($cards[0], $cards[1]);

      if (rand(1, 8) >= $strength) {
        return $game_state['current_buy_in'];
      } else {
        return 0;
      }
    }

    public function postFlop($rainman, $game_state){
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


}
