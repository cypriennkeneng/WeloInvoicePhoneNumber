{extends file="parent:documents/index.tpl"}

{block name="document_index_head_right"}
    {if $weloPhoneNumber.DisplayPhoneNumber && $User.billing.phone}
        {$Containers.Header_Box_Right.value}
        {s name="DocumentIndexCustomerID"}{/s} {$User.billing.customernumber|string_format:"%06d"}<br />
        {if $User.billing.ustid}
            {s name="DocumentIndexUstID"}{/s} {$User.billing.ustid|replace:" ":""|replace:"-":""}<br />
        {/if}

        {block name="document_welo_invoice_email_head_right_email"}
            {if $weloPhoneNumber.isWeloEmailEnabled && $weloOrderData.email}
                {if $weloOrderData.DisplayLabel}Email: {/if}{$weloOrderData.email}<br />
            {/if}
        {/block}

        {block name="document_welo_invoice_phone_number_head_right_number"}
            {if $User.billing.phone}
                Tel: {$User.billing.phone}<br />
            {/if}
        {/block}

        {s name="DocumentIndexOrderID"}{/s} {$Order._order.ordernumber}<br />
        {s name="DocumentIndexDate"}{/s} {$Document.date}<br />
        {if $Document.deliveryDate}{s name="DocumentIndexDeliveryDate"}{/s} {$Document.deliveryDate}<br />{/if}
    {else}
        {$smarty.block.parent}
    {/if}
{/block}