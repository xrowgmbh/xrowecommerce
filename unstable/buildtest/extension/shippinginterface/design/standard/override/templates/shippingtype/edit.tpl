{default attribute_base='ContentObjectAttribute'
         html_class='full'}
<select name="{$attribute_base}_ezstring_data_text_{$attribute.id}">
    <!--
    <option value="0" {if eq($attribute.data_text|wash(),0)} selected="selected" {/if}>Standard Shipping (deprecated)</option>
    <option value="1" {if eq($attribute.data_text|wash(),1)} selected="selected" {/if}>Next Day Service (deprecated)</option>
    <option value="2" {if eq($attribute.data_text|wash(),2)} selected="selected" {/if}>2nd Day Service (deprecated)</option>
    -->
    <option value="3" {if eq($attribute.data_text|wash(),3)} selected="selected" {/if}>UPS Ground (USA only)</option>
    <option value="4" {if eq($attribute.data_text|wash(),4)} selected="selected" {/if}>UPS Next Business Day Air (USA only)</option>
    <option value="5" {if eq($attribute.data_text|wash(),5)} selected="selected" {/if}>UPS 2nd Business Day Air (USA only)</option>
    <option value="6" {if eq($attribute.data_text|wash(),6)} selected="selected" {/if}>USPS Express Mail International (EMS)</option>
    <option value="7" {if eq($attribute.data_text|wash(),7)} selected="selected" {/if}>USPS Global Express Guaranteed</option>
</select>
{/default}