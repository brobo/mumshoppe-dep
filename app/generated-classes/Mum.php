<?php

use Base\Mum as BaseMum;

class Mum extends BaseMum
{

	private function calculatePrice($fullMum) {

		$totalPrice = 0;
		$totalPrice += $fullMum['Backing']['Price'];
		$totalPrice += $fullMum['Letter1']['Price'] * strlen($fullMum['Mum']['NameRibbon1']);
		$totalPrice += $fullMum['Letter2']['Price'] * strlen($fullMum['Mum']['NameRibbon2']);
		foreach ($fullMum['Bears'] as $bear) {
			$totalPrice += $bear['Price'];
		}
		foreach ($fullMum['Trinkets'] as $trinket) {
			$totalPrice += $trinket['Quantity'] * $trinket['Trinket']['Price'];
		}
		return $totalPrice;
	}

	public function getFull() {
		$res = array(
			'Mum' => $this,
			'Customer' => $this->getCustomer() ? array(
				'Name' => $this->getCustomer(),
				'Id' => $this->getCustomer()->getId()
			) : null,
			'Accent_bow' => $this->getAccentBow(),
			'Backing' => $this->getBacking(),
			'Bears' => $this->getBears(),
			'Bears' => $this->getBears(),
			'Grade' => $this->getBacking() ? $this->getBacking()->getGrade() : null,
			'Product' => $this->getBacking() && $this->getBacking()->getSize() ? $this->getBacking()->getSize()->getProduct() : null,
			'Size' => $this->getBacking() ? $this->getBacking()->getSize() : null,
			'Status' => $this->getStatus(),
			'Trinkets' => $this->getMumTrinkets()
		);

		foreach ($res as $key => $value) {
			if ($key == 'Customer') continue;
			if ($res[$key]) {
				$res[$key] = $res[$key]->toArray();
			}
		}

		for ($i = 0; $i < count($res['Trinkets']); $i++) {
			$res['Trinkets'][$i]['Trinket'] = 
				TrinketQuery::create()->findPK($res['Trinkets'][$i]['TrinketId'])->toArray();
		}

		$res['Letter1'] = $res['Mum']['Letter1Id']
			? LetterQuery::create()->findPK($res['Mum']['Letter1Id'])->toArray()
			: null;
		
		$res['Letter2'] = $res['Mum']['Letter2Id']
			? LetterQuery::create()->findPK($res['Mum']['Letter2Id'])->toArray()
			: null;

		$res['TotalPrice'] = $this->calculatePrice($res);

		return $res;
	}
}
