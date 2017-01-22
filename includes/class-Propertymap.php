<?php
# T.A.R.A.L.L.O. - Propertymap
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

trait PropertymapTrait {
	/*
	var $propertymap_parent;
	var $propertymap_child;
	*/
}

class_exists('Property');

class Propertymap {
	use PropertymapTrait;
	use PropertyTrait;

	function __construct() {
		self::normalize($this);
		Property::normalize($this);
	}

	static function normalize($t) {
		if( isset( $t->propertymap_parent ) ) {
			$t->propertymap_parent = (int) $t->propertymap_parent;
		}
		if( isset( $t->propertymap_child ) ) {
			$t->propertymap_child  = (int) $t->propertymap_child;
		}
	}

	static function get() {
		$q = new DynamicQuery();
		return $q->useTable('propertymap');
	}

	static function getByParentID($parent_ID) {
		$q = self::get()->useTable('property');
		$q->appendCondition('propertymap_child = property.property_ID');
		return $q->appendCondition( sprintf(
			'propertymap_parent = %d',
			$parent_ID
		) );
	}

	static function getByChildrenID($child_ID) {
		$q = self::get()->useTable('property');
		$q->appendCondition('propertymap_parent = property.property_ID');
		return $q->appendCondition( sprintf(
			'propertymap_child = %d',
			$child_ID
		) );
	}
}
