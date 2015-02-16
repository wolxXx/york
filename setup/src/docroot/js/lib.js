/**
 * @var Class main Lib File
 */
var Lib = null;
var disableNotification = disableNotification;
window.addEvent('domready', function() {
	Lib = new LibClass();

	//give all scripts the hint, that the library is available!
	window.fireEvent('libloaded');
});

var LibClass = new Class({
	formatDate : function(date, format) {
		if (typeof format === 'undefined' || format === '') {
			format = '%d.%m.%Y %H:%I';
		}
		return new Date().parse(date).format(format);
	}
});

function replaceAll(find, replace, str) {
	return str.replace(new RegExp(find, 'g'), replace);
}

if (!String.prototype.trim) {
	String.prototype.trim = function() {
		return this.replace(/^\s+|\s+$/g, '');
	};
}


function loadJSFile(url){
	var xhrObj = new XMLHttpRequest();
	xhrObj.open('GET', url, false);
	xhrObj.send('');
	var se = document.createElement('script');
	se.type = "text/javascript";
	se.text = xhrObj.responseText;
	document.getElementsByTagName('head')[0].appendChild(se);
}


function _log(text, arguments) {
	arguments = arguments || [];
	if (typeof console !== "undefined" && console.log) {
		console.log(text, arguments);
	}

}

function debug(text){
	var target = $('debug');

	_log(text);

	if(null === target){
		return false;
	}

	target.set('html', text + '<br /> <br />' + target.get('html'));

	return true;
}

window.addEvent('domready', function(){
	var shineTarget = document.getElementById('tilteBar');
	if(shineTarget !== null){
		var shine = new Shine(shineTarget);
		window.addEventListener('mousemove', function(event) {
			shine.light.position.x = event.clientX;
			shine.light.position.y = event.clientY;
			shine.draw();
		}, false);
	}

	$$('label').each(function(elem){
		elem.addEvent('mouseenter', function(){
			$(elem.get('for')).addClass('labelHovered');
		});
		elem.addEvent('mouseleave', function(){
			$(elem.get('for')).removeClass('labelHovered');
		});
	});
});
