function insertAfter( newElement, targetElement )
{
    var parent = targetElement.parentNode;
    if ( parent.lastChild == targetElement )
    {
        parent.appendChild(newElement);
    }
    else
    {
        parent.insertBefore(newElement, targetElement.nextSibling);
    }
}

function xrow_move( data )
{
    if ( data.direction == 'up' )
        x = -1;
    else
        x = 1;

    if ( data.tr )
    {
        var tbody = data.tr.parentNode;
        var i = data.tr.rowIndex + x;
        if ( tbody.rows.length > 1 )
        {
            if ( i < 0 )
                i += tbody.rows.length;

            if ( i == tbody.rows.length )
                i = 0;

            tbody.removeChild( data.tr );
            var nRow = tbody.insertRow( i );
            tbody.replaceChild( data.tr, nRow );
        }
    }
}

function findAttribute( selectBoxID )
{
    var selectBox = document.getElementById( selectBoxID );
    if ( selectBox )
    {
        for ( var i = 0; i < selectBox.options.length; i++ )
        {
            if ( selectBox.options[i].selected == true )
                return selectBox.options[i].value;
        }
    }
}

function findAttributeName( selectBoxID )
{
    var selectBox = document.getElementById( selectBoxID );
    if ( selectBox )
    {
        for ( var i = 0; i < selectBox.options.length; i++ )
        {
            if ( selectBox.options[i].selected == true )
                return selectBox.options[i].text;
        }
    }
}

function addAttribute( attributeDivIdentifier, tbody_id, selectbox_id, noinfo_id, sortboxid, attributename, addtosortbox )
{
    var attributeDiv = document.getElementById( 'xrow_attribute_' + attributeDivIdentifier );
    var tbody = document.getElementById( tbody_id );
    var selectBox = document.getElementById( selectbox_id );

    var noinfo = document.getElementById( noinfo_id );
    if ( noinfo )
    {
        noinfo.style.display = 'none';
    }

    if ( attributeDiv && tbody && selectBox )
    {
        var rowcount = tbody.rows.length - 1;

        var newRow = document.createElement( 'TR' );
        newRow.setAttribute( 'id', 'xrow_tr_attribute_' + attributeDivIdentifier );
        var cell1 = document.createElement( "TD" );
        cell1.vAlign = 'top';

        newRow.appendChild( cell1 );

        var newdiv = document.createElement( "DIV" );
        cell1.appendChild( newdiv );

        newdiv.innerHTML = attributeDiv.innerHTML;
        attributeDiv.innerHTML = '';

        if ( rowcount > 0 )
            insertAfter( newRow, tbody.lastChild );
        else
            tbody.appendChild( newRow );

        if ( selectBox )
        {
            for ( var i = 0; i < selectBox.options.length; i++ )
            {
                if ( selectBox.options[i].value == attributeDivIdentifier )
                    selectBox.options[i] = null;
            }
        }
        if ( addtosortbox )
        {
            var sortBox = document.getElementById( sortboxid );
            if ( sortBox )
            {
                sortBox.options[sortBox.options.length] = new Option( attributename, attributeDivIdentifier );
            }
        }
    }
}

function xrow_delete_template( divtag, container, identifier, name, msg, selectbox_id, noinfo_id, sortboxid )
{
    if ( confirm ( msg ) )
    {
        var attribute_tr = document.getElementById( divtag );
        if ( attribute_tr )
        {
            var condiv = document.getElementById( container );
            if ( condiv )
                condiv.innerHTML = attribute_tr.getElementsByTagName( 'TD' )[0].getElementsByTagName( 'DIV' )[0].innerHTML;
            var tr_parent = attribute_tr.parentNode;
            tr_parent.removeChild( attribute_tr );
        }
        var selectBox = document.getElementById( selectbox_id );
        if ( selectBox )
        {
            selectBox.options[selectBox.options.length] = new Option( name, identifier );
        }
        if ( tr_parent && tr_parent.childNodes.length == 0 )
        {
            var noinfo = document.getElementById( noinfo_id );
            if ( noinfo )
                noinfo.style.display = 'block';
        }
        var sortBox = document.getElementById( sortboxid );
        if ( sortBox )
        {
            for ( var i = 0; i < sortBox.options.length; i++ )
            {
                if ( sortBox.options[i].value == identifier )
                    sortBox.options[i] = null;
            }
        }
    }
}

function xrow_confirm( msg, id )
{
    if ( confirm( msg ) )
    {
        var formular = document.getElementById( id );
        return formular.submit();
    }
}

function addVariation( fromid, toid )
{
    var fromtr = document.getElementById( fromid );
    var to_tbody = document.getElementById( toid );
    if ( fromtr && to_tbody )
    {
        //var newindex = to_tbody.rows.length;
        //alert( newindex );
        var temphtml = fromtr.innerHTML;
        var pattern1 = /xxxrownumberxxx/gi;
        temphtml = temphtml.replace( pattern1, newindex);

        var pattern2 = /DefaultXrowProductVariation/gi;
        temphtml = temphtml.replace( pattern2, 'XrowProductVariation' );
        //alert( temphtml );
        var newRow = document.createElement( 'TR' );
        if ( newindex % 2 == 0 )
            newRow.className = 'bgdark';
        else
            newRow.className = 'bglight';

        if ( newindex > 0 )
            insertAfter( newRow, to_tbody.lastChild );
        else
            to_tbody.appendChild( newRow );

        var child = false;
        var newtd = false;
        var tdchildren = fromtr.childNodes;

        for ( var i = 0; i < tdchildren.length; i++ )
        {
            if ( tdchildren[i].nodeName == 'TD' )
            {
                child = tdchildren[i];

                newtd = document.createElement( 'TD' );
                if ( newRow.childNodes.length > 0 )
                    insertAfter( newtd, newRow.lastChild );
                else
                    newRow.appendChild( newtd );
                newdiv = document.createElement( 'DIV' );
                newtd.className = child.className;

                newtd.appendChild( newdiv );
                newdiv.innerHTML = child.innerHTML;
            }
        }

        newindex++;
    }
}

function xrowaddpriceline( fromid, toid, line )
{
    var fromtr = document.getElementById( fromid );
    var to_tbody = document.getElementById( toid );

    if ( fromtr && to_tbody )
    {
        var temphtml = fromtr.innerHTML;
        var pattern1 = /xxxrownumberxxx/gi;
        temphtml = temphtml.replace( pattern1, line);

        var pattern2 = /DefaultXrowProductVariation/gi;
        temphtml = temphtml.replace( pattern2, 'XrowProductVariation' );

        var newindex = to_tbody.rows.length;

        var newRow = document.createElement( 'TR' );
        if ( newindex % 2 == 0 )
            newRow.className = 'bgdark';
        else
            newRow.className = 'bglight';

        if ( newindex > 0 )
            insertAfter( newRow, to_tbody.lastChild );
        else
            to_tbody.appendChild( newRow );

        var newtd = document.createElement( 'TD' );
        newRow.appendChild( newtd );

        var newdiv = document.createElement( 'DIV' );
        newtd.appendChild( newdiv );

        newdiv.innerHTML = temphtml;

    }
}