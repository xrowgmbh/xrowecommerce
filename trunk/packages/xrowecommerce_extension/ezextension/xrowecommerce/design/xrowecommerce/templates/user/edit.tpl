{def $user=fetch( 'content', 'object', hash( 'object_id', $userID ) )
     $language=$user.initial_language.locale
     $tipafriend_access=fetch( 'user', 'has_access_to', hash( 'module', 'shop',
                                                              'function', 'wishlist' ) )
     $wishlist_access  = fetch( 'user', 'has_access_to', hash( 'module', 'content',
                                                            'function', 'read' ) )
     $orderhistory_access  = fetch( 'user', 'has_access_to', hash( 'module', 'order',
                                                            'function', 'history' ) )
     $notification_access  = fetch( 'user', 'has_access_to', hash( 'module', 'notification',
                                                            'function', 'settings' ) )}
<div class="full-user-edit">
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
                            <li>
                                <a title="{"Change my billing and shipping address or your credit card information."|i18n('extension/xrowecommerce')}" href="{concat("/content/edit/", $userAccount.contentobject_id, "/", "f", "/",$userAccount.contentobject.current_language)|ezurl('no')}">
                                    {"Change my billing and shipping address or creditcard."|i18n('extension/xrowecommerce')}
                                </a>
                            </li>
                            <li>
                                <a href="{'/user/password'|ezurl('no')}" title="{"Change my password"|i18n('extension/xrowecommerce')}">
                                    {"Change my password"|i18n('extension/xrowecommerce')}
                                </a>
                            </li>
                        </ul>
                    </td>
                    <td>
                        <h3>{"Shop Features"|i18n('extension/xrowecommerce')}</h3>
                        <ul>
                            {if $orderhistory_access}
                                <li>
                                    <a title="{"View my recent and current orders."|i18n('extension/xrowecommerce')}" href={"/order/history"|ezurl}>
                                        {"My orders."|i18n('extension/xrowecommerce')}
                                    </a>
                                </li>
                            {/if}
                            {if ezini( 'RecurringOrderSettings', 'enabled', 'recurringorders.ini' )}
                                <li>
                                    <a title="{"View my recurring orders."|i18n('extension/xrowecommerce')}" href={"/recurringorders/list"|ezurl}>
                                        {"My recurring orders."|i18n('extension/xrowecommerce')}
                                    </a>
                                </li>
                            {/if}
                            {if $wishlist_access}
                                <li>
                                    <a title="{"Edit or view my wishlist."|i18n('extension/xrowecommerce')}" href={"/shop/wishlist"|ezurl}>
                                        {"My wishlist."|i18n('extension/xrowecommerce')}
                                    </a>
                                </li>
                            {/if}
                            {if $notification_access}
                                <li>
                                    <a title="{"Edit or view your notification settings."|i18n('extension/xrowecommerce')}" href={"notification/settings"|ezurl}>
                                        {"My notification settings."|i18n("design/ezwebin/user/edit")}
                                    </a>
                                </li>
                            {/if}
                        </ul>
                    </td>
                </tr>
            </table>
        </div>
    </form>
</div>