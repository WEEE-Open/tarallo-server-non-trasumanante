<?php
# T.A.R.A.L.L.O. - Reference
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

class_exists('Reference');
class_exists('Property');

class ReferenceProperty {
	use ReferenceTrait;
	use PropertyTrait;

	function __construct() {
		Reference::normalize($this);
		Property::normalize($this);
	}

	static function get() {
		return Reference::get()->useTable('property')->appendCondition(
			'reference.property_ID = property.property_ID'
		);
	}
}
