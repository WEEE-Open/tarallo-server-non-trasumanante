<?php
# T.A.R.A.L.L.O. - Home
# Copyright (C) 2016, 2017 Valerio Bozzolan
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
enqueue_js('item');

new Header('home');

?>
	<input type="text" name="uid" placeholder="poli" id="item-uid" />
	<button type="button" id="item-search"><?php _e("Get") ?></button>

	<pre id="item"></pre>

	<script>
	var $s        = $asd.id('item-search');
	var $item_uid = $asd.id('item-uid');
	var $item     = $asd.id('item');

	// Can be zero
	var MAX_RECURSION = 1;

	// As default, increment at the same, or at least 1!
	var MAX_RECURSION_STEP = MAX_RECURSION + ( MAX_RECURSION || 1 );

	var CLEAR_ON_CLICK = false;

	// Document ready
	( function () {
		var item_uid = get_anchor();
		item_uid && Item.fetch(item_uid, function (item) {
			if( item ) {
				$item_uid.value = item.getUID();
				item.print();
			}
		} );
	} )();

	function clear() {
		$item.textContent = '';
	}

	// Search button
	$s.onclick = function (e) {
		Item.fetch( $item_uid.value, function (item) {
			if( item ) {
				set_anchor( item.getUID() );
				clear();
				item.clear().print();
			}
		} );
		return false;
	};

	Item.prototype.yetPrinted = false;
	Item.prototype.print = function () {
		var item = this;

		var $a = $asd.el('a');
		$a.href = '#';
		$a.appendChild( $asd.text(
			this.getUID()
		) );

		// URL click
		$a.onclick = function () {
			Item.fetch(item.getUID(), function (item) {
				MAX_RECURSION += MAX_RECURSION_STEP;

				if( CLEAR_ON_CLICK ) {
					console.log( item );
					set_anchor( item.getUID() );
					$item_uid.value = item.getUID();
					clear();
					item.clear();
				}
				item.print();
			} );
			return false;
		};

		var $p = $asd.p( repeat( this.countLevel() , '-') );
		$p.appendChild( $a );

		// Don't print myself, print my children!
		if( this.yetPrinted ) {
			console.log("Yet printed");
		} else {
			$item.appendChild( $p );
			this.printSpec();
		}
		this.yetPrinted = true;

		// Print childs
		var parent = this;
		if( this.countLevel() < MAX_RECURSION ) {
			// Print childs recursively
			for(var i=0; i<this.contains.length; i++) {
				Item.fetch(this.contains[i].item_uid, function (item) {
					item.setParent( parent );
					item.print();
				} );
			}
		} else {
			console.log("Too many levels automatically fetched");
		}
	}

	Item.prototype.printSpec = function () {
		var item = this;

		var item_uid     = this.getUID();
		var level        = this.countLevel();

		var specs = item.getSpecifications()
		for(var i=0; i<specs.length; i++) {
			var spec = specs[i];

			var property_uid = spec.getProperty().getUID();
			var spec_value   = spec.getValue();

			// Property uid label
			$p = $asd.p( repeat( level, '-+') );
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
				spec.setValue($v.value).save( function () {
					$b.disabled = 'disabled';
				} );
			};

			// Save button abilitation on value click
			$v.onclick = function () {
				$b.disabled = false;
			};

			$p.appendChild( $b );

			$item.appendChild( $p );
		}
	}
	</script>
<?php
new Footer();
