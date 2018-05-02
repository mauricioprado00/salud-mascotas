<?php
class Saludmascotas_Helper extends Core_Helper{
	public function getInstance(){
		return self::getInstanceOf(__CLASS__);
	}
	public static function getLogedUser(){
		return Frontend_Usuario_Model_User::getLogedUser();
	}
	public function distance($lat1, $lon1, $lat2, $lon2, $unit) {
		$theta = $lon1 - $lon2; 
		$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)); 
		$dist = acos($dist); 
		$dist = rad2deg($dist); 
		$miles = $dist * 60 * 1.1515;
		$unit = strtoupper($unit);
		
		if ($unit == "K") {
		return ($miles * 1.609344); 
		} else if ($unit == "N") {
		return ($miles * 0.8684);
		} else {
		return $miles;
		}
	}
	public function distance_km($lat1, $lon1, $lat2, $lon2){
		return $this->distance($lat1, $lon1, $lat2, $lon2, 'k');
	}
	public function distance_miles($lat1, $lon1, $lat2, $lon2){
		return $this->distance($lat1, $lon1, $lat2, $lon2, 'm');
	}
	public function distance_nautical_miles($lat1, $lon1, $lat2, $lon2){
		return $this->distance($lat1, $lon1, $lat2, $lon2, 'n');
	}
	public function min_distance_km($lat1, $lon1, $lat2, $lon2){
		return $this->distance($lat1/60, $lon1/60, $lat2/60, $lon2/60, 'k');
	}
	public function min_distance_miles($lat1, $lon1, $lat2, $lon2){
		return $this->distance($lat1/60, $lon1/60, $lat2/60, $lon2/60, 'm');
	}
	public function min_distance_nautical_miles($lat1, $lon1, $lat2, $lon2){
		return $this->distance($lat1/60, $lon1/60, $lat2/60, $lon2, 'n');
	}
}
?>