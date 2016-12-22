<?php
# T.A.R.A.L.L.O. - Item with Location
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

class_exists('Item');
class_exists('Location');

class ItemLocation {
	use ItemTrait;
	use LocationTrait;

	function __construct() {
		Item::normalize($this);
		Location::normalize($this);
	}

	static function get() {
		$q = new DynamicQuery();
		$q->useTable([ 'location', 'item' ]);
		return $q->appendCondition('location.location_ID = item.item_ID');
	}
}
