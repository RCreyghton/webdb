var xmlhttp;

function GetXMLHTTPObject()
{
	var xmlhttp = null;

	if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp = new XMLHttpRequest();
	} else {
		// code for IE6, IE5
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}

	return xmlhttp;
}

function getThreadTitles(str)
{ 
	xmlhttp = GetXMLHTTPObject();
	if (xmlhttp == null){
		alert ("Your browser does not support HTTP requests");
		return;
	}

	var url="./search/quick";
	str = str.replace(/\n/g, " ");
	url = url + "?search=" + str;
	url = url + "&sid=" + Math.random();

	xmlhttp.onreadystatechange = stateChanged;
	xmlhttp.open("GET", url, true);
	xmlhttp.send(null);
}

function stateChanged(str)
{
	if (xmlhttp.readyState == 4 && xmlhttp.status == 200) { 
		document.getElementById( "quickThreads" ).innerHTML = xmlhttp.responseText;
		if ( xmlhttp.responseText != "" ) {
			document.getElementById( "search_input" ).setAttribute( "autocomplete", "off" )
		} else {
			document.getElementById( "search_input" ).removeAttribute( "autocomplete" )
		}
	}
}

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
