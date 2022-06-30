<?php

function hex_To_RGBA ($hexString, $alpha = NULL) {

  $hexString = (substr($hexString, 0, 1) === '#') ? substr($hexString, 1) : $hexString;

  $hex8 = [];
  $hex = str_split($hexString);

  if ((count($hex) === 3) || (count($hex) === 4)) {

    $hex8[0] = $hex8[1] = $hex[0];
    $hex8[2] = $hex8[3] = $hex[1];
    $hex8[4] = $hex8[5] = $hex[2];
    $hex8[6] = $hex8[7] = $hex[3] ?? 'F';
  }

  elseif ((count($hex) === 6) || (count($hex) === 8)) {

    $hex8 = $hex;
    $hex8[6] = $hex8[6] ?? 'F';
    $hex8[7] = $hex8[7] ?? 'F';
  }

  else return FALSE;

  $r = intval(implode(array_slice($hex8, 0, 2)), 16);
  $g = intval(implode(array_slice($hex8, 2, 2)), 16);
  $b = intval(implode(array_slice($hex8, 4, 2)), 16);
  $a = (round((intval(implode(array_slice($hex8, 6, 2)), 16) / 255 * 100), 2));

  return (($alpha !== NULL) && ($alpha <= 1) && ($alpha >= 0)) ? 'rgba('.$r.', '.$g.', '.$b.', '.$alpha.')' : 'rgba('.$r.', '.$g.', '.$b.', '.$a.')';
}

?>
