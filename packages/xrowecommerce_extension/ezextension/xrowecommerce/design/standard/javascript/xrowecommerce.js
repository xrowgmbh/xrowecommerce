YUI( YUI3_config ).use("node", function(Y) {
	Y.on("domready", function() {
		if (Y.Node.get("#shipping-checkbox")) {
			Y.on("change", function(e) {
				updateShipping();
			}, "#country");
			Y.on("change", function(e) {
				updateShipping();
			}, "#s_country");
			Y.on("click", function(e) {
				changeShipping();
	            updateShipping();
			}, "#shipping-checkbox");
		}
		if (Y.Node.get("#AutomaticDeliveryTooltip") && Y.Node.get("#show_auto_tip") ) {
			AutomaticDeliverTooltip("#show_auto_tip", "#AutomaticDeliveryTooltip");
		}
	});
});

YAHOO.namespace("example.calendar");
function ShowHide(id)
{
    	    var ComponentName = id + '-container';
	        if ( YAHOO.util.Dom.hasClass( ComponentName, 'hide') )
	        {
	            YAHOO.util.Dom.removeClass(ComponentName, 'hide');
	            YAHOO.util.Dom.addClass(ComponentName, 'show');
	        }
	        else
	        {
	            YAHOO.util.Dom.removeClass(ComponentName, 'show');
	            YAHOO.util.Dom.addClass(ComponentName, 'hide');
	        }
}
function handleSelect(type,args,obj) {
			var dates = args[0]; 
			var date = dates[0];
			var year = date[0], month = date[1], day = date[2];

			var txtDate1 = document.getElementById( obj.id + "-date");
			txtDate1.value = month + "/" + day + "/" + year;
			ShowHide( obj.id );
}


function AutomaticDeliverTooltip( node, box ) {
    	YUI( YUI3_config ).use( 'node', "overlay", function(Y) { 
    	var WidgetPositionExt = Y.WidgetPositionExt;
    	var overlay = new Y.Overlay({ 
    		      contentBox: box, 
    		      centered: true,
    		      width: "400px" 
    		  }); 
    	overlay.set("align", {node: node, points:[WidgetPositionExt.TC, WidgetPositionExt.BC]});
    	overlay.hide(); 
    	overlay.render(); 
        Y.on("mouseover", Y.bind(overlay.show, overlay), node); 
        Y.on("mouseout", Y.bind(overlay.hide, overlay), node); 
    	});
}


function ezjson(uri, callback, args) {
	// Create business logic in a YUI sandbox using the 'io' and 'json' modules
	YUI( YUI3_config ).use("node", "io", "dump", "json-parse", function(Y) {

		function onFailure(transactionid, response) {
			Y.log("Async call failed!");
		}
		function onComplete(transactionid, response, callback, args) {
			// transactionid : The transaction's ID.
			// response: The response object.
			// arguments: Object containing an array { complete: ['foo', 'bar']
			// }.

			Y.log("RAW JSON DATA: " + response.responseText);

			// Process the JSON data returned from the server
			try {
				var data = null;
				data = Y.JSON.parse(response.responseText);
				Y.log("PARSED DATA: " + Y.Lang.dump(data));
			} catch (e) {
				Y.log("JSON Parse failed!");
				return;
			}
			callback(data, args);
		}

		Y.on('io:failure', onFailure, this);
		Y.on('io:complete', onComplete, this, callback, args);

		// Make the call to the server for JSON data
		transaction = Y.io("/xrowecommerce/json/" + uri, callback);

	});
}
function updateShipping() {
	if ( document.register.shippingtype == null )
	{
		return false;
	}
	if ( document.register.shipping.checked == false)
	{
		status = document.register.s_country.value;
	}
	
	
	YUI( YUI3_config )
			.use(
					"node",
					"dump",
					function(Y) {
						if (Y.Node.get("#shipping-checkbox").get("checked")) {
							var country = Y.Node.get('#country').get('options')
									.item(
											Y.Node.get('#country').get(
													'selectedIndex')).get(
											'value');
						} else {
							var country = Y.Node.get('#s_country').get(
									'options').item(
									Y.Node.get('#s_country').get(
											'selectedIndex')).get('value');
						}

						var doit = function(data) {
							var oldname = Y.Node.get('#shippingtype').get(
									'options').item(
									Y.Node.get('#shippingtype').get(
											'selectedIndex')).get('text');
							var old = Y.Node.get('#shippingtype')
									.get('options').item(
											Y.Node.get('#shippingtype').get(
													'selectedIndex')).get(
											'value');
							var nodes = Y.all('#shippingtype option');
							var deleteNodes = function(n, a, b) {
								n.get('parentNode').removeChild(n)
							};
							nodes.each(deleteNodes);
							for (i = 0; i < data.length; i++) {
								if (data[i][2] == false) {
									var node = Y.Node.create('<option value="'
											+ data[i][1] + '" disabled>'
											+ data[i][0] + '</option>');
								} else {
									if (old == data[i][1]) {
										var selected = i;
									}
									var node = Y.Node.create('<option value="'
											+ data[i][1] + '">' + data[i][0]
											+ '</option>');
								}

								Y.Node.get('#shippingtype').appendChild(node);
							}
							if (typeof (selected) != "undefined") {
								Y.Node.get('#shippingtype').set(
										'selectedIndex', selected)
							} else {
								var replace = new Array();
								replace["%old%"] = oldname;
								replace["%new%"] = Y.Node.get('#shippingtype')
										.get('options').item(
												Y.Node.get('#shippingtype')
														.get('selectedIndex'))
										.get('text');
								ez18nAlert(
										"The shipping method '%old%' is not available for your country of destination and was changed to '%new%'.",
										replace);
							}
							Y.log("INFO2: "
									+ Y.Lang.dump(Y.Node.get('#shippingtype')
											.get('options')));

						}

						ezjson('getshipping?country=' + country, doit);

					});
}

function ez18nAlert(text, args) {
	var doit = function(data, args) {
		for ( var x in args) {
			data = data.replace(x, args[x]);
		}

		alert(data);
	}
	ezjson('translate?text=' + text, doit, args);
}
function changeShipping() {
	YUI( YUI3_config )
			.use(
					'node',
					function(Y) {
						if (Y.Node.get("#shipping-checkbox").get("checked")) {
							Y.Node.get("#shippinginfo").setStyle('display', 'none');
						} else {
							Y.Node.get("#shippinginfo").setStyle('display',
									'block');
							if (document.register.company_name) {
								document.register.s_company_name.value = document.register.company_name.value;
							}
							if (document.register.company_additional) {
								document.register.s_company_additional.value = document.register.company_additional.value;
							}
							if (document.register.mi) {
								document.register.s_mi.value = document.register.mi.value;
							}
							if (document.register.state) {
								document.register.s_state.value = document.register.state.value;
							}
							document.register.s_first_name.value = document.register.first_name.value;
							document.register.s_last_name.value = document.register.last_name.value;

							document.register.s_zip.value = document.register.zip.value;
							document.register.s_phone.value = document.register.phone.value;
							if (Y.Node.get("#fax") && Y.Node.get("#s_fax")) {
								document.register.s_fax.value = document.register.fax.value;
							}
							document.register.s_email.value = document.register.email.value;
							document.register.s_address1.value = document.register.address1.value;
							if (Y.Node.get("#address2")
									&& Y.Node.get("#s_address2")) {
								document.register.s_address2.value = document.register.address2.value;
							}
							document.register.s_city.value = document.register.city.value;
							document.register.s_country.selectedIndex = document.register.country.selectedIndex;

						}
					});
}
function toggleCOS()
{        
    var container = document.getElementById( 'cos-content' );
    if ( container )
    {
        if ( YAHOO.util.Dom.getStyle( container, 'display') == 'block' )
        {
        	YAHOO.util.Dom.setStyle( container, 'display', 'none');
        }
        else
        {
        	YAHOO.util.Dom.setStyle( container, 'display', 'block');
        }
    }
}
// deprecated use generatePopup
function enlargeImage( imsrc, ww, wh, alttext )
{

    alttext = alttext.replace(/\"/ig, '&quot;');
    w1=window.open('','ImageEnlarged','width='+ww+',height='+wh+',status=no,toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=no,dependent=yes,innerHeight='+wh+',innerWidth='+ww+'');
    w1.document.open();
    w1.document.write("<html><head><\/head>");
    w1.document.write("<body style=\"margin: 0px 0px 0px 0px;padding: 0px 0px 0px 0px;\">");
    w1.document.write("<a href=\"javascript:window.close()\" title=\"" + alttext + "\" style=\"margin: 0px 0px 0px 0px;border: none;padding: 0px 0px 0px 0px;\"><img name=\"theimg\" style=\"margin: 0px 0px 0px 0px;border: none;padding: 0px 0px 0px 0px;\" src=\"" + imsrc + "\" alt=\"" + alttext + "\" /></a>");
    w1.document.write("<\/body><\/html>");
    w1.focus();
};
/**
 * @param node DomNode to receive click event
 * @param image Path to the full image
 * @param imagetext Alternate footer text
 * @param doubleclick Double or single click
 */
function generatePopup(node, image, imagetext, doubleclick) {
	YUI(YUI3_config).use(
			"node",
			"overlay",
			"imageloader",
			function(Y) {
				var xy = Y.one(node).getXY();
				imageNode = Y.Node.create('<img />');
				imageNode.set('id', Y.guid());
				var overlay = new Y.Overlay( {
					headerContent : "Popup: Click to close.",
					bodyContent : imageNode,
					width : 'auto',
					height : 'auto',
					centered : node,
					visible : false,
					xy : [ xy[0] + 10, xy[1] + 35 ]
				});
				if (imagetext) {
					overlay.set('footerContent', imagetext)
				}
				var myFirstGroup = new Y.ImgLoadGroup( {
					timeLimit : 2
				});
				myFirstGroup.registerImage( {
					domId : imageNode.get('id'),
					srcUrl : image
				});
				overlay.render();

				Y.on("click", Y.bind(overlay.hide, overlay), overlay
						.get("contentBox"));
				if (doubleclick) {
					Y.on("dblclick", function(e) {
						overlay.show();
					}, node);
				} else {
					Y.on("click", function(e) {
						overlay.show();
					}, node);
				}
			});
}
function change()
{
if (document.getElementById( 'shipping-checkbox' ).checked)
    {
    document.getElementById( 'shippinginfo' ).style.display = 'none';
    }
    else
    {
            document.getElementById( 'shippinginfo' ).style.display = 'block';
    }
}