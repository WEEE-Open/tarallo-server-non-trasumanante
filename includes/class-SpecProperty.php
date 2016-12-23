<?php
# T.A.R.A.L.L.O. - Join between spec and property
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

class SpecProperty {
	use SpecTrait;
	use PropertyTrait;

	function __construct() {
		Spec::normalize($this);
		Property::normalize($this);
	}

	static function get() {
		return Spec::get()->useTable('property')->appendCondition(
			'spec.property_ID = property.property_ID'
		);
	}
}
