<?php

use Base\Backing as BaseBacking;

class Backing extends BaseBacking
{

	public function getFull() {
		$res = array(
			'Id' => $this->getId(),
			'ItemId' => $this->getItemId(),
			'Name' => $this->getName(),
			'Price' => $this->getPrice(),
			'SizeId' => $this->getSizeId(),
			'GradeId' => $this->getGradeId(),
			'HasImage' => !!$this->getImage()
		);

		return $res;
	}

}
