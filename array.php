<?php
   $ham = array(array('a', 'b', 'c'), array('d', 'e', 'f'), array('h', 'i' , 'j'));

   for ($i = 0; $i < 3; $i++) { // Loop through each subarray
      for ($j = 0; $j < 3; $j++) { // Loop through each element on the current subarray
         // Print current element
         echo $ham[$i][$j] . ': ';

         // Print adjacent element (maximum: 4, minimum 2) LEFT RIGHT UP DOWN
         if ($j > 0) echo $ham[$i][$j - 1]; // LEFT
         if ($j < 2) echo $ham[$i][$j + 1]; // RIGHT
         if ($i > 0) echo $ham[$i - 1][$j]; // UP
         if ($i < 2) echo $ham[$i + 1][$j]; // DOWN

         // Go to new line
         echo "<br>";
      }
   }
?>
