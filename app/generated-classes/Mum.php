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
		foreach ($fullMum['Accessories'] as $accessory) {
			$totalPrice += $accessory['Quantity'] * $accessory['Accessory']['Price'];
		}
		return $totalPrice;
	}

	public function getFull() {
		$res = array(
			'Mum' => $this,
			'Customer' => $this->getCustomer() ? array(
				'Name' => $this->getCustomer()->getName(),
				'Id' => $this->getCustomer()->getId()
			) : null,
			'Accent_bow' => $this->getAccentBow()->getFull(),
			'Backing' => $this->getBacking()->getFull(),
			'Bears' => array_map(function($bear) {
							return $bear->getFull();
						}, $this->getBears()->getData()),
			'Grade' => $this->getBacking() ? $this->getBacking()->getGrade() : null,
			'Product' => $this->getBacking() && $this->getBacking()->getSize() ? $this->getBacking()->getSize()->getProduct() : null,
			'Size' => $this->getBacking() ? $this->getBacking()->getSize()->getFull() : null,
			'Status' => $this->getStatus(),
			'Accessories' => $this->getMumAccessories()
		);

		foreach ($res as $key => $value) {
			if (!method_exists($res[$key], 'toArray')) continue;
			if ($res[$key]) {
				$res[$key] = $res[$key]->toArray();
			}
		}

		for ($i = 0; $i < count($res['Accessories']); $i++) {
			$res['Accessories'][$i]['Accessory'] = 
				AccessoryQuery::create()
					->select(array('Id', 'Name', 'Underclassman', 'Junior', 'Senior', 'Price', 'CategoryId'))
					->findPK($res['Accessories'][$i]['AccessoryId']);
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

	public function getMini() {
		$full = $this->getFull();

		$res = array(
			'Id' => $full['Mum']['Id'],
			'Status' => $full['Status'],
			'Grade' => $full['Grade'],
			'OrderDate' => $full['Mum']['OrderDate'],
			'Paid' => $full['Mum']['Paid'],
			'Backing' => $full['Backing'],
			'Customer' => $full['Customer'],
			'TotalPrice' => $full['TotalPrice']
		);
	
		return $res;
	}
}
