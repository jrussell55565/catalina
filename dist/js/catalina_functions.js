function checkAll(){
	for (var i=0;i<document.forms[0].elements.length;i++)
	{
		var e=document.forms[0].elements[i];
		if ((e.name != 'allbox') && (e.type=='checkbox'))
		{
			e.checked=document.forms[0].allbox.checked;
		}
	}
}

function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition, showError);
    } else { 
        y = "Geolocation is not supported by this browser.";
    }
}

function showPosition(position) {
    y = getCoords(position);
    document.getElementById('hdn_coordinates').value = y;

}

function getCoords(position) {
    y = position.coords.latitude + '|' + position.coords.longitude;
    return y;
}

function showError(error) {
    switch(error.code) {
        case error.PERMISSION_DENIED:
            y = "User denied the request for Geolocation."
            break;
        case error.POSITION_UNAVAILABLE:
            y = "Location information is unavailable."
            break;
        case error.TIMEOUT:
            y = "The request to get user location timed out."
            break;
        case error.UNKNOWN_ERROR:
            y = "An unknown error occurred."
            break;
    }
}

function dummyCall(position) {
    y = getCoords(position);

  var xmlhttp;
  if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
      xmlhttp=new XMLHttpRequest();
    }
  else
    {// code for IE6, IE5
      xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
  /*xmlhttp.onreadystatechange=function() {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
      alert(xmlhttp.responseText);
    }else{
      alert("Missing");
    }
  }*/
  xmlhttp.open("POST","updategeodb.php",true);
  xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
  xmlhttp.send("hdn_coordinates="+y);

}

function pushCoords() {
  y = navigator.geolocation.getCurrentPosition(dummyCall, showError);
}

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}

function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

function reloadPage() {
    location.reload();
}
