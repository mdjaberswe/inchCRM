<script>
	$(document).ready(function()
	{
		$('.account').change(function()
		{
			var accountId = $(this).val();
			var row = $(this).closest('.row');
			var accountDetails = row.find('.field-details.field-account');
			var currency = row.find('.currency');

			if(accountId == '')
			{
				accountDetails.slideUp();
				currency.val(globalVar.baseCurrencyId).trigger('change');
			}
			else
			{
				$.ajax(
				{
					type  	: 'GET',
					url		: '{!! route('admin.account.single.data') !!}',
					data	: {'id' : accountId},
					dataType: 'JSON',
					success	: function(data)
							  {
							  	if(data.status == true)
							  	{
							  		$.each(data.info, function(index, value)
							  		{
							  			row.find('.'+index).html(value);
							  		});

							  		currency.val(data.currency).trigger('change');

							  		accountDetails.slideDown();
							  	}
							  	else
							  	{
							  		accountDetails.slideUp();
							  	}
							  }
				});				
			}
		});

		$("select[name='currency_id']").change(function()
		{
			var currencySymbol = $('option:selected', this).attr('symbol');
			$('span.symbol').html(currencySymbol);
		});

		$('.discount-type').change(function()
		{
			var discountType = $(this).val();
			var nextRow = $(this).closest('.row').parent().parent().next('.row');
			var discountPercent = nextRow.find('.discount-percent');
			var tbody = nextRow.find('tbody');
			var tfoot = nextRow.find('tfoot');

			switch(discountType)
			{
				case 'pre' :
					discountPercent.html('(%)');
				break;

				case 'post' :
					discountPercent.html('(%)');
				break;

				case 'flat' :
					discountPercent.html('');
				break;

				default : discountPercent.html('(%)');
			}

			calculateAllItems(tbody, discountType);
			calculateItemSheetFooter(tbody, tfoot);
		});

		$('.item-add .btn').click(function()
		{
			var currencySymbol = $($(this).closest('tfoot')).prev('tbody').find('span.symbol:first').text();
			var newRow = getNewItemRow(currencySymbol);
			$(this).closest('table').find('tbody').append(newRow);
		});

		$('.item-sheet-body').on('click', '.close', function()
		{
			var tbody = $(this).closest('tbody');
			var tfoot = tbody.next('tfoot');

			$(this).closest('tr').remove();
			calculateItemSheetFooter(tbody, tfoot);
		});

		$('.item-sheet-body').on('focusout', '.rate, .quantity, .tax, .discount', function()
		{
			var field = $(this);
			var value = $(this).val();
			dontLeftNumberAwkward(field, value);
		});

		$('.item-sheet-body').on('keyup', '.rate, .quantity, .tax, .discount', function()
		{
			var thisField = $(this);
			var discountType = $(this).closest('.row').prev('.row').find('.discount-type').val();
			var tbody = $(this).closest('tbody');
			var tr = $(this).closest('tr');
			var tfoot = tbody.next('tfoot');

			itemFieldFormatter(tr, thisField)
			calculateSingleItem(tr, discountType);
			calculateItemSheetFooter(tbody, tfoot);
		});

		$('.item-sheet-body').on('keypress', '.rate, .quantity, .tax, .discount', function(event)
		{
			var thisField = $(this);
			var charCode = event.which;
			var input = String.fromCharCode(event.which);
			var outcome = keypressPositiveNumberFilter(thisField, charCode, input)

			if(outcome == false)
			{
				event.preventDefault();
			}
		});

		$('.adjustment').keypress(function(event)
		{
			var thisField = $(this);
			var charCode = event.which;
			var input = String.fromCharCode(event.which);
			var outcome = keypressNumberFilter(thisField, charCode, input);

			if(outcome == false)
			{
				event.preventDefault();
			}
		});

		$('.adjustment').keydown(function(event)
		{
			var thisField = $(this);
			var charCode = event.which;

			keydownIncrementDecrement(thisField, charCode);			
		});	

		$('.adjustment').keyup(function()
		{
			numberFieldFormatter($(this));

			var adjustment = Number($(this).val());
			
			if(!isNaN(adjustment))
			{
				var grandTotal = $(this).closest('tfoot').find('.grand-total');
				var plainGrandTotal = $(this).closest('tfoot').find('.plain-grand-total');
				var plainGrandTotalVal = Number(plainGrandTotal.val());			
				var currentGrandTotal =  plainGrandTotalVal + adjustment;
				grandTotal.val(twoDecimalFormat(currentGrandTotal));
			}	
		});

		$('.adjustment').focusout(function()
		{
			var field = $(this);
			var value = $(this).val();
			dontLeftNumberAwkward(field, value);
		});
	});

	function getNewItemRow(currencySymbol = null)
	{
		var newRow = "<tr>";
		newRow += "<td><input type='text' name='item_name[]' class='form-control item-name' placeholder='Enter Item' autocomplete='off'></td>";
	    newRow += "<td><input type='text' name='quantity[]' value='1' class='form-control quantity' placeholder='Quantity' autocomplete='off'></td>";
	    newRow += "<td><input type='text' name='rate[]' class='form-control rate' placeholder='Rate' autocomplete='off'></td>";
	    newRow += "<td>";
	    newRow += "<input type='text' name='discount[]' class='form-control discount' placeholder='Discount' autocomplete='off'>";
	    newRow += "<input type='hidden' name='discount_val[]' value='0' class='discount-val'>";
	    newRow += "</td>";
	    newRow += "<td>";
	    newRow += "<input type='text' name='tax[]' class='form-control tax' placeholder='Tax' autocomplete='off'>";
	    newRow += "<input type='hidden' name='tax_val[]' value='0' class='tax-val'>";
	    newRow += "</td>";
	    newRow += "<td class='amount-column'>";
	    newRow += "<span class='sheet-currency symbol'>" + currencySymbol + "</span> ";
	    newRow += "<span class='amount'>0.00</span>";
	    newRow += "<input type='hidden' name='amount[]' value='0' class='amount-val'>";
	    newRow += "<input type='hidden' name='item_total[]' value='0' class='item-total'>";
	    newRow += "<button type='button' class='close'><span aria-hidden='true'>&times;</span></button>";
	    newRow += "</td>";
	    newRow += "</tr>";

	    return newRow;
	}
</script>