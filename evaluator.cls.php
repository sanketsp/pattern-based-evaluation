<!--
/*!
 * Paper Pattern Evaluator v1.0
 *
 * Copyright 2015 Sanket Patel
 * Released under the MIT license
 * http://in.linkedin.com/in/sanketsp
 *
 * Date: 24th Jan 2015
 */
-->
<?php
class evaluator{

	private $expression;
	private $marks_array;
	private $exp_stack;
	private $operator_stack;
	private $operand_stack;
	private $postfix_stack;
	private $final_marks;
	
	public function __construct(){
		
		$this->exp_stack = array();
		$this->operator_stack = array();
		$this->operand_stack = array();
		$this->postfix_stack = array();
	}
	
	public function evaluate($expr, $marks){

		$this->expression = $expr;
		$this->marks_array = $marks;
		
		//handling all possible string inputs - START
		$this->expression = "( ".$this->expression." )";//imp
		$this->expression = str_replace(" ", "", $this->expression);//remove all the spaces
		$this->expression = strtoupper($this->expression);				
		$replace_to_array = array("OR", "AND", "ANY", "(", ")");
		$replace_with_array = array(" OR ", " AND ", " ANY", " ( ", " ) ");
		$this->expression = str_replace($replace_to_array, $replace_with_array, $this->expression);
		$this->expression = str_replace("  ", " ", $this->expression);//replace 2 spaces with 1 space
		$this->expression = trim($this->expression);
		//handling all possible string inputs - END
		
		$this->exp_stack = explode(" ", $this->expression);
		foreach($this->exp_stack AS $exp_index=>$exp_value){
			if(is_numeric($exp_value)){
			
				array_push($this->operand_stack, $this->marks_array[$exp_value]);
				
			}elseif(strstr(strtoupper($exp_value), "ANY")){
			
				array_push($this->operand_stack, $exp_value);
				
			}elseif($exp_value == ")"){
			
				array_push($this->operand_stack, array_pop($this->operator_stack));
				array_pop($this->operator_stack);//also pop opening "("
				
			}else{
				if(strtoupper($exp_value) == $this->operator_stack[count($this->operator_stack)-1]){//for case like 1 or 2 or 3 
					array_push($this->operand_stack, strtoupper($exp_value));
				}else{
					array_push($this->operator_stack, strtoupper($exp_value));
				}				
			}
		}
		
		$this->operand_stack = array_reverse($this->operand_stack);
		while(is_array($this->operand_stack) && count($this->operand_stack) > 0){
		
			$pop = array_pop($this->operand_stack);
			$pop = strtoupper($pop);
			
			if(is_numeric($pop)){
			
				array_push($this->postfix_stack, $pop);//add to postfix stack
				
			}elseif($pop == "AND"){
			
				array_push($this->postfix_stack, (array_pop($this->postfix_stack) + array_pop($this->postfix_stack)));//add to postfix stack
				
			}elseif($pop == "OR"){
			
				$num1 = array_pop($this->postfix_stack);
				$num2 = array_pop($this->postfix_stack);
				$max = 0;
				if($num1 > $num2){
					$max = $num1;
				}else{
					$max = $num2;
				}
				array_push($this->postfix_stack, $max);//add to postfix stack
				
			}elseif(strstr($pop, "ANY")){
			
				$pop = str_replace("ANY", "", $pop);
				
				$any_array = explode(":", $pop);
				$any_num = $any_array[0];
				$cs_ques_list = $any_array[1];
				
				$ques_array = explode(",", $cs_ques_list);
				foreach($ques_array AS $index=>$q_no){
					$any_marks_array[$q_no] = $this->marks_array[$q_no];
				}
				arsort($any_marks_array);//sorts in descending order
				$ini_any_marks_ctr = count($any_marks_array);
				for($i = $any_num; $i < $ini_any_marks_ctr; $i++){//remove excessive array values
					array_pop($any_marks_array);
				}
				$any_ans = array_sum($any_marks_array);
				
				array_push($this->postfix_stack, $any_ans);//add to postfix stack
			}
		}
		$this->final_marks = $this->postfix_stack[0];
	}

	public function getFinalMarks(){
		if(is_numeric($this->final_marks)){
			return $this->final_marks;
		}
		return 0;
	}

}
?>