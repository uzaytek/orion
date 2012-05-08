http = createRequestObject();
elid = null;

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

function randomString() {
    var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
    var string_length = 8;
    var randomstring = '';
    for (var i=0; i<string_length; i++) {
	var rnum = Math.floor(Math.random() * chars.length);
	randomstring += chars.substring(rnum,rnum+1);
    }
    return randomstring;
}


function bulletinSubscribe(brandhandler) {
    mbox = document.getElementById('mailbox');
    email = mbox.value;
    http.open('get', SITE_ADDRESS + 'ajax.php?act=bulletinSubscribe&amp;email='+email);
    http.onreadystatechange = handleBrandSubscribe;
    http.send(null);
}

function handleBrandSubscribe() {
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

function stock(productid) {
    http.open('get', SITE_ADDRESS + 'ajax.php?act=stock&productid='+productid);
    http.onreadystatechange = handleStock;
    http.send(null);
}

function handleStock() {
    if(http.readyState == 4) {
	res = http.responseText;
	data = res.split('|');
	elon = document.getElementById('stockon'+data[0]);
	eloff = document.getElementById('stockoff'+data[0]);
        if(data[1] == 1) {
	    elon.style.display="none";
	    eloff.style.display="inline";
	}
	if(data[1] == 2) {
	    eloff.style.display="none";
	    elon.style.display="inline";
	}
    }
}

function basket(productid) {
    http.open('get', SITE_ADDRESS + 'ajax.php?'+randomString()+'&act=basket&productid='+productid);
    http.onreadystatechange = handleBasket;
    http.send(null);
}

function handleBasket() {
    if(http.readyState == 4) {
	res = http.responseText;
	data = res.split('|');	   
	elon = document.getElementById('basketon'+data[0]);
	elin = document.getElementById('basketinsert'+data[0]);
	elup = document.getElementById('basketupdate'+data[0]);
        if(data[1] == 1) {
	    elon.style.display="none";
	    elin.style.display="inline";
	    shiftOpacity('basketinsert'+data[0], 1000);
	    elup.style.display="none";
	}
	if(data[1] == 2) {
	    elon.style.display="none";
	    elin.style.display="none";
	    elup.style.display="inline";
	    shiftOpacity('basketupdate'+data[0], 1000);
	}
    }
}

function basketdelete(basketid) {
    http.open('get', SITE_ADDRESS + 'ajax.php?act=basketdelete&basketid='+basketid);
    http.onreadystatechange = handleBasketDelete;
    http.send(null);
}

function handleBasketDelete() {
    if(http.readyState == 4) {
	res = http.responseText;
	data = res.split('|');	   
        if(data[1] == 1) {
	    document.location.reload();
	}
    }
}


function basketplus(basketid) {
    http.open('get', SITE_ADDRESS + 'ajax.php?act=basketplus&basketid='+basketid);
    http.onreadystatechange = handleBasketPlus;
    http.send(null);
}

function handleBasketPlus() {
    if(http.readyState == 4) {
	res = http.responseText;
	data = res.split('|');	   
        if(data[1] == 1) {
	    document.location.reload();
	}
    }
}

function basketminus(basketid) {
    http.open('get', SITE_ADDRESS + 'ajax.php?act=basketminus&basketid='+basketid);
    http.onreadystatechange = handleBasketMinus;
    http.send(null);
}

function handleBasketMinus() {
    if(http.readyState == 4) {
	res = http.responseText;
	data = res.split('|');	   
        if(data[1] == 1) {
	    document.location.reload();
	}
        if(data[1] == 2) {
	    document.location.reload();
	}
    }
}

function opacity(id, opacStart, opacEnd, millisec) {
    //speed for each frame
    var speed = Math.round(millisec / 100);
    var timer = 0;

    //determine the direction for the blending, if start and end are the same nothing happens
    if(opacStart > opacEnd) {
        for(i = opacStart; i >= opacEnd; i--) {
            setTimeout("changeOpac(" + i + ",'" + id + "')",(timer * speed));
            timer++;
        }
    } else if(opacStart < opacEnd) {
        for(i = opacStart; i <= opacEnd; i++)
            {
            setTimeout("changeOpac(" + i + ",'" + id + "')",(timer * speed));
            timer++;
        }
    }
}

//change the opacity for different browsers
function changeOpac(opacity, id) {
    var object = document.getElementById(id).style;
    object.opacity = (opacity / 100);
    object.MozOpacity = (opacity / 100);
    object.KhtmlOpacity = (opacity / 100);
    object.filter = "alpha(opacity=" + opacity + ")";
} 

function shiftOpacity(id, millisec) {
    //if an element is invisible, make it visible, else make it ivisible
    if(document.getElementById(id).style.opacity == 0) {
        opacity(id, 0, 100, millisec);
    } else {
        opacity(id, 100, 0, millisec);
    }
} 

function favor(productid) {
    http.open('get', SITE_ADDRESS + 'ajax.php?act=favor&productid='+productid);
    http.onreadystatechange = handleFavor;
    http.send(null);
}

function handleFavor() {
    if(http.readyState == 4) {
	res = http.responseText;
	data = res.split('|');	   
	elon = document.getElementById('favoron'+data[0]);
	eloff = document.getElementById('favoroff'+data[0]);
        if(data[1] == 1) {
	    elon.style.display="none";
	    eloff.style.display="inline";
	}
	if(data[1] == 2) {
	    eloff.style.display="none";
	    elon.style.display="inline";
	}
    }
}

