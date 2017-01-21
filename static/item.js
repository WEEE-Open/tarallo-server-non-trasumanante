var ITEM_API = '/api/item.php';

// Cache
var items = {};
var properties = {};

/**
 * Everything is an item
 * /api/item.php?uid=poli
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

Item.prototype.getUID = function () {
	return this.uid;
}

Item.prototype.countLevel = function () {
	if( ! this.parent ) {
		return 0;
	}
	return this.parent.countLevel() + 1;
}

Item.prototype.setParent = function (parent) {
	this.parent = parent;
	return this;
}

Item.prototype.clear = function () {
	items = {};
	this.parent = undefined;
	return this;
};

Item.prototype.getSpecifications = function () {
	return this.specifications;
}

/**
 * Retrieve an item
 *
 * @param string item_uid
 * @param [] args {hash: bool, input: bool, succes: callback}
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
		for(var i=0; i<json.specifications.length; i++) {
			var row = json.specifications[i];
			var property = Property.get(row.property_uid, row.property_name);
			specifications.push( new Spec(property, row.spec_value) );
		}

		success && success( new Item(json.item_uid, specifications, json.contains) );
	} );
};

function Property(uid, name) {
	this.uid  = uid;
	this.name = name;

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
