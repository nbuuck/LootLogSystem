String.prototype.trim = function() {
	return this.replace(/^\s+|\s+$/g,"");
}

function doHttpCall(url,callback,passReply,asXML)
{
	var xhReq = new XMLHttpRequest();
	xhReq.open("GET", url, true);
	xhReq.onreadystatechange = function()
	{
		if (xhReq.readyState == 4)
		{
			if(passReply)
			{
				if(asXML)
				{
					eval(callback + "(xhReq.responseXML);");
				}else{
					eval(callback + "(xhReq.responseText);");
				}
			}else{
				eval(callback + "();");
			}
		}
	}
	xhReq.send(null);
}
