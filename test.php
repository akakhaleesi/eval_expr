<?php

Class Eval_Expr {

	public function __construct($expr){
		$this->expr = $expr;
		$this->final_expr = '';
		$this->stored_expr = null;
		$this->loop = true;
	}

	public function eval(){
		if(!is_string($this->expr)) return "parameter must be a string\n";


    var_dump(preg_split("/\(([^\)]+)\)/", $this->expr));
		//if(preg_match("/(\\(+\d+(\-|\+|\\/|\*|\%)+\d++\\))/", $this->expr, $output)) $this->getBrackets($this->expr, $output[0]);


		// /(?<=\()(.+)(?=\))/is
		// (?<!\d)
		// ((\d+) |-|\+|\/|\*|\%\))

		//$res = $this->loop($this->expr, false);
		//echo "\n\ncalcul: $this->final_expr\n\nresult: $res\n\n";
	}

	public function getBrackets($expr, $output){
		// var_dump($expr);
		// var_dump($output);
		// var_dump(preg_replace("/$output/", 'X', $expr));
		// array_splice($output[0], $i-1, 0, "$res");var_dump('res  == ',$output[0]);

		// $pattern = "/$output[0]/";
		// preg_match($pattern, $expr, $matches, PREG_OFFSET_CAPTURE );
		// print_r($matches);

		// $patterns = [];
		// $replace = [];
		// for($i=0; $i<count($output); $i++){
		// 	$patterns[$i] = "/$output[$i]/";
		// 	$replace[$i] = "$i";
		// 	var_dump($patterns[$i]);
		// 	var_dump(preg_match($patterns[$i], $expr));
		// }
		//var_dump(preg_replace($patterns, $replace, $expr));
	}

	public function loop($expr, $parentheses=false){
		$parentheses_expr = '';

		for($i=0; $i<strlen($expr); $i++){
			//if($this->loop){
				if(preg_match("/(\(|\)|\d+|-|\+|\/|\*|\%)/", $expr)){

					if($expr[$i]==='('){
						//$this->stored_expr = $i;
						return $this->loop(substr($expr, $i+1), true);
						break;
					}
					else if($expr[$i]===')' && $parentheses){
						$this->stored_expr = $i;
						$this->loop = false;
						$parentheses = false;
						$this->final_expr .= $this->calcul($parentheses_expr);

						if(strlen($this->expr) > $this->stored_expr+1){
							//$this->loop = true;
							return $this->loop(substr($this->expr, $this->stored_expr+1));

							//$this->loop = false;
							$this->stored_expr = null;
							break;
						}
						else break;
					}
					else {
						if(!$parentheses){
							$this->final_expr .= $expr[$i];
						}
						else {
							//$this->stored_expr++;
							$parentheses_expr .= $expr[$i];
						}
					}

				}
				else return "function loop: syntax error\n\n";
			//}
		}
		return $this->calcul($this->final_expr);
	}

	public function calcul($expr){
		preg_match_all("/(\d+|-|\+|\/|\*|\%)/", $expr, $output);
		if(!preg_match("/(\d+)/", $output[0][0])) return;
		//$this->stored_expr = null;
		for($i=0; $i < count($output[0]); $i++){
			var_dump('calcul loop ', $output[$i]);
			if($output[0][$i]==='*'){var_dump('i === '.$i);
				$multi = array_splice($output[0], $i-1, 3);var_dump($multi);
				$res = $this->switch($multi);var_dump($res);
				array_splice($output[0], $i-1, 0, "$res");var_dump('res  == ',$output[0]);
				break;
			}
		}
		var_dump('calcul ===============',$output[0]);
		//var_dump(array_search('*', $output[0]));
		// if($index = array_search('*', $output[0])){var_dump('multiplication');
		// 	//svar_dump(0]);
		// 	$priority = array_splice($output[0], $index-1, 3);//var_dump($toto, $output[0]);
		// 	$this->stored_expr = $output[0];
		// 	$this->calculPriority($priority);
		// 	$res = $this->switch($toto);
		// 	array_unshift($output[0] , $res);
		// }v
		//var_dump($output[0]);
		$res = $this->switch($output[0]);//var_dump($output[0]);

		return $res;
	}

	// public function calculPriority($array, $res=''){
	// 	$res .= $this->switch($array);
	// 	if($index = array_search('*', $this->stored_expr))
	// }

	public function switch($array){//var_dump($array);
		$val = $array[0];
		$n = '';

		for($i=1; $i<count($array);$i++){
			switch($array[$i]){
				case "-":
					$i++;
					//if(strlen($expr) === $i) return "function calcul 3: syntax error\n\n";
					$val = $val - $array[$i];
					break;
				case "+":
					$i++;//var_dump($array[$i]);
					//if(strlen($expr) === $i) return "function calcul 3: syntax error\n\n";
					$val = $val + $array[$i];
					break;
				case "*":
					$i++;
					//if(strlen($expr) === $i) return "function calcul 3: syntax error\n\n";
					$val = $val * $array[$i];
					break;
				case "/":
					$i++;
					//if(strlen($expr) === $i) return "function calcul 3: syntax error\n\n";
					$val = $val / $array[$i];
					break;
				case "%":
					$i++;
					//if(strlen($expr) === $i) return "function calcul 3: syntax error\n\n";
					$val = $val % $array[$i];
					break;
				default:
					break;
			}
		}
		return $val;
	}

}

$expr = new Eval_Expr("5+(6*5)+2+(5+5)+(5+(8+8))");
$expr->eval();
