<?php
# T.A.R.A.L.L.O. - Item
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

trait ItemTrait {
	/**
	var $item_ID;
	var $item_uid;
	*/

	/**
	 * Force having the item_ID.
	 *
	 * @return int
	 */
	function getItemID() {
		isset( $this->item_ID )
			|| error_die("Missing item_ID");

		return $this->item_ID;
	}

	/**
	 * Force having the item_uid.
	 *
	 * @return string
	 */
	function getItemUID() {
		isset( $this->item_uid )
			|| error_die("Missing item_uid");

		return $this->item_uid;
	}

	function getItemSpecifications() {
		return SpecProperty::get()->appendCondition( sprintf(
			'spec.item_ID = %d',
			$this->getItemID()
		) );
	}

	function getContainedItems() {
		return Itemmap::getByContainer( $this->getItemID() )
			->useTable('item')
			->appendCondition('itemmap_contained = item.item_ID');
	}

	function getContainerItem() {
		return Itemmap::getByContained( $this->getItemID() )
			->useTable('item')
			->appendCondition('itemmap_container = item.item_ID');
	}
}

class Item {
	use ItemTrait;

	function __construct() {
		self::normalize($this);
	}

	static function normalize($t) {
		if( isset( $t->item_ID ) ) {
			$t->item_ID      = (int) $t->item_ID;
		}

		if( isset( $t->item_active ) ) {
			$t->item_active  = (bool) (int) $t->item_active;
		}
	}

	static function get() {
		$q = new DynamicQuery();
		return $q->useTable('item');
	}

	static function getByID($ID) {
		return self::get()->appendCondition(
			sprintf(
				'item.item_ID = %d',
				$ID
			)
		);
	}

	static function getByUID($uid) {
		return self::get()->appendCondition( sprintf(
			"item_uid = '%s'",
			esc_sql( luser_input( $uid, 32 ) )
		) );
	}

	static function canInsert() {
		return has_permission('insert-item');
	}

	static function canEditAll() {
		return has_permission('edit-all-item');
	}

	static function insert($item_uid) {
		insert_row('item', [
			new DBCol('item_uid', $item_uid, 's')
		] );
	}
}
