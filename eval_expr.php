<?php

Class Eval_Expr {

	public function __construct(){
		$this->final_expr = '';
		$this->loop = true;
	}

	public function eval($expr){
		if(!is_string($expr)) return "parameter must be a string\n";

		$res = $this->loop($expr, false);
		echo "res: $res\n\nfinal expr: $this->final_expr\n\n";
	}

	public function calcul($expr){
		preg_match_all("/(\d+|-|\+|\/|\*|\%)/", $expr, $output);
		if(!preg_match("/(\d+)/", $output[0][0])) return;

		$val = $output[0][0];
		$n = '';

		for($i=1; $i<count($output[0]);$i++){
			switch($output[0][$i]){
				case "-":
					//if(strlen($expr) === $i) return "function calcul 3: syntax error\n\n";
					$val = $val - $output[0][$i+1];
					break;
				case "+":
					//if(strlen($expr) === $i) return "function calcul 3: syntax error\n\n";
					$val = $val + $output[0][$i+1];
					break;
				case "*":
					//if(strlen($expr) === $i) return "function calcul 3: syntax error\n\n";
					$val = $val * $output[0][$i+1];
					break;
				default:
					break;
			}
		}
		return $val;
	}

	public function loop($expr, $parentheses=false){
		$current_expr = '';
		$parentheses_expr = '';

		for($i=0; $i<strlen($expr); $i++){
			if($this->loop){
				if(preg_match_all("/(\(|\)\d+|-|\+|\/|\*|\%)/", $expr)){

					if($expr[$i]==='('){
						$this->loop(substr($expr, $i+1), true);
					}
					else if($expr[$i]===')' && $parentheses){
						$parentheses = false;
						$this->final_expr .= $this->calcul($parentheses_expr);
						$this->loop = false;
						break;
						//if(strlen($this->expr) > strlen($expr)) $this->final_expr .= substr($expr, -1, $i+1); LOOP AT STORED END OF PARENTHESES
					}
					else {
						if(!$parentheses){
							 $this->final_expr .= $expr[$i];
						 }
						else $parentheses_expr .= $expr[$i];
					}

				}
				else return "function loop: syntax error\n\n";
			}
		}
		return $this->calcul($this->final_expr);
	}

}

$expr = new Eval_Expr();
$expr->eval("5+(5+5)");
