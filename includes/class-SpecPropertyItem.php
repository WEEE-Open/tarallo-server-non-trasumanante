<?php
# T.A.R.A.L.L.O. - Join between spec, property and item
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

class_exists('Spec');
class_exists('Property');
class_exists('Item');

trait SpecPropertyItemTrait {
	function updateSpecValue($value) {
		Spec::update(
			$this->getPropertyID(),
			$this->getItemID(),
			$value
		);
	}
}

class SpecPropertyItem {
	use SpecPropertyItemTrait;
	use SpecTrait;
	use PropertyTrait;
	use ItemTrait;

	function __construct() {
		Spec::normalize($this);
		Property::normalize($this);
		Item::normalize($this);
	}

	/**
	 * @return DynamicQuery
	 */
	static function get() {
		return SpecProperty::get()->useTable('item')->appendCondition(
			'spec.item_ID = item.item_ID'
		);
	}

	/**
	 * Get by item UID and property UID
	 *
	 * @param string $item item_uid
	 * @param string $property property_uid
	 *
	 * return DynamicQuery
	 */
	static function getByUID($item, $property) {
		$item     = luser_input($item,     64);
		$property = luser_input($property, 64);

		$q = self::get()->appendCondition( sprintf(
			"item_uid = '%s'",
			esc_sql( $item )
		) );
		return $q->appendCondition( sprintf(
			"property_uid = '%s'",
			esc_sql( $property )
		) );
	}

	static function insert($property_ID, $item_ID, $value) {
		insert_row('spec',
			new DBCol('spec_value',         $value,              'snull'),
			new DBCol('spec_creation_user', get_user('user_ID'), 'd'),
			new DBCol('spec_creation_date', 'NOW()',             '-'),
			new DBCol('property_ID',        $property_ID,        'd'),
			new DBCol('item_ID',            $item_ID,            'd')
		);
		return last_inserted_ID();
	}
}
