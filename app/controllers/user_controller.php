<?php
class UserController extends BaseController {
	public static function remove_not_used_auth($token) {
		$params = array('token' => $token);
		$user = new User($params);
		if ($user->is_admin) {
			return true;
		} else {
			return false;
		}
	}

};


?>
