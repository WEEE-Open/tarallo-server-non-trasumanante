<?php
# T.A.R.A.L.L.O. - Location
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

trait LocationTrait {
	var $location_ID;
	var $location_when;
	var $location_parent;

	/**
	 * item_ID
	 *
	 * @type int
	 */
	var $container;

	/**
	 * item_ID
	 *
	 * @type int
	 */
	var $item;

	/**
	 * Force having the location_ID.
	 *
	 * @return int
	 */
	function getLocationID() {
		isset( $this->location_ID )
			|| error_die("Missing location_ID");

		return $this->location_ID;
	}
}

class Location {
	use LocationTrait;

	function __construct() {
		self::normalize($this);
	}

	static function normalize($t) {
		if( isset( $t->location_ID ) ) {
			$t->location_ID = (int) $t->location_ID;
		}
	}

	static function get() {
		$q = new DynamicQuery();
		return $q->useTable('location');
	}

	static function insert($container, $item, $when = null, $parent = null) {
		$filter = 'd';

		if( ! $when ) {
			$when = 'NOW()';
			$filter = '-';
		}

		insert_row('location', [
			new DBCol('location_when',   $when,      $filter),
			new DBCol('location_parent', $parent,    'dnull'),
			new DBCol('container',       $container, 'd'),
			new DBCol('item',            $item,      'd')
		] );

		return last_inserted_ID();
	}

	static function getByContainer($container_ID) {
		return self::get()->appendCondition( sprintf(
			'container = %d',
			$container_ID
		) );
	}
}
