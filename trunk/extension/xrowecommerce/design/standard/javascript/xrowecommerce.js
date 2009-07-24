YUI().use("node", function(Y) {
	Y.on("domready", function() {
		if( Y.Node.get("#shipping-checkbox") )
		{
		Y.on("change", function(e) {
			updateShipping();
		}, "#country");
		Y.on("change", function(e) {
			alert('hi');
			updateShipping();
		}, "#s_country");
		}
	});
});
function ezjson(uri, callback) {
	//Create business logic in a YUI sandbox using the 'io' and 'json' modules
	YUI( {
		combine : true,
		timeout : 10000
	}).use("node", "io", "dump", "json-parse", function(Y) {

		function onFailure(transactionid, response, arguments) {
			Y.log("Async call failed!");
		}
		function onComplete(transactionid, response, arguments) {
			// transactionid : The transaction's ID.
			// response: The response object.
			// arguments: Object containing an array { complete: ['foo', 'bar']
			// }.

			Y.log("RAW JSON DATA: " + response.responseText);

			// Process the JSON data returned from the server
			try {
				var data = null;
				data = Y.JSON.parse(response.responseText);
				Y.log("PARSED DATA: " + Y.Lang.dump(data));
			} catch (e) {
				Y.log("JSON Parse failed!");
				return;
			}
			arguments(data);
		}

		Y.on('io:failure', onFailure, this);
		Y.on('io:complete', onComplete, this, callback);

		// Make the call to the server for JSON data
		transaction = Y.io("/xrowecommerce/json/" + uri, callback);

	});
}
function updateShipping() {
	YUI().use(
			"node",
			"dump",
			function(Y) {
				if (Y.Node.get("#shipping-checkbox").get("checked")) {
					var country = Y.Node.get('#country').get('options').item(
							Y.Node.get('#country').get('selectedIndex')).get(
							'value');
				} else {
					var country = Y.Node.get('#s_country').get('options').item(
							Y.Node.get('#s_country').get('selectedIndex')).get(
							'value');
				}
				if (country) {
					var shipping = Y.Node.get('#s_country')
				}
				var doit = function(data) {

					var old = Y.Node.get('#shippingtype').get('options').item(
							Y.Node.get('#shippingtype').get('selectedIndex'))
							.get('value');
					var nodes = Y.all('#shippingtype option');
					var deleteNodes = function(n, a, b) {
						n.get('parentNode').removeChild(n)
					};
					nodes.each(deleteNodes);
					for (i = 0; i < data.length; i++) {
						if( old == data[i][1])
						{
							var selected = i;
						}
						if (data[i][2] == false) {
							var node = Y.Node.create('<option value="'
									+ data[i][1] + '" disabled>' + data[i][0]
									+ '</option>');
						} else {
							var node = Y.Node.create('<option value="'
									+ data[i][1] + '">' + data[i][0]
									+ '</option>');
						}

						Y.Node.get('#shippingtype').appendChild(node);
					}
					if ( typeof(selected) != "undefined" )
					{
						Y.Node.get('#shippingtype').set('selectedIndex', selected)
					}
					else
					{
						alert( "Your previously selected shipping method is not avialable for your current shipping destination." );
					}
					Y.log("INFO2: "
							+ Y.Lang.dump(Y.Node.get('#shippingtype').get(
									'options')));

				}
				ezjson('getshipping?country=' + country, doit);

			});
}
function change() {
	YUI()
			.use(
					'node',
					function(Y) {
						if (Y.Node.get("#shipping-checkbox").get("checked")) {
							Y.Node.get("#shippinginfo").setStyle('display',
									'none');
						} else {
							Y.Node.get("#shippinginfo").setStyle('display',
									'block');
							if (document.register.company_name) {
								document.register.s_company_name.value = document.register.company_name.value;
							}
							if (document.register.company_additional) {
								document.register.s_company_additional.value = document.register.company_additional.value;
							}
							if (document.register.mi) {
								document.register.s_mi.value = document.register.mi.value;
							}
							if (document.register.state) {
								document.register.s_state.value = document.register.state.value;
							}
							document.register.s_first_name.value = document.register.first_name.value;
							document.register.s_last_name.value = document.register.last_name.value;

							document.register.s_zip.value = document.register.zip.value;
							document.register.s_phone.value = document.register.phone.value;
							if (Y.Node.get("#fax") && Y.Node.get("#s_fax")) {
								document.register.s_fax.value = document.register.fax.value;
							}
							document.register.s_email.value = document.register.email.value;
							document.register.s_address1.value = document.register.address1.value;
							if (Y.Node.get("#address2")
									&& Y.Node.get("#s_address2")) {
								document.register.s_address2.value = document.register.address2.value;
							}
							document.register.s_city.value = document.register.city.value;
							document.register.s_country.selectedIndex = document.register.country.selectedIndex;

						}
					});
}