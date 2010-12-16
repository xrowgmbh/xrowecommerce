YUI().use( 'node', function(Y)
{
    Y.on( 'domready', function() 
    {
        if ( Y.Node.get( '#country' ) )
        {
            updateSubdivisions( Y.Node.get( '#country' ) );
        }
        if ( Y.Node.get( '#shipping-checkbox' ) )
        {
            if ( Y.Node.get( '#s_country' ) )
            {
                updateSubdivisions( Y.Node.get( '#s_country' ) );
            }
            Y.on( 'change', function( e )
            {
                if ( Y.Node.get( '#shipping-checkbox' ).get( 'checked' ) )
                {
                    updateShipping();
                }
                updateSubdivisions( e.currentTarget );
            }, '#country');
            Y.on( 'change', function( e )
            {
                if ( !Y.Node.get( '#shipping-checkbox' ).get( 'checked' ) )
                {
                    updateShipping();
                }
                updateSubdivisions( e.currentTarget );
            }, '#s_country');
            Y.on( 'click', function( e )
            {
                changeShipping();
                updateShipping();
            }, '#shipping-checkbox');
        }
    });
});

YUI().use("node", "event-mouseenter", function(Y) {
    if ( Y.Node.get("#AutomaticDeliveryTooltip")) {
        var nodeTip = Y.one("#AutomaticDeliveryTooltip")
        Y.on("mouseenter", function (e) {
            nodeTip.removeClass('hide');
        }, "#overlay-text p");
        Y.on("mouseleave", function (e) {
            nodeTip.addClass('hide');
        }, "#overlay-text p");
    }
});

/* uncomment for debugging
YUI({
    filter: 'debug',
    timeout: 10000
}).use( 'node', 'console', 'console-filters', 'dd-plugin', function (Y) {
    if (Y.Node.get("#debug") ) {
        Y.Node.get("BODY").prepend('<div id="yconsole"></div>' );
        Y.Node.get("BODY").addClass( 'yui-skin-sam');
// Configure the Console's logSource to Y.Global to make it universal
new Y.Console({
    boundingBox: '#yconsole',
    plugins: [ Y.Plugin.Drag ], //, Y.Plugin.ConsoleFilters
    logSource: Y.Global,
    style: 'separate',
    newestOnTop: true
}).render();
    }
});
*/

function ShowHide(id)
{
    YUI().use( 'node', function(Y)
    { 
        var node = Y.one( id );
        if ( node.hasClass( 'hide') )
        {
            node.removeClass('hide');
            node.addClass('show');
        }
        else
        {
            node.removeClass('show');
            node.addClass('hide');
        }
    });
}

function ezjson(uri, callback, args) 
{
    // Create business logic in a YUI sandbox using the 'io' and 'json' modules
    YUI().use('node', 'io', 'io-ez', 'dump', 'json-parse', function(Y) 
    {
        function onFailure(transactionid, response)
        {
            Y.log('Async call failed!');
        }
        function onComplete(transactionid, response, callback, args)
        {
            // transactionid : The transaction's ID.
            // response: The response object.
            // arguments: Object containing an array { complete: ['foo', 'bar'] }.
            Y.log('RAW JSON DATA: ' + response.responseText);

            // Process the JSON data returned from the server
            try 
            {
                var data = null;
                data = Y.JSON.parse(response.responseText);
                Y.log('PARSED DATA: ' + Y.Lang.dump(data));
            } 
            catch (e) 
            {
                Y.log('JSON Parse failed!');
                return;
            }
            callback(data, args);
        }
        
        Y.on('io:failure', onFailure, this);
        Y.on('io:complete', onComplete, this, callback, args);
        
        // Make the call to the server for JSON data
        transaction = Y.io('/xrowecommerce/json/' + uri, callback);
    });
}

function updateSubdivisions( country_node ) 
{
    YUI().use( 'node', 'io', 'io-ez', function( Y )
    {
        var country = country_node.get( 'options' ).item( country_node.get( 'selectedIndex' ) ).get( 'value' );

        Y.Node.get( '#s_state' ).set( 'disabled', 'disabled' );
        Y.Node.get( '#state' ).set( 'disabled', 'disabled' );
        Y.io.ez( 'xrowecommerce::getSubdivisions::' + country, 
        {
            arguments: country_node,
            on: 
            {
                success: function( id, r, country_node)
                {
                    YUI().use('node', function(Y) 
                    {
                        var data = r.responseJSON.content;

                        if( country_node.get('id') == 'country' )
                        {
                            var subdivision_node = Y.Node.get( '#state' );
                        }
                        else
                        {
                            var subdivision_node = Y.Node.get( '#s_state' );
                        }

                        // If state is selected: get the old value for checking it later
                        if ( subdivision_node.get( 'selectedIndex' ) > 0 )
                        {
                            var stateSelIndex = subdivision_node.get( 'selectedIndex' );
                            var oldStateValue = subdivision_node.get( 'options' ).item( stateSelIndex ).get( 'value' );
                        }

                        if ( !oldStateValue )
                        {
                            if( country_node.get('id') == 'country' && Y.Node.get( '#hidden_state' ) )
                            {
                                var oldStateValue = Y.Node.get( '#hidden_state' ).get( 'value' );
                            }
                            else if ( country_node.get('id') == 's_country' && Y.Node.get( '#hidden_s_state' ) )
                            {
                                var oldStateValue = Y.Node.get( '#hidden_s_state' ).get( 'value' );
                            }
                        }

                        var nodes = subdivision_node.all( 'option' );
                        var deleteNodes = function(n, a, b)
                        {
                            n.get( 'parentNode' ).removeChild(n);
                        };
                        nodes.each(deleteNodes);
                        var node = Y.Node.create( '<option>&nbsp;</option>' );
                        subdivision_node.appendChild(node);
                        var index = 0;
                        for (i in data ) 
                        {
                            index++;
                            if (oldStateValue == i) 
                            {
                                var stateSelected = index;
                            }
                            var node = Y.Node.create( '<option value="' + i + '">' + data[i] + '</option>' );
                            subdivision_node.appendChild(node);
                        }
                        if ( typeof( stateSelected ) != 'undefined' ) 
                        {
                            subdivision_node.set( 'selectedIndex', stateSelected );
                        }
                        else
                        {
                            subdivision_node.set('selectedIndex', 0);
                        }
                        Y.Node.get( '#s_state' ).removeAttribute( 'disabled' );
                        Y.Node.get( '#state' ).removeAttribute( 'disabled' );
                    });
                }
            }
        });
    });
}

function updateShipping() 
{
    YUI().use( 'node', 'io', 'io-ez', 'dump', 'json-parse', function( Y ) 
    {
        if ( !Y.Node.get('#shippingtype') )
        {
            return false;
        }

        if ( Y.Node.get( '#shipping-checkbox' ).get( 'checked' ) ) 
        {
            var selectedIndex = Y.Node.get( '#country' ).get( 'selectedIndex' );
            var country = Y.Node.get( '#country' ).get( 'options' ).item( selectedIndex ).get( 'value' );
        } 
        else 
        {
            var selectedIndex = Y.Node.get( '#s_country').get( 'selectedIndex' );
            var country = Y.Node.get( '#s_country' ).get( 'options' ).item( selectedIndex ).get( 'value' );
        }

        var doit = function(data) 
        {
            if ( Y.Node.get( '#shippingtype' ).get( 'tagName' ) == 'INPUT' )
            {
                return false;
            }

            // If shippingtype is selected: get the old value for checking it later
            if ( Y.Node.get( '#shippingtype' ).get( 'selectedIndex' ) > 0 )
            {
                var oldShippSelIndex = Y.Node.get( '#shippingtype' ).get( 'selectedIndex' );
                var oldname = Y.Node.get( '#shippingtype' ).get( 'options' ).item( oldShippSelIndex ).get( 'text' );
                var old = Y.Node.get( '#shippingtype' ).get( 'options' ).item( oldShippSelIndex ).get( 'value' );
            }

            var nodes = Y.all('#shippingtype option');
            var deleteNodes = function(n, a, b) 
            {
                n.get('parentNode').removeChild(n);
            };
            nodes.each(deleteNodes);
            var node = Y.Node.create('<option>&nbsp;</option>');
            for (i = 0; i < data.length; i++) 
            {
                if ( data[i][2] == false ) 
                {
                    var node = Y.Node.create('<option value="' + data[i][1] + '" disabled>' + data[i][0] + '</option>');
                } 
                else 
                {
                    if ( old == data[i][1] ) 
                    {
                        var selected = i;
                    }
                    var node = Y.Node.create('<option value="' + data[i][1] + '">' + data[i][0] + '</option>');
                }
    
                Y.Node.get( '#shippingtype' ).appendChild( node );
            }
            if (typeof (selected) != 'undefined') 
            {
                Y.Node.get('#shippingtype').set('selectedIndex', selected);
            } 
            else if ( oldShippSelIndex )
            {
                if ( Y.Node.get( '#shippingtype' ).get( 'selectedIndex' ) != -1 )
                {
                    var replace = new Array();
                    replace['%old%'] = oldname;
                    var newShippSelIndex = Y.Node.get( '#shippingtype' ).get( 'selectedIndex' );
                    var newname = Y.Node.get( '#shippingtype' ).get( 'options' ).item( newShippSelIndex ).get( 'text' );
                    replace['%new%'] = newname;
                    if ( oldname )
                    {
                        ez18nAlert("The shipping method '%old%' is not available for your country of destination and was changed to '%new%'.", replace);
                    }
                }
            }
            Y.log('INFO2: ' + Y.Lang.dump(Y.Node.get('#shippingtype').get('options')));
        };
        ezjson('getshipping?country=' + country, doit);
    });
}

function ez18nAlert(text, args) 
{
    YUI().use( 'node', 'io-ez', function(Y) 
    {
        Y.io.ez( 'xrowecommerce::translate::', 
        {
            data: 'text=' + text,
            arguments: args,
            on: 
            {
                success: function( id, r, args)
                {
                    var data = r.responseJSON.content;
                    YUI().use('node', function(Y) 
                    {
                        for ( var x in args) 
                        {
                            data = data.replace(x, args[x]);
                        }
                        alert(data);
                    });
                }
            }
        });
    });
}
    
function changeShipping() 
{
    YUI().use( 'node', function(Y) 
    {
        if (Y.Node.get( '#shipping-checkbox' ).get( 'checked' ) ) 
        {
            Y.Node.get( '#shippinginfo' ).setStyle( 'display', 'none' );
        } 
        else 
        {
            Y.Node.get( '#shippinginfo' ).setStyle('display', 'block' );
            /*if (document.register.company_name) 
            {
                document.register.s_company_name.value = document.register.company_name.value;
            }*/
            if ( Y.Node.get( '#s_company_name' ) && Y.Node.get( '#company_name' ) && Y.Node.get( '#company_name' ).get( 'value' ) != '' )
            {
                Y.Node.get( '#s_company_name' ).set( 'value', Y.Node.get( '#company_name' ).get( 'value' ) );
            }

            /*if (document.register.company_additional) 
            {
                document.register.s_company_additional.value = document.register.company_additional.value;
            }*/
            if ( Y.Node.get( '#s_company_additional' ) && Y.Node.get( '#company_additional' ) && Y.Node.get( '#company_additional' ).get( 'value' ) != '' )
            {
                Y.Node.get( '#s_company_additional' ).set( 'value', Y.Node.get( '#company_additional' ).get( 'value' ) );
            }

            //document.register.s_first_name.value = document.register.first_name.value;
            if ( Y.Node.get( '#s_first_name' ) && Y.Node.get( '#first_name' ) && Y.Node.get( '#first_name' ).get( 'value' ) != '' )
            {
                Y.Node.get( '#s_first_name' ).set( 'value', Y.Node.get( '#first_name' ).get( 'value' ) );
            }

            /*if (document.register.mi) 
            {
                document.register.s_mi.value = document.register.mi.value;
            }*/
            if ( Y.Node.get( '#s_mi' ) && Y.Node.get( '#mi' ) && Y.Node.get( '#mi' ).get( 'value' ) != '' )
            {
                Y.Node.get( '#s_mi' ).set( 'value', Y.Node.get( '#mi' ).get( 'value' ) );
            }

            //document.register.s_last_name.value = document.register.last_name.value;
            if ( Y.Node.get( '#s_last_name' ) && Y.Node.get( '#last_name' ) && Y.Node.get( '#last_name' ).get( 'value' ) != '' )
            {
                Y.Node.get( '#s_last_name' ).set( 'value', Y.Node.get( '#last_name' ).get( 'value' ) );
            }

            //document.register.s_address1.value = document.register.address1.value;
            if ( Y.Node.get( '#s_address1' ) && Y.Node.get( '#address1' ) && Y.Node.get( '#address1' ).get( 'value' ) != '' ) 
            {
                Y.Node.get( '#s_address1' ).set( 'value', Y.Node.get( '#address1' ).get( 'value' ) );
            }

            /*if (Y.Node.get('#address2') && Y.Node.get('#s_address2')) 
            {
                document.register.s_address2.value = document.register.address2.value;
            }*/
            if ( Y.Node.get( '#s_address2' ) && Y.Node.get( '#address2' ) && Y.Node.get( '#address2' ).get( 'value' ) != '' ) 
            {
                Y.Node.get( '#s_address2' ).set( 'value', Y.Node.get( '#address2' ).get( 'value' ) );
            }

            //document.register.s_city.value = document.register.city.value;
            if ( Y.Node.get( '#s_city' ) && Y.Node.get( '#city' ) && Y.Node.get( '#city' ).get( 'value' ) != '' ) 
            {
                Y.Node.get( '#s_city' ).set( 'value', Y.Node.get( '#city' ).get( 'value' ) );
            }

            //document.register.s_zip.value = document.register.zip.value;
            if ( Y.Node.get( '#s_zip' ) && Y.Node.get( '#zip' ) && Y.Node.get( '#zip' ).get( 'value' ) != '' )
            {
                Y.Node.get( '#s_zip' ).set( 'value', Y.Node.get( '#zip' ).get( 'value' ) );
            }

            //document.register.s_country.selectedIndex = document.register.country.selectedIndex;
            if ( Y.Node.get( '#s_country' ) && Y.Node.get( '#country' ) && Y.Node.get( '#country' ).get( 'selectedIndex' ) != '' )
            {
                Y.Node.get( '#s_country' ).set( 'selectedIndex', Y.Node.get( '#country' ).get( 'selectedIndex' ) );
                updateSubdivisions( Y.Node.get( '#s_country' ) );
            }
            
            //document.register.s_state.selectedIndex = document.register.state.selectedIndex;
            if ( Y.Node.get( '#s_state' ) && Y.Node.get( '#state' ) && Y.Node.get( '#state' ).get( 'selectedIndex' ) != '' )
            {
                Y.Node.get( '#s_state' ).set( 'selectedIndex', Y.Node.get( '#state' ).get( 'selectedIndex' ) );
            }

            //document.register.s_phone.value = document.register.phone.value;
            if ( Y.Node.get( '#s_phone' ) && Y.Node.get( '#phone' ) && Y.Node.get( '#phone' ).get( 'value' ) != '' )
            {
                Y.Node.get( '#s_phone' ).set( 'value', Y.Node.get( '#phone' ).get( 'value' ) );
            }

            //document.register.s_fax.value = document.register.fax.value;
            if ( Y.Node.get( '#s_fax' ) && Y.Node.get( '#fax' ) && Y.Node.get( '#fax' ).get( 'value' ) != '' ) 
            {
               Y.Node.get( '#s_fax' ).set( 'value', Y.Node.get( '#fax' ).get( 'value' ) );
            }

            //document.register.s_email.value = document.register.email.value;
            if ( Y.Node.get( '#s_email' ) && Y.Node.get( '#email' ) && Y.Node.get( '#email' ).get( 'value' ) != '' ) 
            {
                Y.Node.get( '#s_email' ).set( 'value', Y.Node.get( '#email' ).get( 'value' ) );
            }

        }
    });
};

function toggleCOS()
{
    YUI().use( 'node', function(Y) 
    { 
        var container = Y.Node.get('#cos-content');
        if ( container )
        {
            if ( container.getStyle('display') == 'block' )
            {
                container.setStyle('display', 'none');
            }
            else
            {
                container.setStyle('display', 'block');
            }
        }
    });
};

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
 * @param node
 *            DomNode to receive click event
 * @param image
 *            Path to the full image
 * @param imagetext
 *            Alternate footer text
 * @param doubleclick
 *            Double or single click
 */
function generatePopup(node, image, imagetext, doubleclick) 
{
    YUI().use('node', 'overlay', 'imageloader', function(Y) 
    {
        var xy = Y.one(node).getXY();
        imageNode = Y.Node.create('<img />');
        imageNode.set('id', Y.guid());
        var overlay = new Y.Overlay( 
        {
            headerContent : 'Popup: Click to close.',
            bodyContent : imageNode,
            width : 'auto',
            height : 'auto',
            centered : node,
            visible : false,
            xy : [ xy[0] + 10, xy[1] + 35 ]
        });
        if (imagetext) 
        {
            overlay.set('footerContent', imagetext);
        }
        var myFirstGroup = new Y.ImgLoadGroup( 
        {
            timeLimit : 2
        });
        myFirstGroup.registerImage( 
        {
            domId : imageNode.get('id'),
            srcUrl : image
        });

        overlay.render();

        Y.on('click', Y.bind(overlay.hide, overlay), overlay.get('contentBox'));
        if (doubleclick) 
        {
            Y.on('dblclick', function(e) 
            {
                overlay.show();
            }, node);
        } 
        else 
        {
            Y.on('click', function(e) {
                overlay.show();
            }, node);
        }
    });
};

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
};