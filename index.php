<?php
$state = [
    ['', '', ''],
    ['', '', ''],
    ['', '', ''],
];
$player = 'X';
$activeCell = [0 => 0, 1 => 0];
function renderGame($state, $activeCell, $player){
    $output = '';
    $output .= 'Player:' . $player . "\n";
    foreach ($state as $x => $line){
      $output .= '|';
      foreach ($line as $y => $item){
        switch ($item){
          case '';
            $cell = ' ';
            break;
          case 'X';
            $cell = 'X';
            break;
          case 'O';
            $cell = 'O';
            break;
        }
        if ($activeCell[0] == $x && $activeCell[1] == $y) {
          $cell = '-'. $cell . '-';
        }
        else {
          $cell = ' ' . $cell . ' ';
        }
        $output .= $cell . '|';
      }
      $output .= "\n";
    }
    return $output;
  }
function translateKeypress($string) {
    switch ($string) {
      case "\033[A":
        return "UP";
      case "\033[B":
        return "DOWN";
      case "\033[C":
        return "RIGHT";
      case "\033[D":
        return "LEFT";
      case "\n":
        return "ENTER";
      case " ":
        return "SPACE";
      case "\010":
      case "\177":
        return "BACKSPACE";
      case "\t":
        return "TAB";
      case "\e":
        return "ESC";
     }
    return $string;
  }
  function move($stdin, &$state, &$activeCell, &$player) {
    $key = fgets($stdin);
    if ($key) {
      $key = translateKeypress($key);
      switch ($key) {
        case "UP":
          if ($activeCell[0] >= 1) {
            $activeCell[0]--;
          }
          break;
        case "DOWN":
          if ($activeCell[0] < 2) {
            $activeCell[0]++;
          }
          break;
        case "RIGHT":
          if ($activeCell[1] < 2) {
            $activeCell[1]++;
          }
          break;
        case "LEFT":
          if ($activeCell[1] >= 1) {
            $activeCell[1]--;
          }
          break;
        case "ENTER":
        case "SPACE":
          if ($state[$activeCell[0]][$activeCell[1]] == '') {
            $state[$activeCell[0]][$activeCell[1]] = $player;
            if ($player == 'X') {
              $player = 'O';
            } else {
              $player = 'X';
            }
          }
          break;
       }
    }
  }
  function isWinState($state) {
    foreach (['X', 'O'] as $player) {
      foreach ($state as $x => $line) {
        if ($state[$x][0] == $player && $state[$x][1] == $player && $state[$x][2] == $player) {
          die($player . ' wins');
        }
        foreach ($line as $y => $item) {
          if ($state[0][$y] == $player && $state[1][$y] == $player && $state[2][$y] == $player) {
            die($player . ' wins');
          }
        }
      }
      if ($state[0][0] == $player && $state[1][1] == $player && $state[2][2] == $player) {
        die($player . ' wins');
      }
      if ($state[2][0] == $player && $state[1][1] == $player && $state[0][2] == $player) {
        die($player . ' wins');
      }
    }
    $blankQuares = 0;
    foreach ($state as $x => $line) {
      foreach ($line as $y => $item) {
        if ($state[$x][$y] == '') {
          $blankQuares++;
        }
      }
    }
    if ($blankQuares == 0) {
      die('DRAW!');
    }
  }
  $stdin = fopen('php://stdin', 'r');
  stream_set_blocking($stdin, 0);
  system('stty cbreak -echo');
  while (1) {
    system('clear');
    move($stdin, $state, $activeCell, $player);
    echo renderGame($state, $activeCell, $player);
    isWinState($state);
  }
?>