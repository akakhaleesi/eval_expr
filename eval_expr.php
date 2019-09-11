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

		$res = $this->loop($this->expr, false);
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
				case "/":
					//if(strlen($expr) === $i) return "function calcul 3: syntax error\n\n";
					$val = $val / $output[0][$i+1];
					break;
				case "%":
					//if(strlen($expr) === $i) return "function calcul 3: syntax error\n\n";
					$val = $val % $output[0][$i+1];
					break;
				default:
					break;
			}
		}
		return $val;
	}

	public function loop($expr, $parentheses=false){
		var_dump("LOOOOOOOOOOOP");
		$parentheses_expr = '';

		for($i=0; $i<strlen($expr); $i++){
			if($this->loop){
				if(preg_match_all("/(\(|\)\d+|-|\+|\/|\*|\%)/", $expr)){

					if($expr[$i]==='('){
						$this->stored_expr = $i;
						$this->loop(substr($expr, $i+1), true);
					}
					else if($expr[$i]===')' && $parentheses){
						$this->stored_expr++;
						$this->loop = false;
						$parentheses = false;
						$this->final_expr .= $this->calcul($parentheses_expr);
						var_dump(strlen($this->expr), $this->stored_expr);
						if(strlen($this->expr) > $this->stored_expr){
							$this->loop = true;
							echo "HOLLY SHIT\n\n";
							var_dump(substr($this->expr, $this->stored_expr+1));
							$this->loop(substr($this->expr, $this->stored_expr+1));
							$this->loop = false;
							$this->stored_expr = null;
						}
						else break;
					}
					else {
						if(!$parentheses){
							$this->final_expr .= $expr[$i];
						}
						else {
							$this->stored_expr++;
							$parentheses_expr .= $expr[$i];
						}
					}

				}
				else return "function loop: syntax error\n\n";
			}
		}
		return $this->calcul($this->final_expr);
	}

}

$expr = new Eval_Expr("5+(10+5)/2");
$expr->eval();
