<?php

function convert_To_RGBA($input, $alpha = NULL) {

  $rgba_Value = FALSE;
  $input = strtolower($input);
  $input_Types = confirm_Input_Type($input);
  
  if (($input_Types['colorKeyword'] === TRUE) && ($input_Types['hex'] === FALSE)) {
    
    $rgba_Value = color_Keyword_To_RGBA($input, $alpha);
  }
  
  elseif (($input_Types['colorKeyword'] === FALSE) && ($input_Types['hex'] === TRUE)) {

    $rgba_Value = hex_To_RGBA($input, $alpha);
  }
  
  elseif (($input_Types['colorKeyword'] === TRUE) && ($input_Types['hex'] === TRUE)) {
    
    $rgba_Value_1 = hex_To_RGBA($input, $alpha);

    $rgba_Value_2 = color_Keyword_To_RGBA($input, $alpha);

    $rgba_Value = ($rgba_Value_2 === FALSE) ? rgbaValue1 : rgbaValue2;
  }
  
  return $rgba_Value;
}

?>
