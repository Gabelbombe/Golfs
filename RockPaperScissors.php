<?php
/**
 * Based of of the rules from: http://codegolf.stackexchange.com/questions/45177/rock-paper-scissors-competition-simulator
 *
 * You decided to organize a rock-paper-scissors championship to find out who is the best. You don't want to let
 * luck to decide the winner so everyone has to give you his or her tactic in writing before the competition. You
 * also like simple things so a move of a competitor (showing rock, paper or scissors) has to be based only the
 * previous turn (RvR, RvP, RvS, PvR, PvP, PvS, SvR, SvP or SvS). In the first turn a player has to show a fixed sign.
 *
 * You decided to write a program (or function) to simulate the championship.
 * Details of the competition
 *
 * There will be at least 2 contestants.
 * Every player plays exactly one match with everyone else.
 * One match lasts 7 rounds.
 * In every round the winner gets 2 points the loser gets none. In the event of a tie both player score 1 point.
 * A players score in a match is the sum of his or her points over the turns of the match.
 * A players final score in the championship is the sum of his or her points over all matches.
 *
 * Details of the input:
 *
 * your program or function receives N 10 character long strings each of them corresponds to a players strategy. All
 * characters are (lowercase) r p or s meaning that in the given situation the player will show rock paper or scissors.
 * The first letter codes the first turn (in every match for that competitor). The second shows what happens if the
 * last round was rock vs rock. The next ones are RvP, RvS, PvR, PvP, PvS, SvR, SvP and SvS where the first letter is
 * the player's sign and the second is the opponent's. E.g. rrpsrpsrps means the player starts with rock and then copies
 * the opponent's last move.
 * You can input the list of strings as a list/array or similar data of your language or as one string. In the latter
 * case some kind of separator character is a must.
 *
 * Details of the output:
 *
 * Your program or function should output the final scores of each player in the same order as the input was supplied.
 * Scores should be separated by spaces or newlines. Trailing space or newline is allowed.
 *
 * Examples:
 *
 * Input: ['rrpsrpsrps', 'rpppsprrpr']
 *
 * Output: 5 9 (turns are rvr rvp pvs svp pvr rvp pvs)
 *
 * Input: ['rrpsrpsrps', 'rpppsprrpr', 'ssssssssss']
 *
 * Output: 13 17 12 (matches are 5-9 (1st vs 2nd), 8-6 (1st vs 3rd) and 8-6 (2nd vs 3rd))
 */
Namespace Games
{
  class RockPaperScissors
  {
    const ROUNDS = 7;     // will actually be 6 because of 0
    const MOVES  = 10;

    protected $players  = 1,
              $tally    = [],
              $hands    = [];

    private $plays      = ['r','p','s'];

    public function __construct(array $hands, $players = false)
    {
      if ($this->testPlayers($hands))
      {
        $this->hands = $hands;

        if (! empty($players) && is_int($players) & $players > count($hands))
        {
          while (count($this->hands) !== $players) $this->hands[] = $this->assign();
        }

        $this->split($this->hands); // expand....
        $this->tally = array_fill(0, count($this->hands), [
            'points' => 0,
            'hand'   => '',
            'games'  => [],
        ]);
      }
    }

    public function play()
    {
      foreach ($this->hands AS $p1 => $h1)
      {
        $this->tally[$p1]['hand'] = implode('',$h1);
        $contenders = $this->hands;

          unset($contenders[$p1]);

        foreach ($contenders AS $p2 => $h2)
        {
          $i = 1;
          while (self::ROUNDS >= $i)
          {
            $this->compare([$p1 => $this->next($h1, $i)], [$p2 => $this->next($h2, $i)]);
            $i++;
          }
        }
      }
      return $this->tally;
    }

    private function next(array $hand, $inc)
    {
      return $hand[$inc-1];
    }


    private function pickRandom(array $hand)
    {
      return $hand[rand(0,count($hand)-1)];
    }


    private function assign()
    {
      $val = '';
      while(self::MOVES > strlen($val)) $val .= str_shuffle(implode('',$this->plays));
      return substr($val,0,10);
    }

    private function only($string, $opts)
    {
      $opts = str_split($opts);
      foreach(str_split($string) AS $char) if(false === in_array($char, $opts)) return $char;
      return false;
    }

    private function testPlayers($hands)
    {
      if (empty($hands) || count($hands) < $this->players)
        Throw New \Exception("There must be at least two players....");

      foreach ($hands AS $id => $hand)
      {
        if (self::MOVES !== strlen($hand))
          Throw New \Exception("Please check player ". ++$id ."'s hand. Hands must be ". self::MOVES . " moves in length.");

        if ($c = $this->only($hand, 'rps'))
          Throw New \Exception("Hand can only consist of " . strtoupper(implode($this->plays, ' ')) . ", move " . strtoupper($c) . " unknown.");
      }
      return true;
    }

    private function split($hands)
    {
      foreach ($hands AS &$hand) $hand = str_split($hand);
      $this->hands = $hands;
    }

    private function compare(array $c1, array $c2)
    {
      $map = array_flip($this->plays);

      $p1 = key($c1);
      $p2 = key($c2);

      $c1 = reset($c1);
      $c2 = reset($c2);

      $noc =  ((3 + $map[$c1] - $map[$c2]) % 3);

      switch ($noc)
      {
        case(0):
          $this->tally[$p1]['points'] += 1;
          $this->tally[$p1]['games'][$p2][] = "VS: C{$p2} Tie: "     . strtoupper($c1) . 'v' . strtoupper($c2);

          $this->tally[$p2]['points'] += 1;
          $this->tally[$p2]['games'][$p1][] = "VS: C{$p1} Tie: "     . strtoupper($c2) . 'v' . strtoupper($c1);
        break;

        case(1):
          $this->tally[$p1]['points'] += 2;
          $this->tally[$p1]['games'][$p2][] = "VS: C{$p2} Win: "     . strtoupper($c1) . 'v' . strtoupper($c2);

          $this->tally[$p2]['points'] += 0;
          $this->tally[$p2]['games'][$p1][] = "VS: C{$p1} Lse: "     . strtoupper($c2) . 'v' . strtoupper($c1);
        break;

        case(2):
          $this->tally[$p1]['points'] += 0;
          $this->tally[$p1]['games'][$p2][] = "VS: C{$p2} Lse: "     . strtoupper($c1) . 'v' . strtoupper($c2);

          $this->tally[$p2]['points'] += 2;
          $this->tally[$p2]['games'][$p1][] = "VS: C{$p1} Win: "     . strtoupper($c2) . 'v' . strtoupper($c1);
        break;

      }
      return true;
    }
  }
}

Namespace
{
  USE \Games\RockPaperScissors AS RPS;

  $RPS = New RPS(['psrpsrprsr'], 3);
  $game = $RPS->play();

  foreach($game AS $id => $player)
  {
    echo "Player: {$id} got {$player['points']}\n";
  }
echo "\n";
}
