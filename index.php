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
		item_uid && Item.fetchByUID(item_uid, function (item) {
			$item_uid.value = item.getUID();
			item.print();
		} );
	} )();

	function clear() {
		$item.textContent = '';
	}

	// Search button
	$s.onclick = function (e) {
		Item.fetchByUID( $item_uid.value, function (item) {
			set_anchor( item.getUID() );
			clear();
			item.clear().print();
		} );
		return false;
	};

	Item.prototype.yetPrinted = false;
	Item.prototype.print = function () {
		var $a = $asd.el('a');
		$a.href = INDEX + this.getUID();
		$a.appendChild( $asd.text(
			this.getUID()
		) );

		// URL click
		var item = this;
		$a.onclick = function () {
			Item.fetchByUID(item.getUID(), function (item) {
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
		if( this.countLevel() < MAX_RECURSION ) {
			// Print childs recursively
			this.eachContained( function (item) {
				item.print();
			} );
		} else {
			console.log("Too many levels automatically fetched");
		}
	}

	Spec.prototype.print = function () {
		// Property uid label
		$p = $asd.p( repeat( this.getItem().countLevel(), '-+') );

		this.getProperty().print( $p, this );

		$item.appendChild( $p );
	};

	Property.prototype.print = function ($p, spec) {
		var propertyModule = PropertyModules[ this.getUID() ];
		propertyModule && propertyModule(this);

		this.printLabel( $p, spec );
		this.printValue( $p, spec );
		this.printSave( $p, spec );
	};

	Property.prototype.printLabel = function($p, spec) {
		$p.appendChild( $asd.text( "[" + this.getUID() + "] = ") );
	}

	Property.prototype.printValue = function ($p, spec) {
		this.setInputValue( spec.getValue() );
		$p.appendChild( this.getInput() );
	};

	// Spec.prototype.$input = null;
	Property.prototype.getInputType = function () {
		return 'text';
	};
	Property.prototype.getInput = function () {
		if( ! this.$input ) {
			this.$input = $asd.input( this.getInputType() );
		}
		return this.$input;
	};
	Property.prototype.setInput = function ($input) {
		this.$input = $input;
		return this;
	};
	Property.prototype.getInputValue = function () {
		return this.getInput().value;
	};
	Property.prototype.setInputValue = function (value) {
		this.getInput().value = value;
		return this;
	};
	Property.prototype.printSave = function ($p, spec) {
		var $b = $asd.input('button', "<?php _esc_attr( _("Salva") ) ?>");
		$b.disabled = 'disabled';

		var property = this;
		$b.onclick = function (e) {
			property.setInputValue( property.getInputValue() );
			spec.setValue( property.getInputValue() ).save( function () {
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
		var specs = this.eachSpec( function(spec) {
			spec.print();
		} );
	}

	/**
	 * Enhances some types of properties
	 */
	var PropertyModules = {};
	PropertyModules.note = function (property) {
		property.setInput( $asd.el('textarea') );
	};
	PropertyModules.capacity = function (property) {
		property.setInputType('number');
	};
	</script>
<?php
new Footer();
