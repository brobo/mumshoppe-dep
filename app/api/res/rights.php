<?php

	/*****************************************************
	 * Copyright (c) 2014 Colby Brown                    *
	 * This program is released under the MIT license.   *
	 * For more information about the MIT license,       *
	 * visit http://opensource.org/licenses/MIT          *
	 *****************************************************/

abstract class VolunteerRights {
	const NO_RIGHTS = -1; // This is used to enforce a volunteer token without rights
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