<?php

function getRainman($game_state){
  $card = array(
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
    if ($player['id'] == 1){
      foreach ($player['hole_cards'] as $node){
        $card['rank'] = $node['rank'];
        $card['suit'] = $node['suit'];
        $cards[] = $card;
      }
    }
  }
  $encoded = urlencode(json_encode($cards));
  $result = file_get_contents('http://rainman.leanpoker.org/rank?cards=' . $encoded);

  $decoded = json_decode($result, true);

  return $decoded['rank'];
}

 ?>
