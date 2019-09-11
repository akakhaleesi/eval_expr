<?php

Class Eval_Expr {

	public function __construct($expr){
		$this->expr = $expr;
		$this->operators = ['+','-','/','*','%','(',')'];
		$this->numbers = ['0','1','2','3','4','5','6','7','8','9'];
		$this->final_expr = '';
	}

	public function eval(){
		if(!is_string($this->expr)) return "parameter must be a string\n";

		return $this->loop($this->expr, false);

		return "res: $res\n\nfinal expr: $this->final_expr\n\n";
	}

	public function calcul($expr){
		if(strlen($expr) < 1) return "function calcul 1: syntax error\n\n";
		if(!in_array($expr[0], $this->numbers)) return "function calcul 2: syntax error\n\n";
		$last_n = $expr[0];

		for($i=1; $i<strlen($expr);$i++){

			switch($expr[$i]){

				case "-":
					if(strlen($expr) === $i) return "function calcul 3: syntax error\n\n";
					$last_n = $last_n - $expr[$i+1];
					break;

				case "+":
					if(strlen($expr) === $i) return "function calcul 3: syntax error\n\n";
					$last_n = $last_n + $expr[$i+1];
					break;

				case "*":
					if(strlen($expr) === $i) return "function calcul 3: syntax error\n\n";
					$last_n = $last_n * $expr[$i+1];
					break;

				default: echo "default: $expr[$i]\n\n";
			}
		}
		return $last_n;
	}

	public function loop($expr, $parentheses=false){echo "$expr\n\n";
		$current_expr = '';
		$parentheses_expr = '';

		for($i=0; $i<strlen($expr); $i++){

			if(in_array($expr[$i], $this->operators) || in_array($expr[$i], $this->numbers)){
echo "$expr[$i]\n\n";
				if($expr[$i]==='('){echo "[1]\n\n";
					//if($i !== 0) $this->final_expr .= substr($expr, 0, $i);
					$this->loop(substr($expr, $i+1), true);
				}

				else if($expr[$i]===')'){echo "[2]\n\n";
					$parentheses = false;
					echo "parentheses: $parentheses_expr\n\n";
					$this->final_expr .= $this->calcul($parentheses_expr);
					echo "final exprrrrrrrrrrrrrr: $this->final_expr\n\n";
					//if(strlen($this->expr) > strlen($expr)) $this->final_expr .= substr($expr, -1, $i+1); LOOP AT STORED END OF PARENTHESES
					//$this->loop($this->final_expr, false);
				}

				else {echo "[3]\n\n";
					if(!$parentheses) $this->final_expr .= $expr[$i];
					else $parentheses_expr .= $expr[$i];
				}

			}
			else return "function loop: syntax error\n\n";
		}
		echo "MOTHAFUCKED SUPPPOSED END OF LOOP\n\n";
		return $this->calcul($this->final_expr);
	}

}

$expr = new Eval_Expr("5+(5+5)");
echo $expr->eval();
