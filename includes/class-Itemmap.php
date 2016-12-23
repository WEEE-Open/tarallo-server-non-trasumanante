<?php
# T.A.R.A.L.L.O. - Itemmap
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

trait ItemmapTrait {
	/*
	var $itemmap_ID;
	var $itemmap_subject;
	var $itemmap_container;
	var $itemmap_creation_user;
	var $itemmap_creation_date;
	var $itemmap_formal_user;
	var $itemmap_formal_date;
	var $itemmap_parent;
	*/

	/**
	 * Force having the itemmap_ID.
	 *
	 * @return int
	 */
	function getItemmapID() {
		isset( $this->itemmap_ID )
			|| error_die("Missing itemmap_ID");

		return $this->itemmap_ID;
	}
}

class Itemmap {
	use ItemmapTrait;

	function __construct() {
		self::normalize($this);
	}

	static function normalize($t) {
		if( isset( $t->itemmap_ID ) ) {
			$t->itemmap_ID = (int) $t->itemmap_ID;
		}
	}

	static function get() {
		$q = new DynamicQuery();
		return $q->useTable('itemmap');
	}

	static function getByContainer($container_ID) {
		return self::get()->appendCondition( sprintf(
			'itemmap_container = %d',
			$container_ID
		) );
	}
}
