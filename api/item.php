<?php
# T.A.R.A.L.L.O. - API
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

$item = Item::getByUID( @ $_GET['uid'] )
	->selectField( [
		'item_ID',
		'item_uid'
	] )
	->getRow('Item');

if( $item ) {
	$item->specifications = $item->getItemSpecifications()->selectField( [
		'property_uid',
		'spec_value'
	] )->getResults('SpecProperty');

	// We are a bit sure that we can't have more than one parent
	$parent = $item->getContainerItem()->selectField( [
		'item_uid'
	] )->getRow('Item');

	$item->parent = null;
	if( $parent ) {
		$item->parent = $parent->getItemUID();
	}

	$contains = $item->getContainedItems()->selectField( [
		'item_uid'
	] )->getResults('Item');

	$item->contains = [];
	foreach($contains as $contain) {
		$item->contains[] = $contain->getItemUID();
	}

	// Now unused
	unset( $item->item_ID );
}

http_json_header();
echo json_encode($item, JSON_PRETTY_PRINT);
