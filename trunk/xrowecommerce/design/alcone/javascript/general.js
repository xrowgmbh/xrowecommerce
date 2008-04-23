/* http://www.dustindiaz.com/getelementsbyclass/ */
function getElementsByClass(searchClass,node,tag) {
	var classElements = new Array();
	if ( node == null )
		node = document;
	if ( tag == null )
		tag = '*';
	var els = node.getElementsByTagName(tag);
	var elsLen = els.length;
	var pattern = new RegExp("(^|\\s)"+searchClass+"(\\s|$)");
	for (i = 0, j = 0; i < elsLen; i++) {
		if ( pattern.test(els[i].className) ) {
			classElements[j] = els[i];
			j++;
		}
	}
	return classElements;
}

/* by Jeremy Keith */
function insertAfter(newElement, targetElement) {
	var parent = targetElement.parentNode;
	if (parent.lastChild == targetElement) {
		parent.appendChild(newElement);
	}
	else {
		parent.insertBefore(newElement, targetElement.nextSibling);
	}
}
function alterButtons( buttonclasses ) {
	if (!document.getElementsByTagName) {
		return false
	}
	for (var j=0; j < buttonclasses.length; j++ )
	{
	   var buttons = getElementsByClass(buttonclasses[j], null, 'input');
	   /* loop through all buttons and attach a child div */
	   for (var i=0; i < buttons.length; i++)
	   {
		  var span = document.createElement("span");
		  var parent = buttons[i].parentNode;
		  span.className = buttons[i].className;
		  buttons[i].className = null;
		  parent.insertBefore( span, buttons[i] );
		  span.appendChild(buttons[i]);
	   }
	}
}