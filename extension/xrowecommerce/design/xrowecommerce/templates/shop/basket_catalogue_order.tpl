<h1>{"Add Catalogue Order"|i18n("design/base/shop")}</h1>

<table class="shop-basket">
    <tr>
        <th><p>{"Article Number"|i18n("design/base/shop")}</p></th>
        <th class="optional"><p>{"Search"|i18n("design/base/shop")}</p></th>
        <th><p>{"Quantity"|i18n("design/base/shop")}</p></th>
        <th><p>{"Notation"|i18n("design/base/shop")}</p></th>
        <th class="price"><p>{"Price in €"|i18n("design/base/shop")}</p></th>
        <th class="optional"><p>{"Delete"|i18n("design/base/shop")}</p></th>
    </tr>

    <tr>
        <td><input type="text" size="12" value="" name="KatalogueSearchText" id="kalaogue_searchtext"/></td>
        <td><input id="catalogue_button" class="smallbutton" type="submit" alt="Submit" value="{"Search"|i18n("design/base/shop")}"/></td>
        <td><input type="text" class="halfbox" size="12" value="" name="KatalogueQuantity" id="kalaogue_quantity"/></td>
        <td><p class="optional">{"please type in an article number"|i18n("design/base/shop")}</p></td>
        <td><p class="optional">{"please type in an article number"|i18n("design/base/shop")}</p></td>
        <td><input id="catalogue_delete_button" class="button" type="submit" alt="Submit" value="{"Add Catalogue Item"|i18n("design/base/shop")}"/></td>
    </tr>

</table>
