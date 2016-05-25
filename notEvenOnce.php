<?php

class display {
    private $meth;
	
	public function __construct($meth) {
		$this->meth = $meth;
		$this->round = 5;
		$this->primseList = [
			'f1' => 2.33333,
			'f2' => 0.40546,
			'f3' => 4.67077,
			'f4' => 0.95644,
		];
	}
	
	public function prime($func) {
		return ($this->primseList[$func]);
	}
	
	public function get($type, $func) {
		return ($this->meth->{$type}($func));
	}
	
	public function all() {
		$out = [];
		$a = ['left', 'right', 'center'];
		$b = ['f1', 'f2', 'f3', 'f4'];
		foreach ($b as $func) {			
			$out[$func]['prime'] = $this->prime($func);
			foreach ($a as $type) {
				$out[$func][$type] = (float) number_format($this->get($type, $func), $this->round);
			}
		}
		return ($out);
	}
	
	public function table() {
		$out = $this->all();
		$mask = "| %5.5s | %10.10s | %10.10s | %10.10s | %10.10s |\n";
		
		$a = printf($mask, 'f(x)', 'prime', 'left', 'right', 'center');
		printf(str_repeat('-', $a - 1) . "\n");
		foreach ($out as $key => $row) {
			printf($mask, $key, $row['prime'], $row['left'], $row['right'], $row['center']);
		}
		printf(str_repeat('-', $a - 1));
	}
}

class base {
    public function f1($result) {
		return (pow($result, 2));
	}

	public function f2($result) {
		return (1 / (1 + $result));
	}

	public function f3($result) {
		return (exp($result));
	}

	public function f4($result) {
		return (sin($result));
	}
	
	public function xi($a, $h, $i) {
		return ($a + ($i * $h));
	}
}

class Meth extends base {
	private $a;
	private $b;
	private $n;
	private $h;
	
	public function __construct($a, $b, $n) {
		$this->a = $a;
		$this->b = $b;
		$this->n = $n;
		$this->h = (($b - $a) / $n);	
	}
	
    public function left($func) {
		$out = 0;
		
		for ($i = 0; $i < $this->n; $i++) {
			$out += ($this->h * $this->{$func}($this->xi($this->a, $this->h, $i)));
		}
		
		return ($out);
	}

	public function right($func) {
		$out = 0;
		
		for ($i = 1; $i <= $this->n; $i++) {
			$out += ($this->h * $this->{$func}($this->xi($this->a, $this->h, $i)));
		}
		
		return ($out);
	}

	public function center($func) {
		$out = 0;
		
		for ($i = 0; $i < $this->n; $i++) {
			$out += ($this->h * $this->{$func}(($this->xi($this->a, $this->h, $i) + ($this->h / 2))));
		}
		
		return ($out);
	}
	
	public function display() {
		return (new display($this));
	}
}


$cat = new Meth(1, 2, 20);
$cat->display()->table();
