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

	<pre id="item"></pre>

	<script>
	var $s    = $asd.id('item-search');
	var $uid  = $asd.id('item-uid');
	var $item = $asd.id('item');

	function taralloItem(item, level) {
		var $a = $asd.el('a');
		$a.href = '#';
		$a.appendChild( $asd.text(
			item.item_uid
		) );
		$a.onclick = function () {
			api(item.item_uid, {hash: true} );
			return false;
		};

		var $p = $asd.p( repeat(level, '-') );
		$p.appendChild( $a );
		return $p;
	}

	function taralloProperty(spec, item, level) {
		var item_uid     = item.item_uid;
		var property_uid = spec.property_uid;
		var spec_value   = spec.spec_value;

		// Property uid label
		$p = $asd.p( repeat(level, '-+') );
		$p.appendChild( $asd.text(
			"[" + property_uid + "] = "
		) );

		// Spec value label
		var $v = $asd.input('text', spec_value);
		$p.appendChild( $v );

		// Save button
		var $b = $asd.input('button', "Save");
		$b.disabled = 'disabled';
		$b.onclick = function (e) {
			save_spec(item_uid, property_uid, $v.value, function (asd) {
				$b.disabled = 'disabled';
			} );
		};

		// Save button abilitation on value click
		$v.onclick = function () {
			$b.disabled = false;
		};

		$p.appendChild( $b );
		return $p;
	}

	/**
	 * Retrieve an item
	 *
	 * @param string uid
	 * @param [] args
	 * @param callback success
	 */
	function api(uid, args, success) {
		$asd.ajax('/api/item.php', {uid: uid}, 'GET', function (json) {
			if( ! json ) {
				$item.textContent = 'none';
				return;
			}

			if( args ) {
				if( args.hash ) {
					window.location.hash = uid;
				}
				if( args.input ) {
					$uid.value = uid;
				}
			}

			// Empty
			$item.textContent = '';

			$item.appendChild( taralloItem(json) );

			for(var i=0; i<json.properties.length; i++) {
				$item.appendChild( taralloProperty( json.properties[i], json ) );
			}

			// Contains
			for(var i=0; i<json.contains.length; i++) {
				$item.appendChild( taralloItem( json.contains[i], 2 ) );
				for(var j=0; j<json.contains[i].properties.length; j++) {
					$item.appendChild( taralloProperty( json.contains[i].properties[j], json, 2 ) );
				}
			}
		} );
	};

	function save_spec(item, property, value, success) {
		var data = {
			item: item,
			property: property,
			value: value
		};
		$asd.ajax('/api/set-spec.php', data, 'POST', function (json) {
			success && success(json);
		} );
	};

	function repeat(n, c) {
		n = n || 1;
		var s = '';
		for(var i=0; i<n; i++) {
			s += c;
		}
		return s + ' ';
	}

	$s.onclick = function (e) {
		api( $uid.value, {hash: true} );
		return false;
	};

	// ready
	( function () {
		var item = window.location.hash.substr(1);
		item && api(item, {input: true, hash: true} );
	} )();
	</script>
<?php
new Footer();
