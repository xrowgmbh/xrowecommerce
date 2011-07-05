{if and(not(ezurl('no','full')|begins_with('https')) ,ezini( 'SocialNetwork', 'socialplugins', 'xrowecommerce.ini' )|ne('disabled'))}
    {if is_set(module_params().parameters.NodeID)}
        {def $shared_node_id = module_params().parameters.NodeID
             $shared_node = fetch('content', 'node', hash('node_id', $shared_node_id))}
    {/if}
    {def $plugins = ezini( 'SocialNetwork', 'plugins', 'xrowecommerce.ini' )
         $accounts = ezini( 'SocialNetwork', 'account', 'xrowecommerce.ini' )}
    <div class="share-icons">

        {if and(is_set($shared_node),$plugins['facebook']|eq('enabled'))}
            <div class="plugin">
                <div id="fb-root"></div>
                <script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>
                <fb:like href="{$shared_node.path_with_names|ezurl('no','full')}"
                         send="true"
                         layout="button_count"
                         width="450"
                         show_faces="false"
                         colorscheme="light"
                         font="">
                </fb:like>
            </div>
        {/if}

        {if and(is_set($shared_node),$plugins['twitter']|eq('enabled'))}
            <div class="plugin">
                {if and(is_set($node), is_set($node.object.current_language_object.language_code))}
                    {if $node.object.current_language_object.language_code|eq('ger')}
                        {def $datalang = "data-lang='de'"}
                    {elseif $node.object.current_language_object.language_code|eq('jpn')}
                        {def $datalang = "data-lang='ja'"}
                    {elseif $node.object.current_language_object.language_code|eq('fre')}
                        {def $datalang = "data-lang='fr'"}
                    {elseif $node.object.current_language_object.language_code|eq('ita')}
                        {def $datalang = "data-lang='it'"}
                    {elseif $node.object.current_language_object.language_code|eq('cat')}
                        {def $datalang = "data-lang='es'"}
                    {elseif $node.object.current_language_object.language_code|eq('rus')}
                        {def $datalang = "data-lang='ru'"}
                    {elseif $node.object.current_language_object.language_code|eq('tur')}
                        {def $datalang = "data-lang='tr'"}
                    {/if}
                {/if}
                <a href="http://twitter.com/share"
                   class="twitter-share-button"
                   data-url="{$shared_node.path_with_names|ezurl('no','full')}"
                   data-text="{$shared_node.name}"
                   data-count="horizontal"
                   {if is_set($accounts['twitter'])}data-via="{$accounts['twitter']}"{/if}
                   {if is_set($datalang)}{$datalang}{/if}>Tweet</a>
                <script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
            </div>
        {/if}

    </div>
{undef $shared_node_id $shared_node $plugins $accounts}
{/if}
