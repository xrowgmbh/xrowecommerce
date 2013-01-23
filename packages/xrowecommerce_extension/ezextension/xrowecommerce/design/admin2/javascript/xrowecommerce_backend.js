$(document).ready(function(){
    if( $('form[name="orderlist"]').length )
    {
        $('#MarkOrdersButton').mousedown(function(){
            $('input[type="checkbox"]').each(function(){
                $(this).attr('checked', false);
                if( $(this).closest('tr').find('select[name^="StatusList"] option:selected').attr('value') == $('#MarkOrdersSelection').val() )
                {
                    $(this).attr('checked', true);
                }
            });
        });
        
        $('select[name^="StatusList"]').change(function(){
            $('.button-right input[name="SaveOrderStatusButton"]').removeClass('button').addClass('defaultbutton');
        });
        
        $('#SetToButton').mousedown(function(){
            $('form[name="orderlist"] input[type="checkbox"]').each(function(){
                if( $(this).attr('checked') == 'checked' )
                {
                    $(this).closest('tr').find('select[name^="StatusList"] option:selected').removeAttr('selected');
                    $(this).closest('tr').find('select[name^="StatusList"] option[value="' + $('#SetToSelection').val() + '"]').attr('selected', 'selected');
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