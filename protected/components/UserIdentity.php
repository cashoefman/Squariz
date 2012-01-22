<?php
/**
 * Simple class to work with foursquare
 * @author undsoft
 *
 */
class UserIdentity extends CUserIdentity
{
	/**
	 * Authenticates a user.
	 * Actually a dummy function, because authentication is done elsewhere.
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
		$this->errorCode==self::ERROR_NONE;
	}
}