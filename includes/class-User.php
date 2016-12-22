<?php
# T.A.R.A.L.L.O. - User
# Copyright (C) 2016 Ludovico Pavesi, Valerio Bozzolan
#
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU Affero General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU Affero General Public License for more details.
#
# You should have received a copy of the GNU Affero General Public License
# along with this program.  If not, see <http://www.gnu.org/licenses/>.

trait UserTrait {
	var $user_ID;
	var $user_uid;
	var $user_name;
	var $user_surname;
	var $user_password;
	var $user_active;

	/**
	 * Force having the user_ID.
	 *
	 * @return int
	 */
	function getUserID() {
		isset( $this->user_ID )
			|| error_die("Missing user_ID");

		return $this->user_ID;
	}

	/**
	 * Force having the user_uid
	 */
	function getUserUID() {
		isset( $this->user_uid )
			|| error_die("Missing user_uid");

		return $this->user_uid;
	}

	/**
	 * Force knowing if the user is active.
	 *
	 * @return bool
 	 */
	function isUserActive() {
		isset( $this->user_active )
			|| error_die("Missing user_active");

		return $this->user_active;
	}
}

class User extends Sessionuser {
	use UserTrait;

	function construct() {
		self::normalize($this);
	}

	static function normalize($t) {
		if( isset( $t->user_ID ) ) {
			$t->user_ID      = (int)        $t->user_ID;
		}

		if( isset( $t->user_active ) ) {
			$t->user_active  = (bool) (int) $t->user_active;
		}
	}

	function get() {
		$q = new DynamicQuery();
		return $q->useTable('user');
	}
}
