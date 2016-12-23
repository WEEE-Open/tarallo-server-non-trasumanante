<?php
# T.A.R.A.L.L.O. - Home
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

enqueue_js('asd-js');

new Header('home');

?>
	<input type="text" name="uid" placeholder="poli" id="item-uid" />
	<button type="button" id="item-search"><?php _e("Get") ?></button>

	<div id="item"></div>

	<script>
	var $uid        = $asd.id('item-uid');
	var $item       = $asd.id('item');

	function getHTMLa(href, text) {
		var $a = $asd.el('a');
		$a.appendChild( document.createTextNode(text) );
		$a.href = href;
		return $a;
	}

	function getHTMLProperty(p, level) {
		var $p = $asd.el('p');
		$p.textContent = repeat(level, '-+') + "[" + p.property_uid + "] = " + p.spec_value;
		return $p;
	}

	function getHTMLItem(i, level) {
		var $p = $asd.el('p');
		var $a = getHTMLa('#', repeat(level, '-') + i.item_uid );
		$a.onclick = function () {
			$uid.value = i.item_uid;
			api(i.item_uid);
			return false;
		};
		$p.appendChild($a);
		return $p;
	}

	function api(uid) {
		$asd.ajax('/api/item.php', {uid: uid}, 'GET', function (json) {
			$item.textContent = '';

			$item.appendChild( getHTMLItem(json) );

			for(var i=0; i<json.properties.length; i++) {
				$item.appendChild( getHTMLProperty( json.properties[i] ) );
			}

			// Contains
			for(var i=0; i<json.contains.length; i++) {
				$item.appendChild( getHTMLItem( json.contains[i], 2 ) );
				for(var j=0; j<json.contains[i].properties.length; j++) {
					$item.appendChild( getHTMLProperty( json.contains[i].properties[j], 2 ) );
				}
			}
		} );
	};

	function repeat(n, c) {
		n = n || 1;
		var s = '';
		for(var i=0; i<n; i++) {
			s += c;
		}
		return s;
	}

	$asd.id('item-search').onclick = function (e) {
		api( $uid.value );
		return false;
	}
	</script>

<?php
new Footer();
