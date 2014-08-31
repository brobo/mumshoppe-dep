<?php

use Base\Size as BaseSize;

class Size extends BaseSize
{

	public function getFull() {
		$res = array(
			'Id' => $this->getId(),
			'Name' => $this->getName(),
			'BearLimit' => $this->getBearLimit(),
			'ProductId' => $this->getProductId()
		);

		return $res;
	}

}
