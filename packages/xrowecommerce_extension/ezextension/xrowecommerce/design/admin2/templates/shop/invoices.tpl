<div class="print-selected-invoices">
{foreach $orders as $order}
    <div class="invoice-item">
    {include uri='design:shop/invoice.tpl' order=$order}
    </div>
{/foreach}
</div>