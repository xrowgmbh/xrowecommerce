$(document).ready(function(){
    if( $('form[name="orderlist"]').length )
    {
        $('#MarkPendingOrdersButton').mousedown(function(){
            $('input[type="checkbox"]').each(function(){
                if( $(this).closest('tr').find('select[name^="StatusList"] option:selected').attr('value') == "1") 
                $(this).attr('checked', true);
            });
        });
        
        $('select').change(function(){
            $('.button-right input[name="SaveOrderStatusButton"]').removeClass('button').addClass('defaultbutton');
        });
        
        $('#SetToProcessingButton').mousedown(function(){
            $('form[name="orderlist"] input[type="checkbox"]').each(function(){
                if( $(this).attr('checked') == 'checked' )
                {
                    $(this).closest('tr').find('select[name^="StatusList"] option:selected').removeAttr('selected');
                    $(this).closest('tr').find('select[name^="StatusList"] option[value="2"]').attr('selected', 'selected');
                    $('.button-right input.button[name="SaveOrderStatusButton"]').removeClass('button').addClass('defaultbutton');
                }
            });
        });
        
        $('#PrintMarkedOrdersButton').mousedown(function(){
            $('#printidarray').empty();
            $('form[name="orderlist"] input[type="checkbox"]').each(function(){
                if( $(this).attr('checked') == 'checked' )
                {
                    $('#printidarray').append('<input type="hidden" value="' + $(this).attr('value') + '" name="IDArray[]"></input>');
                }
            });
        });
    }
});