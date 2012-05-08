

function createRequestObject() {
    var ro;
    var browser = navigator.appName;
    if(browser == "Microsoft Internet Explorer"){
        ro = new ActiveXObject("Microsoft.XMLHTTP");
    }else{
        ro = new XMLHttpRequest();
    }
    return ro;
}

var http = createRequestObject();
var elid = null;

function handleNewsletter() {
    if(http.readyState == 4) {
	res = http.responseText;
	data = res.split('|');
	elon = document.getElementById('mailboxon');
	eloff = document.getElementById('mailboxoff');
        if(data[0] == 1) {
	    elon.style.display="none";
	    eloff.style.display="inline";
	}
    }
}

// prd_list.php ON_Table.tr
function changeaction(_ofm, _value) {    
    if (_value != '') {
	_ofm.action = _value;
    }
}

function myValidator(_ofm) {
    doCheck();
}