{def $user=fetch( 'content', 'object', hash( 'object_id', $userID ) )
     $language=$user.initial_language.locale}

<div class="border-box">
<div class="border-tl"><div class="border-tr"><div class="border-tc"></div></div></div>
<div class="border-ml"><div class="border-mr"><div class="border-mc float-break">

    <form action={concat($module.functions.edit.uri,"/",$userID)|ezurl} method="post" name="Edit">
    <input type="hidden" name="ContentObjectLanguageCode" value="{$language}" />

        <div class="user-edit">
            <div class="attribute-header">
                <h1 class="long">{"User profile"|i18n("design/ezwebin/user/edit")}</h1>
            </div>
{def $order_list=get_order_history()}
            <table class="accountinfo">
                <tr>
                    <th colspan="2">
                        <h3>{'Account Information'|i18n('extension/xrowecommerce')}</h3>
                    </th>
                </tr>
                <tr>
                    <td>{"Username"|i18n("design/ezwebin/user/edit")}</td>
                    <td>{$userAccount.login|wash}</td>
                </tr>
                <tr>
                    <td>{"Name"|i18n("design/ezwebin/user/edit")}</td>
                    <td>{attribute_view_gui attribute=$userAccount.contentobject.data_map.first_name} {attribute_view_gui attribute=$userAccount.contentobject.data_map.last_name}</td>
                </tr>
                <tr>
                    <td>{'E-mail'|i18n('extension/xrowecommerce')}</td>
                    <td>{$userAccount.email|wash(email)}</td>
                </tr>
                {* points to an admin module - insecure 
                <tr>
                    <td colspan="2">
                        <p><a href={concat("/shop/customerorderview/", $userID, "/", $userAccount.email)|ezurl}>{"My orders"|i18n("design/ezwebin/user/edit")}</a></p>
                    </td>
                </tr>
                *}
            </table>

            <div class="userinfo">
                {if $userAccount.contentobject.data_map.shippingaddress.data_int|eq(1)}

                <table>
                    <tr>
                        <th colspan="2">
                         <h3>{"Billing & shipping address"|i18n("extension/xrowecommerce")|wash}</h3>
                        </th>
                    </tr>
                    {if eq(ezini( 'Settings', 'CompanyName', 'xrowecommerce.ini' ), 'enabled' )}
                    <tr>
                        <td>{'Company name'|i18n('extension/xrowecommerce')}</td>
                        <td>{attribute_view_gui attribute=$userAccount.contentobject.data_map.company_name}</td>
                    </tr>
                    {/if}
                    {if eq(ezini( 'Settings', 'CompanyAdditional', 'xrowecommerce.ini' ), 'enabled' )}
                    <tr>
                        <td>{'Form of company'|i18n('extension/xrowecommerce')}</td>
                        <td>{attribute_view_gui attribute=$userAccount.contentobject.data_map.company_additional}</td>
                    </tr>
                    {/if}
                    {if eq(ezini( 'Settings', 'TaxID', 'xrowecommerce.ini' ), 'enabled' )}
                    <tr>
                        <td>{'Tax ID'|i18n('extension/xrowecommerce')}</td>
                        <td>{attribute_view_gui attribute=$userAccount.contentobject.data_map.tax_id}</td>
                    </tr>
                    {/if}
                    <tr>
                        <td>{'First name'|i18n('extension/xrowecommerce')}</td>
                        <td>{attribute_view_gui attribute=$userAccount.contentobject.data_map.first_name}</td>
                    </tr>
                    <tr>
                        <td>{'Last name'|i18n('extension/xrowecommerce')}</td>
                        <td>{attribute_view_gui attribute=$userAccount.contentobject.data_map.last_name}</td>
                    </tr>
                    <tr>
                        <td>{'Address 1'|i18n('extension/xrowecommerce')}</td>
                        <td>{attribute_view_gui attribute=$userAccount.contentobject.data_map.address1}</td>
                    </tr>
                    {if eq(ezini( 'Settings', 'Address2', 'xrowecommerce.ini' ), 'enabled' )}
                    <tr>
                        <td>{'Address 2'|i18n('extension/xrowecommerce')}</td>
                        <td>{attribute_view_gui attribute=$userAccount.contentobject.data_map.address2}</td>
                    </tr>
                    {/if}
                    <tr>
                        <td>{'Zip / Postcode'|i18n('extension/xrowecommerce')}</td>
                        <td>{attribute_view_gui attribute=$userAccount.contentobject.data_map.zip_code}</td>
                    </tr>
                    <tr>
                        <td>{'City / Town'|i18n('extension/xrowecommerce')}</td>
                        <td>{attribute_view_gui attribute=$userAccount.contentobject.data_map.city}</td>
                    </tr>
                    <tr>
                        <td>{'Phone'|i18n('extension/xrowecommerce')}</td>
                        <td>{attribute_view_gui attribute=$userAccount.contentobject.data_map.phone}</td>
                    </tr>
                    <tr>
                        <td>{'Fax'|i18n('extension/xrowecommerce')}</td>
                        <td>{attribute_view_gui attribute=$userAccount.contentobject.data_map.fax}</td>
                    </tr>
                    <tr>
                        <td>{'Country'|i18n('extension/xrowecommerce')}</td>
                        <td>{attribute_view_gui attribute=$userAccount.contentobject.data_map.country}</td>
                    </tr>
                </table>

                {else}

                <table>
                    <tr>
                        <th colspan="2">
                            <h3>{'Billing Information'|i18n('extension/xrowecommerce')}</h3>
                        </th>
                    </tr>
                    {if eq(ezini( 'Settings', 'CompanyName', 'xrowecommerce.ini' ), 'enabled' )}
                    <tr>
                        <td>{'Company name'|i18n('extension/xrowecommerce')}</td>
                        <td>{attribute_view_gui attribute=$userAccount.contentobject.data_map.company_name}</td>
                    </tr>
                    {/if}
                    {if eq(ezini( 'Settings', 'CompanyAdditional', 'xrowecommerce.ini' ), 'enabled' )}
                    <tr>
                        <td>{'Form of company'|i18n('extension/xrowecommerce')}</td>
                        <td>{attribute_view_gui attribute=$userAccount.contentobject.data_map.company_additional}</td>
                    </tr>
                    {/if}
                    {if eq(ezini( 'Settings', 'TaxID', 'xrowecommerce.ini' ), 'enabled' )}
                    <tr>
                        <td>{'Tax ID'|i18n('extension/xrowecommerce')}</td>
                        <td>{attribute_view_gui attribute=$userAccount.contentobject.data_map.tax_id}</td>
                    </tr>
                    {/if}
                    <tr>
                        <td>{'First name'|i18n('extension/xrowecommerce')}</td>
                        <td>{attribute_view_gui attribute=$userAccount.contentobject.data_map.first_name}</td>
                    </tr>
                    <tr>
                        <td>{'Last name'|i18n('extension/xrowecommerce')}</td>
                        <td>{attribute_view_gui attribute=$userAccount.contentobject.data_map.last_name}</td>
                    </tr>
                    <tr>
                        <td>{'Address 1'|i18n('extension/xrowecommerce')}</td>
                        <td>{attribute_view_gui attribute=$userAccount.contentobject.data_map.address1}</td>
                    </tr>
                    {if eq(ezini( 'Settings', 'Address2', 'xrowecommerce.ini' ), 'enabled' )}
                    <tr>
                        <td>{'Address 2'|i18n('extension/xrowecommerce')}</td>
                        <td>{attribute_view_gui attribute=$userAccount.contentobject.data_map.address2}</td>
                    </tr>
                    {/if}
                    <tr>
                        <td>{'Zip / Postcode'|i18n('extension/xrowecommerce')}</td>
                        <td>{attribute_view_gui attribute=$userAccount.contentobject.data_map.zip_code}</td>
                    </tr>
                    <tr>
                        <td>{'City / Town'|i18n('extension/xrowecommerce')}</td>
                        <td>{attribute_view_gui attribute=$userAccount.contentobject.data_map.city}</td>
                    </tr>
                    <tr>
                        <td>{'Phone'|i18n('extension/xrowecommerce')}</td>
                        <td>{attribute_view_gui attribute=$userAccount.contentobject.data_map.phone}</td>
                    </tr>
                    <tr>
                        <td>{'Fax'|i18n('extension/xrowecommerce')}</td>
                        <td>{attribute_view_gui attribute=$userAccount.contentobject.data_map.fax}</td>
                    </tr>
                    <tr>
                        <td>{'Country'|i18n('extension/xrowecommerce')}</td>
                        <td>{attribute_view_gui attribute=$userAccount.contentobject.data_map.country}</td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <th colspan="2">
                            <h3>{'Shipping Information'|i18n('extension/xrowecommerce')}</h3>
                        </th>
                    </tr>
                    {if eq(ezini( 'Settings', 'CompanyName', 'xrowecommerce.ini' ), 'enabled' )}
                    <tr>
                        <td>{'Company name'|i18n('extension/xrowecommerce')}</td>
                        <td>{attribute_view_gui attribute=$userAccount.contentobject.data_map.s_company_name}</td>
                    </tr>
                    {/if}
                    {if eq(ezini( 'Settings', 'CompanyAdditional', 'xrowecommerce.ini' ), 'enabled' )}
                    <tr>
                        <td>{'Form of company'|i18n('extension/xrowecommerce')}</td>
                        <td>{attribute_view_gui attribute=$userAccount.contentobject.data_map.s_company_additional}</td>
                    </tr>
                    {/if}
                    <tr>
                        <td>{'First name'|i18n('extension/xrowecommerce')}</td>
                        <td>{attribute_view_gui attribute=$userAccount.contentobject.data_map.s_first_name}</td>
                    </tr>
                    <tr>
                        <td>{'Last name'|i18n('extension/xrowecommerce')}</td>
                        <td>{attribute_view_gui attribute=$userAccount.contentobject.data_map.s_last_name}</td>
                    </tr>
                    <tr>
                        <td>{'Address 1'|i18n('extension/xrowecommerce')}</td>
                        <td>{attribute_view_gui attribute=$userAccount.contentobject.data_map.s_address1}</td>
                    </tr>
                    {if eq(ezini( 'Settings', 'Address2', 'xrowecommerce.ini' ), 'enabled' )}
                    <tr>
                        <td>{'Address 2'|i18n('extension/xrowecommerce')}</td>
                        <td>{attribute_view_gui attribute=$userAccount.contentobject.data_map.s_address2}</td>
                    </tr>
                    {/if}
                    <tr>
                        <td>{'Zip / Postcode'|i18n('extension/xrowecommerce')}</td>
                        <td>{attribute_view_gui attribute=$userAccount.contentobject.data_map.s_zip_code}</td>
                    </tr>
                    <tr>
                        <td>{'City / Town'|i18n('extension/xrowecommerce')}</td>
                        <td>{attribute_view_gui attribute=$userAccount.contentobject.data_map.s_city}</td>
                    </tr>
                    <tr>
                        <td>{'Phone'|i18n('extension/xrowecommerce')}</td>
                        <td>{attribute_view_gui attribute=$userAccount.contentobject.data_map.s_phone}</td>
                    </tr>
                    {if eq(ezini( 'ShippingSettings', 'DisplayFax', 'xrowecommerce.ini' ), 'enabled' )}
                    <tr>
                        <td>{'Fax'|i18n('extension/xrowecommerce')}</td>
                        <td>{attribute_view_gui attribute=$userAccount.contentobject.data_map.s_fax}</td>
                    </tr>
                    {/if}
                    <tr>
                        <td>{'Country'|i18n('extension/xrowecommerce')}</td>
                        <td>{attribute_view_gui attribute=$userAccount.contentobject.data_map.s_country}</td>
                    </tr>
                    <tr>
                        <td>{'E-mail'|i18n('extension/xrowecommerce')}</td>
                        <td>{attribute_view_gui attribute=$userAccount.contentobject.data_map.s_email}</td>
                    </tr>
                </table>

                {/if}
            </div>
            <div class="orderhistory">
            {literal}
                <script type="text/javascript">
                    function OpenPrintWindow (adress) 
                    {
                        PrintWindow = window.open(adress, "PrintWindow", "width=1,height=1,left=100,top=200, toolbar=no, scrollbars=no");
                        PrintWindow.onload = function()
                        {
                                PrintWindow.print();
                                PrintWindow.close();
                        };
                        window.focus();
                    }
                </script>
            {/literal}
            {def $currency = false() $locale = false() $symbol = false() $product_info_count = false() }
                {if $order_list}
                    <table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
                    <tr class="orderhistory"><th colspan="5"><h3>{"Order history"|i18n("design/standard/shop")}</h3></th></tr>
                        <tr>
                            <th>
                               {"Order No"|i18n("design/standard/shop")}
                            </th>
                            <th>
                               {"Date"|i18n("design/standard/shop")}
                            </th>
                            <th>
                               {"Status"|i18n("design/standard/shop")}
                            </th>
                            <th>
                               {"Total inc. TAX"|i18n("design/standard/shop")}
                            </th>
                            <th>
                                {"View"|i18n("design/standard/shop")}
                            </th>
                        </tr>
                        {foreach $order_list as $Order sequence array( 'dark', 'light' ) as $style}
                            {set currency = fetch( 'shop', 'currency', hash( 'code', $Order.productcollection.currency_code ) ) }
                            {if $currency}
                                {set locale = $currency.locale symbol = $currency.symbol}
                            {else}
                                {set locale = false() symbol = false()}
                            {/if}

                            <tr>
                                <td class="{$style}">
                                {$Order.order_nr}
                                </td>
                                <td class="{$style}">
                                   {$Order.created|l10n(shortdatetime)}
                                </td>
                                <td class="{$style}">
                                   {$Order.status_name}
                                </td>
                                <td class="{$style}" align="right">
                                   {$Order.total_inc_vat|l10n( 'currency', $locale, $symbol )}
                                </td>
                                <td class="{$style}" align="right">
                                   <a href={concat("/shop/orderview/",$Order.id,"/0")|ezurl} target="_blank"><img src={"orderhistory/view.gif"|ezimage()} title="{"View"|i18n("extension/xrowecommerce")}"></a>
                                   <a href={concat("/shop/orderview/",$Order.id,"/0")|ezurl} target="_blank" onclick="OpenPrintWindow(this.href); return false" ><img src={"orderhistory/printer.png"|ezimage()} title="{"Print"|i18n("extension/xrowecommerce")}"></a>
                                </td>
                            </tr>
                        {/foreach}
                    </table>
                {/if}
            </div>
            {undef}
            <div class="buttonblock">
                <input class="button" type="submit" name="EditButton" value="{'Edit profile / Change password'|i18n('extension/xrowecommerce')}" />
                {*<input class="button" type="submit" name="ChangePasswordButton" value="{'Change password'|i18n('design/ezwebin/user/edit')}" />*}
            </div>
        </div>
    </form>
</div></div></div>
<div class="border-bl"><div class="border-br"><div class="border-bc"></div></div></div>
</div>