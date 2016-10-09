<?php
class UserController extends BaseController {
	public static function not_used_auth($token) {
		$params = array('token' => $token);
		$user = new User($params);
		if ($user->is_valid()) {
			return true;
		} else {
			return false;
		}
	}

};


?>
