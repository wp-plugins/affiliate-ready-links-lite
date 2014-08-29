<?php

function RdyLnk_trim_r($item) {
	if(!is_array($item)) {
		return trim($item);
	} else {
		$array = array();
		foreach($item as $key => $value) {
			$array[$key] = RdyLnk_trim_r($value);
		}
		return $array;
	}
}
