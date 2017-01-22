var ITEM_API     = '/api/item.php';
var PROPERTY_API = '/api/property.php';

// Cache
var items = {};
var properties = {};

/**
 * Everything is an item.
 *
 * @param string uid Item UID
 * @param [] specifications Array of Spec
 * @param [] contains Array of Item
 * @param Item parent Item parent
 */
function Item(uid, specifications, contains, parent) {
	this.uid            = uid;
	this.specifications = specifications;
	this.contains       = contains;
	this.parent         = parent;

	// Useful reference
	for(var i=0; i<this.specifications.length; i++) {
		this.specifications[i].setItem(this);
	}

	// Cache
	items[ uid ] = this;
}

/**
 * @return string Item UID
 */
Item.prototype.getUID = function () {
	return this.uid;
}

/**
 * @return int Nesting level
 */
Item.prototype.countLevel = function () {
	if( ! this.parent ) {
		return 0;
	}
	return this.parent.countLevel() + 1;
}

/**
 * @param Item parent Item parent
 * @return Item this
 */
Item.prototype.setParent = function (parent) {
	this.parent = parent;
	return this;
}

/**
 * Free this Item from his parent.
 *
 * @return Item this
 */
Item.prototype.clear = function () {
	items = {};
	this.parent = undefined;
	return this;
};

/**
 * @return [] Array of Spec
 */
Item.prototype.getSpecifications = function () {
	return this.specifications;
}

/**
 * Retrieve an Item from its UID.
 *
 * @param string uid Item UID
 * @param callback success AJAX success callback (called when all the properties are fetched)
 * @param callback failure AJAX failure callback
 */
Item.fetch = function (uid, success, failure) {
	// Try from cache
	var item = items[uid];
	if( item ) {
		success && success(item);
		return;
	}

	$asd.ajax(ITEM_API, {uid: uid}, 'GET', function (json) {
		if( ! json ) {
			console.log('Not found item ' + uid);
			failure && failure(uid);
			return;
		}

		var specifications = [];
		var found = 0;
		for(var i=0; i<json.specifications.length; i++) {
			var row = json.specifications[i];

			Property.fetch(row.property_uid, function (property) {
				specifications.push( new Spec(property, row.spec_value) );

				found++;
				if( found >= json.specifications.length ) {
					success && success( new Item(json.item_uid, specifications, json.contains) );
				}
			} );
		}
	} );
};

/**
 * Retrieve a Property from its UID.
 *
 * @param string uid Property UID
 * @param callback success AJAX success callback
 * @param callback failure AJAX failure callback
 * @param Property parent Optional Property that is actually fetching a child
 */
Property.fetch = function (uid, success, failure, parent) {
	// Try from cache
	var property = properties[uid];
	if( property ) {
		success && success(property);
		return;
	}

	$asd.ajax(PROPERTY_API, {uid: uid}, 'GET', function (json) {
		if( ! json ) {
			console.log('Not found property ' + uid);
			failure && failure(uid);
			return;
		}

		property = new Property(json.property_uid, json.property_name);

		parent && parent.addParent(property);

		// Fetch also all parent properties (N.B. NORMALLY IS ONE. I DON'T KNOW WHY IT SHOULD BE MORE THAN ONE.)
		for(var i=0; i<json.parent.length; i++) {
			Property.fetch(json.parent[i].property_uid, success, failure, property);
		}

		// This is the root property
		if( ! json.parent.length ) {
			success && success(property);
		}
	} );
};

function Property(uid, name) {
	this.uid  = uid;
	this.name = name;
	this.parent = [];
	this.child  = [];

	if( ! properties[uid] ) {
		properties[uid] = this;	
	}
}

// Get or insert in cache
Property.get = function (uid, name) {
	var property = properties[uid];
	if( ! property ) {
		return new Property(uid, name);
	}
	return property;
}

Property.prototype.getUID = function () {
	return this.uid;
}

Property.prototype.addParent = function(property) {
	this.parent.push( property );
	return this;
}

function Spec(property, value) {
	this.property = property;
	this.value    = value;
	this.item     = undefined;
}

Spec.prototype.getValue = function () {
	return this.value;
}

Spec.prototype.setValue = function (value) {
	this.value = value;
	return this;
}

Spec.prototype.getProperty = function () {
	return this.property;
}

Spec.prototype.setItem = function (item) {
	this.item = item;
	return this;
}

Spec.prototype.getItem = function () {
	return this.item;
}

/**
 * @param string item item_uid
 * @param string property property_uid
 * @param callback success
 */
Spec.prototype.save = function (success) {
	var data = {
		item:     this.getItem().getUID(),
		property: this.getProperty().getUID(),
		value:    this.getValue()
	};
	console.log(data);
	$asd.ajax('/api/set-spec.php', data, 'POST', function (json) {
		if( ! json.can ) {
			console.log("Can't save spec");
				if( json.can === null ) {
				console.log("Are you logged?");
			} else if( json.can === false ) {
				console.log("Permission denied");
			}
		}
		if( json.done ) {
			console.log("Spec saved");
			success && success(json);
		} else {
			console.log("Spec not saved: " + json.msg);
		}
	} );
};

/**
 * Repeat `n` times the `s` string
 */
function repeat(n, c) {
	n = n || 0;
	n++;
	var s = '';
	for(var i=0; i<n; i++) {
		s += c;
	}
	return s + ' ';
}
