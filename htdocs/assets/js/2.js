
function init() {
	searchbox = document.getElementById( "search_input" );
	searchbox.focused = false;
	
	submit = document.getElementById( "search_submit" );
	submit.focused = false;

	searchdefault = 'Zoeken in WebDBOverflow...';
	searchbox.value = searchdefault;

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