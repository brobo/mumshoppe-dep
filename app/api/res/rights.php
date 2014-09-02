<?php

abstract class VolunteerRights {
	const ConfigureItems = 0;
	const ViewMums = 1;
	const MarkMumsPaid = 2;
	const DeleteMums = 3;
	const TruncateMums = 4;
	const ChangeVolunteerPerms = 5;
	const DeleteVolunteer = 6;
	const CreateVolunteer = 7;
	const ToggleOrders = 8;
	
	public static function CreateBitmask(array $rights) {
		$mask = 0;
		foreach($rights as $right)
			$mask = $mask | (1<<$right);
		
		return $mask;
	}

	public static function HasRight($mask, $right) {
		return ($mask & (1<<$right)) === (1<<$right);
	}
}