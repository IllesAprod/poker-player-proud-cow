<?php

require_once __DIR__ . '/../holdit.php';

class HolditTest extends PHPUnit_Framework_TestCase {

    private $gameState = <<<EOL
  {
      "tournament_id":"550d1d68cd7bd10003000003",
      "game_id":"550da1cb2d909006e90004b1",
      "round":0,
      "bet_index":0,
      "small_blind": 10,
      "current_buy_in": 320,
      "pot": 400,
      "minimum_raise": 240,
      "dealer": 1,
      "orbits": 7,
      "in_action": 1,

      "players": [
        {
              "id": 0,
              "name": "Albert",
              "status": "active",
              "version": "Default random player",
              "stack": 1010,
              "bet": 320
          },
          {
              "id": 1,
              "name": "Chuck",
              "status": "out",
              "version": "Default random player",
              "stack": 0,
              "bet": 0
          },
          {
              "id": 2,
              "name": "Bob",
              "status": "active",
              "version": "Default random player",
              "stack": 1590,
              "bet": 80,
              "hole_cards": [
                  {
                      "rank": "6",
                      "suit": "hearts"
                  },
                  {
                      "rank": "K",
                      "suit": "spades"
                  }
              ]
          }
      ],
      "community_cards": [
          {
              "rank": "4",
              "suit": "spades"
          },
          {
              "rank": "A",
              "suit": "hearts"
          },
          {
              "rank": "6",
              "suit": "clubs"
          }
      ]
  }
EOL;

    public function testCheck() {
        $state = json_decode($this->gameState, true);
        $state['current_buy_in'] = 0;
        $this->assertEquals(holdIfCheap($state), 0);
    }

    public function testBet() {
        $state = json_decode($this->gameState, true);
        $state['current_buy_in'] = 20;
        $this->assertEquals(holdIfCheap($state), 20);
    }

    public function testExpensive() {
        $state = json_decode($this->gameState, true);
        $this->assertEquals(holdIfCheap($state), 0);
    }

}
