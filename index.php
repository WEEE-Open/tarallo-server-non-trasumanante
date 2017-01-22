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
	var INDEX     = '<?php echo ROOT ?>#';
	var ITEM_API  = '<?php echo ITEM_API ?>';
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
		$a.href = INDEX + this.getUID();
		$a.appendChild( $asd.text(
			this.getUID()
		) );

		// URL click
		$a.onclick = function () {
			Item.fetch(item.getUID(), function (item) {
				MAX_RECURSION += MAX_RECURSION_STEP;

				if( CLEAR_ON_CLICK ) {
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

	Spec.prototype.print = function () {
		// Property uid label
		$p = $asd.p( repeat( this.getItem().countLevel(), '-+') );

		this.getProperty().print( $p, this );
		this.printSave(  $p );

		$item.appendChild( $p );
	};

	Property.prototype.print = function ($p, spec) {
		this.printLabel( $p, spec );
		this.printValue( $p, spec );
	};

	Property.prototype.printLabel = function($p, spec) {
		$p.appendChild( $asd.text( "[" + this.getUID() + "] = ") );
	}

	Property.prototype.printValue = function ($p, spec) {
		spec.setInput( $asd.input('text', spec.getValue() ) );
		$p.appendChild( spec.getInput() );
	};

	Spec.prototype.$input = null;
	Spec.prototype.getInput = function () {
		return this.$input;
	};
	Spec.prototype.setInput = function ($input) {
		this.$input = $input;
	};
	Spec.prototype.getInputValue = function() {
		return this.$input.value;
	}

	Spec.prototype.printSave = function ($p) {
		var $b = $asd.input('button', "Save");
		$b.disabled = 'disabled';

		var spec = this;
		$b.onclick = function (e) {
			spec.setValue( spec.getInputValue() ).save( function () {
				$b.disabled = 'disabled';
			} );
		};

		// Save button abilitation on value click
		this.getInput().onchange = function () {
			$b.disabled = false;
		};

		$p.appendChild( $b );
	};

	Item.prototype.printSpec = function () {
		var specs = this.getSpecifications();
		for(var i=0; i<specs.length; i++) {
			specs[i].print();
		}
	}
	</script>
<?php
new Footer();
