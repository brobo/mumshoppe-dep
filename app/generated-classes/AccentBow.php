<?php

use Base\AccentBow as BaseAccentBow;

class AccentBow extends BaseAccentBow
{

	public function getFull() {
		$res = array(
			'Id' => $this->getId(),
			'ItemId' => $this->getItemId(),
			'Name' => $this->getName(),
			'GradeId' => $this->getGradeId(),
			'HasImage' => !!$this->getImageMime()
		);

		return $res;
	}

}
