<?php

require_once "evaluator.cls.php";

$eval = new evaluator();
		
$marks_array[1] = 4;
$marks_array[2] = 6;
$marks_array[3] = 10;
$marks_array[4] = 5;
$marks_array[5] = 9;
$marks_array[6] = 6;
$marks_array[7] = 7;

$pattern = "(2 and ANY1 : 4,5,6,7 )or   3";
//$pattern = "( 2 AND 3 ) OR ( 4 AND 5 )";
//$pattern = " 3 or 1 ";
//$pattern = "( 4 or 1 ) and 5";
//$pattern = "(  4 and 1 and 5 and 2 )";

$eval->evaluate($pattern, $marks_array);
echo $eval->getFinalMarks();
?>