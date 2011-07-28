{def $user=fetch( 'content', 'object', hash( 'object_id', $userID ) )
     $language=$user.initial_language.locale
     $tipafriend_access=fetch( 'user', 'has_access_to', hash( 'module', 'content',
                                                           'function', 'tipafriend' ) )}
<div class="full-user-edit">
<div class="border-box">
<div class="border-tl"><div class="border-tr"><div class="border-tc"></div></div></div>
<div class="border-ml"><div class="border-mr"><div class="border-mc float-break">
    <form action={concat($module.functions.edit.uri,"/",$userID)|ezurl} method="post" name="Edit">
        <input type="hidden" name="ContentObjectLanguageCode" value="{$language}" />

        <div class="account-info">
            <div class="attribute-header">
                <h1 class="long">{"User profile"|i18n('extension/xrowecommerce')}</h1>
            </div>
        </div>
        <div class="payment-info">
            <table class="list">
                <tr>
                    <th colspan="2">
                        <h2>{'My Account:'|i18n('extension/xrowecommerce')}</h2>
                    </th>
                </tr>
                <tr>
                    <td colspan="2">
                        <h3>{'Profile'|i18n('extension/xrowecommerce')}:</h3>
                        <ul>
                            <li>{"Username"|i18n('extension/xrowecommerce')}: {$userAccount.login|wash}</li>
                            <li>{"Name"|i18n('extension/xrowecommerce')}: {attribute_view_gui attribute=$userAccount.contentobject.data_map.first_name} {attribute_view_gui attribute=$userAccount.contentobject.data_map.last_name}</li>
                            <li>{'E-mail'|i18n('extension/xrowecommerce')}: {$userAccount.email|wash(email)}</li>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <th colspan="2"><h2>{"Personal Information"|i18n('extension/xrowecommerce')}</h2></th>
                </tr>
                <tr>
                    <td>
                        <h3>{"Account Settings"|i18n('extension/xrowecommerce')}</h3>
                        <ul>
                            <li><a title="{"Change my billing and shipping address or your credit card information."|i18n('extension/xrowecommerce')}" href="{concat("/content/edit/", $userAccount.contentobject_id, "/", "f", "/",$userAccount.contentobject.current_language)|ezurl('no')}">{"Change my billing and shipping address or creditcard."|i18n('extension/xrowecommerce')}</a></li>
                            <li><a href="{'/user/password'|ezurl('no')}" title="{"Change my password"|i18n('extension/xrowecommerce')}">{"Change my password"|i18n('extension/xrowecommerce')}</a></li>
                        </ul>
                    </td>
                    <td>
                        <h3>{"Shop Features"|i18n('extension/xrowecommerce')}</h3>
                        <ul>
                            <li><a title="{"View my recent and current orders."|i18n('extension/xrowecommerce')}" href={"/order/history"|ezurl}>{"My orders."|i18n('extension/xrowecommerce')}</a></li>
                            <li><a title="{"View my recurring orders."|i18n('extension/xrowecommerce')}" href={"/recurringorders/list"|ezurl}>{"My recurring orders."|i18n('extension/xrowecommerce')}</a></li>
                            <li><a title="{"Edit or view my wishlist."|i18n('extension/xrowecommerce')}" href={"/shop/wishlist"|ezurl}>{"My wishlist."|i18n('extension/xrowecommerce')}</a></li>
                            <li><a title="{"Edit or view your notification settings."|i18n('extension/xrowecommerce')}" href={"notification/settings"|ezurl}>{"My notification settings."|i18n("design/ezwebin/user/edit")}</a></li>
                        </ul>
                    </td>
                </tr>
            </table>
        </div>
    </form>
</div></div></div>
<div class="border-bl"><div class="border-br"><div class="border-bc"></div></div></div>
</div>
</div>