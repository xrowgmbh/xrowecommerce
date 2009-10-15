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

