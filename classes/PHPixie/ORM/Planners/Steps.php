<?php

namespace \PHPixie\ORM\Planners;

use \PHPixie\ORM\Planners\Steps\Step;

class Steps {
	
	public function query($query) {
		return new Step\Query($query);
	}
	
	public function result_query($query) {
		return new Step\Query\Result($query);
	}
	
	public function in_subquery($query, $placeholder, $field) {
		return new Step\InSubquery($query, $placeholder, $field);
	}
	
	public function cross_insert($query, $left_key, $right_key) {
		return new Step\Cross\Insert($query, $left_key, $right_key);
	}
	
	public function cross_delete($query, $left_key, $right_key) {
		return new Step\Cross\Delete($query, $left_key, $right_key);
	}
	
	public function push($update_query, $path) {
		return new Step\Push($update_query, $path);
	}
	
	public function pull($update_query, $path, $id_field) {
		return new Step\Pull($update_query, $path, $id_field);
	}
	
}