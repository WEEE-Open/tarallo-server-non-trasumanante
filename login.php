<?php
# T.A.R.A.L.L.O. - Login page
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

require 'load.php';

login();

new Header('login');

?>

	<?php if( is_logged() ): ?>
		<p><?php _e("Tarallizzato.") ?></p>
	<?php else: ?>
		<form method="post">
			<p>
				<input type="text" name="user_uid" />
			</p>
			<p>
				<input type="password" name="user_password" />
			</p>
			<p>
				<button type="submit"><?php _e("Login") ?></button>
			</p>
		</form>
	<?php endif ?>

<?php
new Footer();
