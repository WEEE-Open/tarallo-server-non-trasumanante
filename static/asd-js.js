/* T.A.R.A.L.L.O. - asd-js
 * Copyright (C) 2016 Ludovico Pavesi, Valerio Bozzolan
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

$asd = {};

$asd.id    = function (id) { return document.getElementById(id); };
$asd.el    = function (el) { return document.createElement(el); };
$asd.class = function (c)  { return document.getElementByClass(c); };
$asd.text  = function (s)  { return document.createTextNode(s); };

$asd.a = function (href, text) {
	var $a = $asd.el('a');
	if(text) {
		$a.appendChild( $asd.text(text) );
		$a.href = href;
	}	
	return $a;
};

$asd.p = function (text) {
	$p = $asd.el('p');
	text && $p.appendChild( $asd.text(text) );
	return $p;
};

$asd.input = function (type, value) {
	var $i = $asd.el('input');
	$i.type = type;
	if( value) {
		$i.value = value;
	}
	return $i;
};

$asd.ajax = function (url, data, method, callback, error) {
	var data   = data   || {};
	var method = method || 'GET';

	var q = '';
	for(key in data) {
		if(q) {
			q += '&';
		}
		q = q + key + '=' + encodeURIComponent( data[key] );
	}

	if( method === 'GET' ) {
		// in GET, data is part of the query string
		url += '?' + q;
		data = undefined;
	} else if( method === 'POST' ) {
		// in POST, data is encoded differently
		data = q;
	}

	var xmlhttp  = new XMLHttpRequest();
	xmlhttp.open(method, url, true);
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == XMLHttpRequest.DONE) {
			if(xmlhttp.status == 200) {
				callback && callback( JSON.parse(xmlhttp.responseText) );
			} else {
				console.log('Error: ' + xmlhttp.statusText );
				error && error(xmlhttp);
			}
		}
	}

	if( method === 'POST' ) {
		// Required in POST to encode data properly
		xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
	}

	xmlhttp.send(data);
};

function set_anchor(anchor) {
	window.location.hash = '#' + anchor;
}

function get_anchor() {
	return window.location.hash.substr(1);
}
