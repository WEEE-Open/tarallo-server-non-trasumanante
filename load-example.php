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

// if( '127.0.0.1' === $_SERVER['REMOTE_ADDR'] ) {
// 	define('DEBUG', 1);
// 	define('SHOW_EVERY_SQL', 1);
// }

$database = '';
$username = '';
$password = '';
$location = 'localhost';

// Database table prefix, if any.
$prefix = 'asd_';

// Absolute pathname of this directory
define('ABSPATH', __DIR__ );

// Absolute pathname of the request for this directory
define('ROOT', '');

define('DB_TIMEZONE', 'Europe/Rome');

// Where is the framework?
require '/usr/share/boz-php-another-php-framework/load.php';
