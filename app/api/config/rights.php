<?php

abstract class VolunteerRights {
	const ConfigureItems = 1;
	const ViewMums = 2;
	const MarkMumsPaid = 4;
	const DeleteMums = 8;
	const TruncateMums = 16;
	const ChangeVolunteerPerms = 32;
	const DeleteVolunteer = 64;
	const CreateVolunteer = 128;
	
	public static function CreateBitmask(array $rights) {
		$mask = 0;
		foreach($rights as $right)
			$mask = $mask | $right;
		
		return $mask;
	}

	public static function HasRight($mask, $right) {
		return ($mask & $right) == $right;
	}
}