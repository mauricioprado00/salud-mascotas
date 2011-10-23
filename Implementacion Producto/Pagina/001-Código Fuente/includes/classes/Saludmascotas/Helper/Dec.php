<?php
class Saludmascotas_Helper_Dec extends Saludmascotas_Helper{
	public function getInstance(){
		return self::getInstanceOf(__CLASS__);
	}
	public function simple($mat, $pesos, $max=null, &$ord_sorted=null){
		$sumas = array();
//		$fill_null = array();
		$mat = array_values($mat);
		foreach($mat as $i=>&$row){
			$row = array_values($row);
			foreach($row as $j=>&$val){
				if(!isset($val)&&isset($max[$j]))
					$val = $max[$j];
				//if(!isset($sumas[$j])||$val>$sumas[$j])
				$sumas[$j] += $val;
			}
		}

//		echo "entrada:\n";
//		foreach($mat as $i=>&$row){
//			echo implode(', ', $row)."\n";
//		}
//		echo "sumas:\n";
//		echo implode(', ', $sumas)."\n";

		
		//homogeneizar matriz
		foreach($mat as $i=>&$row){
			foreach($row as $j=>&$val){
				if(!isset($val))
					$val = 1;
				elseif(!isset($sumas[$j])||$sumas[$j]==0)
					$val = 0;
				else $val = $val/$sumas[$j];
			}
		}
//		echo "salida:\n";
//		foreach($mat as $i=>&$row){
//			echo implode(', ', $row)."\n";
//		}
//		echo "pesos:\n";
//		echo implode(', ', $pesos)."\n";
		
		$ord = array();
		foreach($mat as $i=>&$row){
			$ord[$i] = 0;
			foreach($row as $j=>&$val){
				$ord[$i] += $val * $pesos[$j];
			}
		}
		
//		echo "orden:\n";
//		echo implode(', ', $ord)."\n";
		$ord_sorted = $ord;
		arsort($ord_sorted);

//		echo "ord sorted\n";
//		var_dump($ord_sorted);

		$ord_idx = array_keys($ord_sorted);
//		echo "idx order\n";
//		echo implode(', ', $ord_idx)."\n";
		
		return $ord_idx;
	}
}
?>