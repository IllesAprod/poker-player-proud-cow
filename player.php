<?php

class Player
{
    const VERSION = "Default PHP folding player";

    public function betRequest($game_state)
    {
      $cards = array();

      foreach ($game_state['community_cards'] as $node){
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


          if ($me["stack"] > 1800) {
             return 0;
          } elseif ($game_state['community_cards']) {
              return 1000000;
          } else {
              if ($playerCards[0]['rank'] == $playerCards[1]['rank']) {
                  return 1000000;
              } elseif (rand(0, 100) < 50) {
                  return 0;
              } else {
                 return 1000000;
              }
          }

          }

    private function me($game_state)
    {
        foreach ($game_state['players'] as $player) {
            if ($player["id"] == 2) {
                file_put_contents("php://stderr", json_encode($player));
                return $player;
            }
        }
        file_put_contents("php://stderr", "Nincs kartya a kezben");
        return null;
    }

    public function showdown($game_state)
    {
    }
}
