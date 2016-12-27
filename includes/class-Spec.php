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

	/**
	 * Editable when I've created this entry, or if I'm the spec administrator.
	 *
	 * @return bool
	 */
	function isSpecEditable() {
		if( ! is_logged() ) {
			return false;
		}

		return $this->getSpecCreationUserID() === get_user()->getUserID() || Spec::canEditAll();
	}

	/**
	 * Assicure that the creation_user_ID was selected in the query, and return it.
	 *
	 * @return int
	 */
	function getSpecCreationUserID() {
		isset( $this->spec_creation_user )
			|| error_die("Missing spec_creation_user_ID");

		return $this->spec_creation_user;
	}
}

class Spec {
	use SpecTrait;

	function __construct() {
		self::normalize( $this );
	}

	static function normalize($t) {
		if( isset( $t->spec_creation_user ) ) {
			$t->spec_creation_user = (int) $t->spec_creation_user;
		}
	}

	static function get() {
		$q = new DynamicQuery();
		return $q->useTable('spec');
	}

	static function canEditAll() {
		return has_permission('edit-all-spec');
	}

	static function insert($property_ID, $item_ID, $value) {
		$myself = get_user()->getUserID();

		insert_row('spec',
			new DBCol('spec_value',         $value,       'snull'),
			new DBCol('spec_creation_user', $myself,      'd'),
			new DBCol('spec_lastedit_user', $myself,      'd'),
			new DBCol('spec_creation_date', 'NOW()',      '-'),
			new DBCol('spec_lastedit_date', 'NOW()',      '-'),
			new DBCol('property_ID',        $property_ID, 'd'),
			new DBCol('item_ID',            $item_ID,     'd')
		);

		return last_inserted_ID();
	}

	static function update($property_ID, $item_ID, $value) {
		$myself = get_user()->getUserID();

		query_update('spec', [
				new DBCol('spec_value',         $value,  'snull'),
				new DBCol('spec_lastedit_user', $myself, 'd'),
				new DBCol('spec_lastedit_date', 'NOW()', '-')
			],
			// Where
			$sql = sprintf(
				'property_ID = %d AND item_ID = %d',
				$property_ID,
				$item_ID
			)
		);
	}
}
