<?php

class Player
{
    const VERSION = "Default PHP folding player";

    public function betRequest($game_state)
    {

      /*$card = array(
        "rank" => "",
        "suit" => "",
      );

      $cards = array();

      foreach ($game_state['community_cards'] as $node){
        $card['rank'] = $node['rank'];
        $card['suit'] = $node['suit'];
        $cards[] = $card;
      }

      foreach ($game_state['players'] as $player){
        if ($player['id'] == 2){
          foreach ($player['hole_cards'] as $node){
            $card['rank'] = $node['rank'];
            $card['suit'] = $node['suit'];
            $cards[] = $card;
          }
        }
      }
      $encoded = http_build_query(array('cards' => $cards));
      $opts = array('http' =>
        array(
          'method' => 'GET',
          'header' => 'Content-type: application/x-www-form-urlencoded',
          'content' => $encoded
        )
      );

      $context = stream_context_create($opts);

      $response = file_get_contents('http://rainman.leanpoker.org/rank', false, $opts);
*/
        return 1000000;
    }

    public function showdown($game_state)
    {
    }
}
