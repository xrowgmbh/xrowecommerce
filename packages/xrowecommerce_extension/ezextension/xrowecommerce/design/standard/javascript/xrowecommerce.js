$(document).ready(function() {
    $('#show_auto_tip').mouseenter(function(){
        if ( !$('#AutomaticDeliveryTooltip').is(':animated') )
        {
            $('#AutomaticDeliveryTooltip').slideDown('300');
        }
    }).mouseleave(function(){
        $('#AutomaticDeliveryTooltip').slideUp('300');
    });

    $('.shop-userregister input#company, .user_register input#company').click(function(){
        $('div.company').slideDown('slow');
    });
    $('.shop-userregister input#home, .user_register input#home').click(function(){
        $('div.company').slideUp('slow');
    });
    $('.shop-userregister input#s_company, .user_register input#s_company').click(function(){
        $('div.s_company').slideDown('slow');
    });
    $('.shop-userregister input#s_home, .user_register input#s_home').click(function(){
        $('div.s_company').slideUp('slow');
    });
    $('#shipping-link').mousedown(function(){
        $('input#shipping-checkbox').trigger('click');
    });
    

});
YUI(YUI3_config).use( 'node', function(Y)
{
    Y.on( 'domready', function() 
    {
        if ( Y.one( '#country' ) )
        {
            updateSubdivisions( Y.one( '#country' ) );
        }
        if ( Y.one( '#shipping-checkbox' ) )
        {
            if ( Y.one( '#s_country' ) )
            {
                updateSubdivisions( Y.one( '#s_country' ) );
            }
            Y.on( 'change', function( e )
            {
                if ( Y.one( '#shipping-checkbox' ).get( 'checked' ) )
                {
                    updateShipping();
                }
                if ( Y.one( '#country' ) )
                {
                    updateSubdivisions( e.currentTarget );
                }
            }, '#country');
            Y.on( 'change', function( e )
            {
                if ( !Y.one( '#shipping-checkbox' ).get( 'checked' ) )
                {
                    updateShipping();
                }
                if ( Y.one( '#country' ) )
                {
                updateSubdivisions( e.currentTarget );
                }
            }, '#s_country');
            Y.on( 'click', function( e )
            {
                changeShipping();
                updateShipping();
            }, '#shipping-checkbox');
        }
    });
});

YUI(YUI3_config).use("node", "event-mouseenter", function(Y) {
    if ( Y.one("#AutomaticDeliveryTooltip")) {
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
    if (Y.one("#debug") ) {
        Y.one("BODY").prepend('<div id="yconsole"></div>' );
        Y.one("BODY").addClass( 'yui-skin-sam');
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
    YUI(YUI3_config).use( 'node', function(Y)
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
    YUI(YUI3_config).use('node', 'io', 'io-ez', 'dump', 'json-parse', function(Y) 
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
    YUI(YUI3_config).use( 'node', 'io', 'io-ez', function( Y )
    {
        var country = country_node.get( 'options' ).item( country_node.get( 'selectedIndex' ) ).get( 'value' );
        if( Y.one( '#s_state' ) )
        {
            Y.one( '#s_state' ).set( 'disabled', 'disabled' );
        }
        if( Y.one( '#state' ) )
        {
            Y.one( '#state' ).set( 'disabled', 'disabled' );
        }
        Y.io.ez( 'xrowecommerce::getSubdivisions::' + country, 
        {
            arguments: country_node,
            on: 
            {
                success: function( id, r, country_node)
                {
                    YUI(YUI3_config).use('node', function(Y) 
                    {
                        var data = r.responseJSON.content;

                        if( country_node.get('id') == 'country' )
                        {
                            var subdivision_node = Y.one( '#state' );
                        }
                        else
                        {
                            var subdivision_node = Y.one( '#s_state' );
                        }

                        // If state is selected: get the old value for checking it later
                        if ( subdivision_node.get( 'selectedIndex' ) > 0 )
                        {
                            var stateSelIndex = subdivision_node.get( 'selectedIndex' );
                            var oldStateValue = subdivision_node.get( 'options' ).item( stateSelIndex ).get( 'value' );
                        }

                        if ( !oldStateValue )
                        {
                            if( country_node.get('id') == 'country' && Y.one( '#hidden_state' ) )
                            {
                                var oldStateValue = Y.one( '#hidden_state' ).get( 'value' );
                            }
                            else if ( country_node.get('id') == 's_country' && Y.one( '#hidden_s_state' ) )
                            {
                                var oldStateValue = Y.one( '#hidden_s_state' ).get( 'value' );
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
                        if( Y.one( '#s_state' ) )
                        {
                            Y.one( '#s_state' ).removeAttribute( 'disabled' );
                        }
                        if( Y.one( '#state' ) )
                        {
                            Y.one( '#state' ).removeAttribute( 'disabled' );
                        }
                    });
                }
            }
        });
    });
}

function updateShipping() 
{
    YUI(YUI3_config).use( 'node', 'io', 'io-ez', 'dump', 'json-parse', function( Y ) 
    {
        if ( !Y.one('#shippingtype') )
        {
            return false;
        }

        if ( Y.one( '#shipping-checkbox' ).get( 'checked' ) ) 
        {
            var selectedIndex = Y.one( '#country' ).get( 'selectedIndex' );
            var country = Y.one( '#country' ).get( 'options' ).item( selectedIndex ).get( 'value' );
        } 
        else 
        {
            var selectedIndex = Y.one( '#s_country').get( 'selectedIndex' );
            var country = Y.one( '#s_country' ).get( 'options' ).item( selectedIndex ).get( 'value' );
        }

        var doit = function(data) 
        {
            if ( Y.one( '#shippingtype' ).get( 'tagName' ) == 'INPUT' )
            {
                return false;
            }

            // If shippingtype is selected: get the old value for checking it later
            if ( Y.one( '#shippingtype' ).get( 'selectedIndex' ) > 0 )
            {
                var oldShippSelIndex = Y.one( '#shippingtype' ).get( 'selectedIndex' );
                var oldname = Y.one( '#shippingtype' ).get( 'options' ).item( oldShippSelIndex ).get( 'text' );
                var old = Y.one( '#shippingtype' ).get( 'options' ).item( oldShippSelIndex ).get( 'value' );
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
    
                Y.one( '#shippingtype' ).appendChild( node );
            }
            if (typeof (selected) != 'undefined') 
            {
                Y.one('#shippingtype').set('selectedIndex', selected);
            } 
            else if ( oldShippSelIndex )
            {
                if ( Y.one( '#shippingtype' ).get( 'selectedIndex' ) != -1 )
                {
                    var replace = new Array();
                    replace['%old%'] = oldname;
                    var newShippSelIndex = Y.one( '#shippingtype' ).get( 'selectedIndex' );
                    var newname = Y.one( '#shippingtype' ).get( 'options' ).item( newShippSelIndex ).get( 'text' );
                    replace['%new%'] = newname;
                    if ( oldname )
                    {
                        ez18nAlert("The shipping method '%old%' is not available for your country of destination and was changed to '%new%'.", replace);
                    }
                }
            }
            Y.log('INFO2: ' + Y.Lang.dump(Y.one('#shippingtype').get('options')));
        };
        ezjson('getshipping?country=' + country, doit);
    });
}

function ez18nAlert(text, args) 
{
    YUI(YUI3_config).use( 'node', 'io-ez', function(Y) 
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
                    YUI(YUI3_config).use('node', function(Y) 
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
    YUI(YUI3_config).use( 'node', function(Y) 
    {
        if (Y.one( '#shipping-checkbox' ).get( 'checked' ) ) 
        {
            Y.one( '#shippinginfo' ).setStyle( 'display', 'none' );
        } 
        else 
        {
            Y.one( '#shippinginfo' ).setStyle('display', 'block' );
            /*if (document.register.company_name) 
            {
                document.register.s_company_name.value = document.register.company_name.value;
            }*/
            if ( Y.one( '#s_company_name' ) && Y.one( '#company_name' ) && Y.one( '#company_name' ).get( 'value' ) != '' )
            {
                Y.one( '#s_company_name' ).set( 'value', Y.one( '#company_name' ).get( 'value' ) );
            }

            /*if (document.register.company_additional) 
            {
                document.register.s_company_additional.value = document.register.company_additional.value;
            }*/
            if ( Y.one( '#s_company_additional' ) && Y.one( '#company_additional' ) && Y.one( '#company_additional' ).get( 'value' ) != '' )
            {
                Y.one( '#s_company_additional' ).set( 'value', Y.one( '#company_additional' ).get( 'value' ) );
            }
            
            if ( Y.one( '#s_title' ) && Y.one( '#title' ) && Y.one( '#title' ).get( 'value' ) != '' )
            {
                Y.one( '#s_title' ).set( 'value', Y.one( '#title' ).get( 'value' ) );
            }

            //document.register.s_first_name.value = document.register.first_name.value;
            if ( Y.one( '#s_first_name' ) && Y.one( '#first_name' ) && Y.one( '#first_name' ).get( 'value' ) != '' )
            {
                Y.one( '#s_first_name' ).set( 'value', Y.one( '#first_name' ).get( 'value' ) );
            }

            /*if (document.register.mi) 
            {
                document.register.s_mi.value = document.register.mi.value;
            }*/
            if ( Y.one( '#s_mi' ) && Y.one( '#mi' ) && Y.one( '#mi' ).get( 'value' ) != '' )
            {
                Y.one( '#s_mi' ).set( 'value', Y.one( '#mi' ).get( 'value' ) );
            }

            //document.register.s_last_name.value = document.register.last_name.value;
            if ( Y.one( '#s_last_name' ) && Y.one( '#last_name' ) && Y.one( '#last_name' ).get( 'value' ) != '' )
            {
                Y.one( '#s_last_name' ).set( 'value', Y.one( '#last_name' ).get( 'value' ) );
            }

            //document.register.s_address1.value = document.register.address1.value;
            if ( Y.one( '#s_address1' ) && Y.one( '#address1' ) && Y.one( '#address1' ).get( 'value' ) != '' ) 
            {
                Y.one( '#s_address1' ).set( 'value', Y.one( '#address1' ).get( 'value' ) );
            }

            /*if (Y.one('#address2') && Y.one('#s_address2')) 
            {
                document.register.s_address2.value = document.register.address2.value;
            }*/
            if ( Y.one( '#s_address2' ) && Y.one( '#address2' ) && Y.one( '#address2' ).get( 'value' ) != '' ) 
            {
                Y.one( '#s_address2' ).set( 'value', Y.one( '#address2' ).get( 'value' ) );
            }

            //document.register.s_city.value = document.register.city.value;
            if ( Y.one( '#s_city' ) && Y.one( '#city' ) && Y.one( '#city' ).get( 'value' ) != '' ) 
            {
                Y.one( '#s_city' ).set( 'value', Y.one( '#city' ).get( 'value' ) );
            }

            //document.register.s_zip.value = document.register.zip.value;
            if ( Y.one( '#s_zip' ) && Y.one( '#zip' ) && Y.one( '#zip' ).get( 'value' ) != '' )
            {
                Y.one( '#s_zip' ).set( 'value', Y.one( '#zip' ).get( 'value' ) );
            }

            //document.register.s_country.selectedIndex = document.register.country.selectedIndex;
            if ( Y.one( '#s_country' ) && Y.one( '#country' ) && Y.one( '#country' ).get( 'selectedIndex' ) != '' )
            {
                Y.one( '#s_country' ).set( 'selectedIndex', Y.one( '#country' ).get( 'selectedIndex' ) );
                updateSubdivisions( Y.one( '#s_country' ) );
            }
            
            if ( Y.one( '#s_country' ) && Y.one( '#country' ) && Y.one( '#country' ).get( 'selectedIndex' ) != '' )
            {
                Y.one( '#s_country' ).set( 'selectedIndex', Y.one( '#country' ).get( 'selectedIndex' ) );
                updateSubdivisions( Y.one( '#s_country' ) );
            }
            
            //document.register.s_state.selectedIndex = document.register.state.selectedIndex;
            if ( Y.one( '#s_state' ) && Y.one( '#state' ) && Y.one( '#state' ).get( 'selectedIndex' ) != '' )
            {
                Y.one( '#s_state' ).set( 'selectedIndex', Y.one( '#state' ).get( 'selectedIndex' ) );
            }

            //document.register.s_phone.value = document.register.phone.value;
            if ( Y.one( '#s_phone' ) && Y.one( '#phone' ) && Y.one( '#phone' ).get( 'value' ) != '' )
            {
                Y.one( '#s_phone' ).set( 'value', Y.one( '#phone' ).get( 'value' ) );
            }

            //document.register.s_fax.value = document.register.fax.value;
            if ( Y.one( '#s_fax' ) && Y.one( '#fax' ) && Y.one( '#fax' ).get( 'value' ) != '' ) 
            {
               Y.one( '#s_fax' ).set( 'value', Y.one( '#fax' ).get( 'value' ) );
            }

            //document.register.s_email.value = document.register.email.value;
            if ( Y.one( '#s_email' ) && Y.one( '#email' ) && Y.one( '#email' ).get( 'value' ) != '' ) 
            {
                Y.one( '#s_email' ).set( 'value', Y.one( '#email' ).get( 'value' ) );
            }

        }
    });
};

function toggleCOS()
{
    YUI(YUI3_config).use( 'node', function(Y) 
    { 
        var container = Y.one('#cos-content');
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
    YUI(YUI3_config).use('node', 'overlay', 'imageloader', function(Y) 
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