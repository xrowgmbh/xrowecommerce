YAHOO.namespace("xrowecommerce");
function test2( el )
{
	if ( el.tagName != 'textarea' )
	{
		return true;
	}
	else
	{
		return false;
	}
}
function assignDefaultSubmitButtons()
{
	var forms = YAHOO.util.Dom.getElementsBy(function(el){return true;},'form'); 
	for (var i = 0; i <= forms.length; i++)
	{
		var handler = function(type, args, obj) {
			var test = this;
		    alert("key press handled: " + args[0]);
		    test.click();
		  };
		var form = forms[i];
		var input = YAHOO.util.Dom.getElementsBy( function(el){return test2(el);} , false , form )

			for (var y = 0; y <= input.length; y++)
			{
				var kl = new YAHOO.util.KeyListener(input[y], { keys:13 }, handler );
				kl.enable();
			}
		var _defaultSubmit = YAHOO.util.Dom.getElementsByClassName( "continue-button", false, form ); // search default submit button
		if(_defaultSubmit!=null && _defaultSubmit.length>0)
		{
			var kl = new YAHOO.util.KeyListener(form, { keys:13 }, handler );

kl.enable();



		  /**$j("input", _form).keypress(function(e){ // attach onkeypress event listener for each input field
			var keycode;
				if (window.event) {//IE
					keycode = window.event.keyCode;
				}else if (e) {  //FF
					keycode = e.which;
				}else {
					return true;
				}
				if(keycode==13){
					_defaultSubmit[0].click();  // emulates click on defaultSubmit button
					return false;   // stops propagating keypress event
				}
		  });
		  **/
		}
   }
}

//YAHOO.util.Event.onDOMReady(assignDefaultSubmitButtons); 