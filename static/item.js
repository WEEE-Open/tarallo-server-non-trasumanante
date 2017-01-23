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
function Item(uid, fetched) {
	this.uid            = uid;
	this.fetched        = fetched || false;
	this.parent         = null;
	this.contains       = [];
	this.specifications = [];

	// Cache
	items[ uid ] = this;
}

// Get or insert in cache
Item.get = function (uid) {
	var item = items[uid];
	if( item ) {
		return item;
	}
	return new Item(uid);
};

/**
 * @param Item Item that is contained in this.
 */
Item.prototype.addContained = function (item) {
	this.contains.push( item );
	item.setParent( this );
	return this;
}

/**
 * @return string Item UID
 */
Item.prototype.getUID = function () {
	return this.uid;
};

/**
 * @param Spec
 * @return Item this
 */
Item.prototype.addSpec = function (spec) {
	spec.setItem(this);
	this.specifications.push( spec );
	return this;
};

/**
 * @return int Nesting level
 */
Item.prototype.countLevel = function () {
	return this.parent ? this.parent.countLevel() + 1 : 0;
};

/**
 * @param Item parent Item parent
 * @return Item this
 */
Item.prototype.setParent = function (parent) {
	this.parent = parent;
	return this;
};

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

Item.prototype.fetch = function (success, failure) {
	Item.fetchByUID( this.uid, success, failure );
};

/**
 * Retrieve Item from its UID, full with all the specifications and properties.
 *
 * @param string uid Item UID
 * @param callback success AJAX success callback (called when all the properties are fetched)
 * @param callback failure AJAX failure callback
 */
Item.fetchByUID = function (uid, success, failure) {
	// Try from cache
	var item = items[uid];
	if( item && item.fetched ) {
		success && success(item);
		return;
	}

	$asd.ajax(ITEM_API, {uid: uid}, 'GET', function (json) {
		if( ! json ) {
			console.log('Not found item ' + uid);
			failure && failure(uid);
			return;
		}

		var item = Item.get( json.item_uid );

		if(json.parent) {
			item.setParent( Item.get(json.parent) );
		}

		for(var i=0; i<json.contains.length; i++) {
			item.addContained( Item.get( json.contains[i] ) );
		}

		item.fetched = true;

		var found = 0;
		for(var i=0; i<json.specifications.length; i++) {
			var row = json.specifications[i];

			Property.fetchByUID( row.property_uid, function (property) {
				var spec = new Spec(property, row.spec_value);

				item.addSpec( spec );

				found++;
				if( found >= json.specifications.length ) {
					success && success( item );
				}
			} );
		}

		if( ! json.specifications.length ) {
			success && success( item );
		}
	} );
};

/**
 * Do something for each item contained.
 *
 */
Item.prototype.eachContained = function (success, failure) {
	if( ! this.fetched ) {
		var item = this;
		this.fetch( function () {
			item.eachContained(success, failure);
		}, failure);
		return;
	}

	for(var i=0; i<this.contains.length; i++) {
		var item = this.contains[i];
		item.fetch(function () {
			success && success( item );
		}, failure);
	}
}

/**
 * @return [] Array of Spec
 */
Item.prototype.eachSpec = function (success, failure) {
	if( ! this.fetched ) {
		var item = this;
		this.fetch( function () {
			item.eachSpec(success, failure);
		}, failure);
		return;
	}

	for(var i=0; i<this.specifications.length; i++) {
		success && success( this.specifications[i] );
	}
};

/**
 * Retrieve a Property from its UID. It will reach the latest parent.
 *
 * @param string uid Property UID
 * @param callback success AJAX success callback
 * @param callback failure AJAX failure callback
 * @param Property parent Optional Property that is actually fetching a child
 */
Property.fetchByUID = function (uid, success, failure, parent) {
	// Try from cache
	var property = properties[ uid ];
	if( property && property.fetched ) {
		success && success(property);
		return;
	}

	$asd.ajax(PROPERTY_API, {uid: uid}, 'GET', function (json) {
		if( ! json ) {
			console.log('Not found property ' + uid);
			failure && failure(uid);
			return;
		}

		property = new Property(json.property_uid, json.property_name, true);

		parent && parent.addParent(property);

		if( json.parent.length ) {
			for(var i=0; i<json.parent.length; i++) {
				Property.fetchByUID( json.parent[i], success, failure, property );
			}
		} else {
			// Found the root property
			success && success(parent || property);
		}
	} );
};

function Property(uid, name, fetched) {
	this.uid  = uid;
	this.name = name;
	this.fetched = fetched || false;
	this.parent = [];
	this.children  = [];

	if( ! properties[uid] ) {
		properties[uid] = this;	
	}
}

Property.prototype.fetch = function(success, failure, parent) {
	Property.fetchByUID( this.uid, success, failure, parent);
};

// Get or insert in cache
Property.get = function (uid, name) {
	var property = properties[uid];
	if( property ) {
		return property;
	}
	return new Property(uid, name);
};

Property.prototype.getUID = function () {
	return this.uid;
};

Property.prototype.addParent = function(property) {
	this.parent.push( property );
	property.children.push( this );
	return this;
};

function Spec(property, value) {
	this.property = property;
	this.value    = value;
	this.item     = undefined;
}

Spec.prototype.getValue = function () {
	return this.value;
};

Spec.prototype.setValue = function (value) {
	this.value = value;
	return this;
};

Spec.prototype.getProperty = function () {
	return this.property;
};

Spec.prototype.setItem = function (item) {
	this.item = item;
	return this;
};

Spec.prototype.getItem = function () {
	return this.item;
};

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
