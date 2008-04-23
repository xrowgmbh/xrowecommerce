function callButtons()
{
    var buttonclasses = [ 'button', 'rarrow-orange', 'larrow-orange', "noarrow-orange", "rarrow-white", "larrow-white", "noarrow-white" ];
    alterButtons( buttonclasses );
}
YAHOO.util.Event.onDOMReady(callButtons);

function Menus() {
	if (document.all&&document.getElementById) {
		navRoot = document.getElementById("nav");
		for (i=0; i<navRoot.childNodes.length; i++) {
			node = navRoot.childNodes[i];
			if (node.nodeName=="LI") {
				node.onmouseover=function() {
					this.className+=" over";
				}
				node.onmouseout=function() {
					this.className=this.className.replace(" over", "");
				}
			}
		}
	}
    if (document.all&&document.getElementById) {
		navRoot = document.getElementById("nav2");
		for (i=0; i<navRoot.childNodes.length; i++) {
			node = navRoot.childNodes[i];
			if (node.nodeName=="LI") {
				node.onmouseover=function() {
					this.className+=" over";
				}
				node.onmouseout=function() {
					this.className=this.className.replace(" over", "");
				}
			}
		}
	}
}
YAHOO.util.Event.onDOMReady(Menus); 
