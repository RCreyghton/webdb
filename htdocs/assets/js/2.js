
function init() {
	var searchbox = document.getElementById( "search_input" );

	searchbox.focused = false;
	var submit = document.getElementById( "search_submit" );
	submit.focused = false;

	var searchdefault = 'Zoeken in WebDBOverflow...';
	searchbox.value = searchdefault;

	function testFocus() {
		if ( searchbox.focused == false && submit.focused == false )
			clearSearch();
	}

	function clearSearch() {
		if ( submit.focused == false ) {
			searchbox.value = searchdefault;
		
			if ( document.getElementById( "quickThreads" ).innerHTML != "" ) {
				document.getElementById( "quickThreads" ).innerHTML = "";
				searchbox.removeAttribute( "autocomplete" );
			}
		}
	}

	function beginSearch() {
		if ( searchbox.value == searchdefault ) {
			searchbox.value = "";
		}
	}

	searchbox.onfocus = function() {
		this.focused = true;
		beginSearch();
	}
	searchbox.onblur = function() {
		this.focused = false;
		setTimeout("testFocus()", 500);
	}
	submit.onfocus = function ()
	{
		this.focused = true;
	}
	submit.onblur = function()
	{
		this.focused = false;
		setTimeout("testFocus()", 500);
	}
	
}