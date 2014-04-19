var shortcuts = new Array();

function registerShortcut(keyNumber,clickableObjectID)
{
	if(!isShortcutRegistered(keyNumber))
	{
		var shortcut = new Array();
		shortcut[0] = keyNumber;
		shortcut[1] = clickableObjectID;
		shortcuts.push(shortcut);
	}else{
		alert("Key Number " + keyNumber + " is already registered as a shortcut.");
	}
}

function keyPressHandler(key)
{
	var keynum = null;
	if(window.event)
	{
		keynum = window.event.keyCode;
	}else{
		if(!key){return;}
		keynum = key.which;
	}
	if(isShortcutRegistered(keynum))
	{
		window.open(document.getElementById(getAssociatedObjectName(keynum)).href,"_self",true);
	}
}

function isShortcutRegistered(keyNum)
{
	var isRegistered = false;
	for(var i = 0; i < shortcuts.length; i++)
	{
		if(shortcuts[i][0] == keyNum)
		{
			isRegistered = true;
		}
	}
	return isRegistered;
}

function getAssociatedObjectName(keyNum)
{
	for(var i = 0; i < shortcuts.length; i++)
	{
		if(shortcuts[i][0] == keyNum)
		{
			return shortcuts[i][1];
		}
	}
	return null;
}
