<?php

namespace PHPixie\ORM\Relationships\OneToMany\Preloader;

class Items extends \PHPixie\ORM\Model\Preloader\Multiple{
	
	protected function process_items() {
		$this->items = array();
		$this->ids_map = array();
		$id_field = $this->repository->id_field();
		$key = $this->link->config()->item_key;
		foreach($this->reusable_result_step->iterator() as $item_data) {
			$id = $item_data->$id_field;
			$this->items[$id] = $item_data;
			$owner_id = $item_data->$key;
			if (!isset($this->ids_map[$owner_id]))
				$this->ids_map[$owner_id] = array();
			$this->ids_map[$owner_id][] = $id;
		}
	}
	
	protected function get_item_ids($owner) {
		return $this->ids_map[$owner->id()];
	}
	
}