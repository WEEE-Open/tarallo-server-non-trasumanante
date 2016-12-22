<?php
# T.A.R.A.L.L.O. - Property
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

trait PropertyTrait {
	/*
	var $property_ID;
	var $property_uid;
	var $property_name;
	var $property_parent;
	*/

	/**
	 * Force having the property_ID.
	 *
	 * @return int
	 */
	function getPropertyID() {
		isset( $this->property_ID )
			|| error_die("Missing property_ID");

		return $this->property_ID;
	}
}

class Property {
	use PropertyTrait;

	function __construct() {
		self::normalize($this);
	}

	static function normalize($t) {
		if( isset( $t->property_ID ) ) {
			$t->property_ID = (int) $t->property_ID;
		}
	}

	static function get() {
		$q = new DinamicQuery();
		return $q->useTable('property');
	}
}
