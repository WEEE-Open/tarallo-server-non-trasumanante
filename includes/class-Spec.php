<?php
# T.A.R.A.L.L.O. - Spec
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

trait SpecTrait {
	/*
	var $spec_value;
	var $spec_creation_user;
	var $spec_creation_date;
	var $item_ID;
	var $property_ID;
	 */

	/**
	 * @return bool
	 */
	function hasSpecValue() {
		property_exists($this, 'spec_value')
			|| error_die("Unset spec_value");

		return isset( $this->spec_value );
	}

	function getSpecValue() {
		return $this->spec_value;
	}
}

class Spec {
	use SpecTrait;

	function __construct() {
		// Ehm... boh.
	}

	static function normalize() {
		// Ehm... boh.
	}

	static function get() {
		$q = new DynamicQuery();
		return $q->useTable('spec');
	}
}
