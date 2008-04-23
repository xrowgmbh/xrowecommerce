<div id="col_main" >
	<h2>{$node.name|upcase()}</h2>
	<br />
	{attribute_view_gui attribute=$node.data_map.content}
</div>

<div style="width: 0px; height: 500px; float: left; display: inline;"></div>
<div id="col_right" >
{*    <form action={"/content/search/"|ezurl} method="get">
        <div style="vertical-align: middle;">
            <b><span style="font-family: Times; font-size: 14px;color: #308d9d; font-weight: normal;">SEARCH:</span></b>
            <input type="text" name="SearchText" value="keyword" size="15" class="searchbox">&nbsp;
            <input type="image" src={"images/go.gif"|ezdesign()} style="vertical-align:middle;">
            <div id="dottedline"></div>
        </div>
    </form>*}
<div style="width: 10px; height: 450px;"></div>
</div>