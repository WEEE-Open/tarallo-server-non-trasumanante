<?php
# T.A.R.A.L.L.O. - API
# Copyright (C) 2016 Valerio Bozzolan
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

$property = Property::getByUID( @ $_GET['uid'] )
	->selectField( [
		'property_ID',
		'property_uid',
		'property_name'
	] )
	->getRow('Property');

if( $property ) {
	$property->suggested = $property
		->getPropertyChildren()
		->selectField( [
			'property_uid',
			'property_name'
		] )
		->getResults('Property');

	unset( $property->property_ID );
}

http_json_header();
echo json_encode($property, JSON_PRETTY_PRINT);
