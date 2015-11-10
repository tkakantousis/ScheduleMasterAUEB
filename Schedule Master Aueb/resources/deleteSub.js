var xmlHttp;
var slot;
var schedule;
var lessonToBeDeleted;
var submitID;
function confirmation(id,slot,schedule,lessonid) 
{
	this.slot = slot;
	this.schedule=schedule;
	this.lessonToBeDeleted=lessonid;
	this.submitID=id;
	var answer = confirm("Delete entry?")
	if (answer)
	{
		xmlHttp = GetXmlHttpObject();
		if (xmlHttp==null)
		{
			alert ("Browser does not support HTTP Request");
			return;
		}
		var url="delete.php";
		url=url+"?id=" + id;
		xmlHttp.onreadystatechange=stateDeleteChanged;
		xmlHttp.open("GET",url,true);
		xmlHttp.send(null);
	}
	else
	{
	}
}


function stateDeleteChanged()
{
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
	{
		slots[lessonToBeDeleted][1]--;
		updateLessonMenu(lessonToBeDeleted);
		
		var tde = document.getElementById("tr" + submitID);
		while(tde.hasChildNodes())
		{
			tde.removeChild(tde.firstChild);
		}
		
		inserted[slot][slots[lessonToBeDeleted][4]][slots[lessonToBeDeleted][3]]=false;
		currentSlots--;
		updateRestOfSlots();
	}
}

function updateLessonMenu(id)
{
	var temp = document.getElementById("les" + id);	
	var ulElement = temp.nextSibling.childNodes[2].firstChild;
	while(ulElement.hasChildNodes())
		ulElement.removeChild(ulElement.firstChild);
	var textelement = document.createTextNode("Αριθμός slot: " + slots[id][1] + "/" + slots[id][2]);
	ulElement.appendChild(textelement);
	
	
}

function GetXmlHttpObject()
{
	var objXMLHttp=null;
	if (window.XMLHttpRequest)
	{
		objXMLHttp=new XMLHttpRequest();
	}
	else if (window.ActiveXObject)
	{
		objXMLHttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	return objXMLHttp;
}





