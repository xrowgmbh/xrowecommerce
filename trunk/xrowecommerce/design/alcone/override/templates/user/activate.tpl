<div id="col_main" >
	<h2>{"Activate account"|i18n("design/standard/user")|upcase()}</h2>
	<br />
	
<p>
{section show=$account_activated}
{'Your account is now activated.'|i18n('design/standard/user')}
{section-else}
{'Sorry, the key submitted was not a valid key. Account was not activated.'|i18n('design/standard/user')}
{/section}

</p>
	
</div>

<div style="width: 0px; height: 500px; float: left; display: inline;"></div>
<div id="col_right" >
    <form action={"/content/search/"|ezurl} method="get">
        <div style="vertical-align: middle;">
            <b><span style="font-family: Times; font-size: 14px;color: #308d9d; font-weight: normal;">SEARCH:</span></b>
            <input type="text" name="SearchText" value="keyword" size="15" class="searchbox">&nbsp;
            <input type="image" src={"images/go.gif"|ezdesign()} style="vertical-align:middle;">
            <div id="dottedline"></div>
        </div>
    </form>
<div style="width: 10px; height: 450px;"></div>
</div>