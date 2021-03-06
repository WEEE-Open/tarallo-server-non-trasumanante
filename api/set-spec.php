<?php
# T.A.R.A.L.L.O. - API to save specifications
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

require '../load.php';

// Can do this? (Unknown)
$can = null;

// Message
$msg = 'rtfm';

// Done, or not
$done = false;

/**
 * Insert or update the specification row (`spec`).
 *
 * @param string $item item_uid
 * @param string $property property_uid
 */
$api = function($item, $property, $value) use (& $msg, & $can) {

	// It exists yet?
	$existing_spec = SpecPropertyItem::getByUID( $item, $property )->getRow('SpecPropertyItem');

	if( $existing_spec ) {
		// Update existing specification

		// Has permission to edit this specific specification?
		$can = $existing_spec->isSpecEditable();
		if( ! $can ) {
			$msg = 'cannot-update';
			return false;
		}

		// Update the already existing specification value
		$existing_spec->updateSpecValue( $value );
	} else {
		// Insert as a new specification

		// Has permission to insert a new specification?
		$can = Spec::canInsert();
		if( ! $can ) {
			$msg = 'cannot-insert';
			return false;
		}

		// Item exists?
		$item_ID = Item::getByUID( $item )->getValue('item_ID');
		if( ! $item_ID ) {
			$msg = 'missing-item';
			return false;
		}

		// Property exists?
		$property_ID = Property::getByUID( $property )->getValue('property_ID');
		if( ! $property_ID ) {
			$msg = 'missing-property';
			return false;
		}

		// Insert as a new spec
		Spec::insert($item_ID, $property_ID, $value);
	}

	$msg = null;

	// DONE!
	return true;
};

if( isset( $_POST['item'], $_POST['property'] ) ) {
	$value = null;
	if( ! empty( $_POST['value'] ) ) {
		$value = $_POST['value'];
	}

	$done = $api( $_POST['item'], $_POST['property'], $value );
}

http_json_header();

echo json_encode( [
	'can'   => $can,
	'done'  => $done,
	'msg'   => $msg
], JSON_PRETTY_PRINT);
