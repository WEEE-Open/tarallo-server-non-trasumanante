<?php
# T.A.R.A.L.L.O. - Boz-PHP configuration
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

defined('ABSPATH') || exit;

define('INCLUDES', 'includes');
define('STITIC'  , 'static');

define('SESSIONUSER_CLASS', 'User');

// Autoload classes
spl_autoload_register( function($c) {
	$path = ABSPATH . __ . INCLUDES . __ . "class-$c.php";
	if( is_file( $path ) ) {
		require $path;
	}
} );

register_js('asd-js', ROOT . _ . STITIC . _ . 'asd-js.js');
register_js('item',   ROOT . _ . STITIC . _ . 'item.js');

add_menu_entries( [
	new MenuEntry('login', ROOT . '/login.php', _("Login") ),
	new MenuEntry('home',  ROOT               , _("Home") )
] );


register_permissions('admin', [
	'edit-all-item',
	'edit-all-spec'
] );
