<?php

use Base\Bear as BaseBear;

class Bear extends BaseBear
{
	public function getFull() {
		$res = array(
			'Id' => $this->getId(),
			'ItemId' => $this->getItemId(),
			'Name' => $this->getName(),
			'Underclassman' => $this->getUnderclassman(),
			'Junior' => $this->getJunior(),
			'Senior' => $this->getSenior(),
			'Price' => $this->getPrice()
		);

		return $res;
	}
}
