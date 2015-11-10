document.onmousemove=checkselection;
var up;
var upid;
var down;
var downid;
var xmlHttp;
function lesmouseup(context) {
	up = null;
	down = null;
}

function lesmousedown(context) {
	down = context;
	downid = down.id.substring(3);
	if(slots[downid]!=null)
	{
		if(slots[downid][1]>=slots[downid][2])
		{
			down=null;
			downid=null;
			alert("Ο απαιτούμενος αριθμός εισαγωγών έχει συμπληρωθεί.");
			return;
		}
	}
	for(var i=1;i<=30;i++)
	{
		document.getElementById("slo" + i).removeAttribute("style");
	}
	if(prohibs[downid]!=null)
	{
		for(var i=0;i<prohibs[downid].length;i++)
		{
			if(prohibs[downid][i]==1)
				document.getElementById("slo" + i).setAttribute("style","background:#FF0000;");
		}
	}
}

function slomouseup(context) {
	up = context;
	upid = up.id.substring(3);
	clearSelection();
	if (down == null)
		return;
	var slot = upid;
	var lesson = downid;
	var elemen = up.getElementsByTagName("input");
	for ( var i = 0; i < elemen.length; i++) {
		if (elemen[i].name == "lesson") {
			elemen[i].value = lesson;
		}
	}
	var lightbox=document.getElementById("lightbox" + slot);
	if(lightbox==null)
		return;
	
	//Check if it's allowed to enter this lesson into this slot
	if(prohibs[lesson]!=null)
	{
		if(prohibs[lesson][slot]==1)
		{
			up=null;
			upid=null;
			down=null;
			downid=null;
			alert("Δεν επιτρέπεται να μπει αυτό το μάθημα σε αυτό το slot");
			
			return;
		}
	}
	
	//check if there is already lesson of the same department and semester
		var lessonSemester = slots[lesson][3];
		var lessonDepartment = slots[lesson][4];
		var contains = false;
		if(isArray(inserted[slot]))
		{
			var curSlot=inserted[slot];
			if(isArray(curSlot[lessonDepartment]))
			{
				var curDepart = curSlot[lessonDepartment];
				if(curDepart[lessonSemester]==true)
				{
					contains=true;
				}
			}
		}
		if(contains)
		{
			var resultCon = confirm ("Υπάρχει ήδη μάθημα του ίδου τμήματος και του ίδιου εξαμήνου στο ίδιο slot.");
			if(!resultCon)
				return;
		}

	document.getElementById("lightbox" + slot).setAttribute("style", "display:block;");
	document.getElementById("overlay").setAttribute("style", "display:block;");
	
	
	
	xmlHttp = GetXmlHttpObject();
	if (xmlHttp==null)
	{
		alert ("Browser does not support HTTP Request");
		return;
	}
	
	
	var schedule;
	var elemen = up.getElementsByTagName("input");
	for ( var i = 0; i < elemen.length; i++) {
		if (elemen[i].name == "schedule") {
			schedule = elemen[i].value;
		}
	}

	
	
	var url="actions/availableRooms.php";
	url=url+"?slot=" + slot + "&schedule=" + schedule + "&lesson=" + lesson ;
	xmlHttp.onreadystatechange=stateAvailableChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);	
}

function stateAvailableChanged()
{
	var form= document.getElementById("lightbox" + upid);
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
	{
		var text = xmlHttp.responseText;
		var rooms = text.split(",");
		for(var x = 0 ; x<rooms.length;x++)
		{
			var roomstr = rooms[x];
			if(roomstr.length>2)
			{
				var roomats = roomstr.split(":");
				var newelem = document.createElement('input');
				newelem.setAttribute("name","room");
				newelem.setAttribute("type","radio");
				newelem.setAttribute("value",roomats[0]);
				var text = document.createTextNode(roomats[1]);
				form.appendChild(newelem);
				form.appendChild(text);		
				form.appendChild(document.createElement('br'));		
			}
		}
		
		
				
		var formelem = form.getElementsByTagName("input");
		for ( var i = 0; i < formelem.length; i++) 
		{
			var inputatt = formelem[i].attributes;
			for(var x=0;x<inputatt.length;x++)
			{
				if(inputatt[x].value=="text")
				{
					form.appendChild(formelem[i]);
				}
			}
		}
		for ( var i = 0; i < formelem.length; i++) 
		{
			var inputatt = formelem[i].attributes;
			for(var x=0;x<inputatt.length;x++)
			{
				if(inputatt[x].value=="submit")
				{
					form.appendChild(formelem[i]);
				}
			}
		}
		for ( var i = 0; i < formelem.length; i++) 
		{
			var inputatt = formelem[i].attributes;
			for(var x=0;x<inputatt.length;x++)
			{
				if(inputatt[x].value=="reset")
				{
					form.appendChild(formelem[i]);
				}
			}
		}

	}
}

function cancel()
{
	document.getElementById("overlay").setAttribute("style", "display:none;");
	var lightbox = document.getElementById("lightbox" + upid);

	var elemen = up.getElementsByTagName("input");
	for ( var i = 0; i < elemen.length; i++) {
		if (elemen[i].name == "lesson") {
			elemen[i].removeAttribute("value");
		}
	}
	
	var submit;
	var reset;
	var text;
	while(lightbox.hasChildNodes())
	{
		var  removed = lightbox.removeChild(lightbox.firstChild);
		if(removed.nodeName=="INPUT")
		{
			if(removed.getAttribute("type")=="submit")
				submit=removed;
			if(removed.getAttribute("type")=="reset")
				reset=removed;
			if(removed.getAttribute("type")=="text")
				text=removed;
		}
	}
	lightbox.appendChild(text);
	lightbox.appendChild(submit);
	lightbox.appendChild(reset);
	lightbox.setAttribute("style", "display:none;");
	up=null;
	upid=null;
	down=null;
	downid=null;
}

function slomousedown(context) {
	up=null;
	upid=null;
	down=null;
	downid=null;
}

function clearSelection() {
	if (document.selection)
		document.selection.empty();
	else if (window.getSelection)
		window.getSelection().removeAllRanges();
}
function checkselection()
{
	if(down!=null)
		clearSelection();
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






































var submitHttp;
var slesson;
var sroom;
var sscheduleid;
var sslot;
var scomments;

function dosubmit(context)
{
	if (!ValidateSchedForm(context))
		return false;
	for(var i=0;i<context.childNodes.length;i++)
	{
		var curNode = (context.childNodes)[i];
		if(curNode.nodeName=="INPUT")
		{
		if(curNode.getAttributeNode("name").value=="lesson")
			this.slesson = curNode.getAttributeNode("value").value;
		if(curNode.getAttributeNode("name").value=="slot")
			this.sslot = curNode.getAttributeNode("value").value;
		if(curNode.getAttributeNode("name").value=="schedule")
			this.sscheduleid = curNode.getAttributeNode("value").value;		
		}
	}
	
	var lightboxelements = document.getElementById("lightbox" + sslot);
	for(var i=0;i<lightboxelements.childNodes.length;i++)
	{
		
		var curNode = (lightboxelements.childNodes)[i];
		
		if(curNode.nodeName=="INPUT")
		{
			if(curNode.getAttributeNode("name")!=null)
			{
				if(curNode.getAttributeNode("name").value=="comments")
				{
					scomments = curNode.value;
				}
			}
			if(curNode.checked==true)
				sroom = curNode.getAttributeNode("value").value;
		}
	}
	

	submitHttp = GetXmlHttpObject();
	var url="actions/submit.php";
	var params="slot=" + sslot + "&schedule=" + sscheduleid + "&lesson=" + slesson + "&room=" + sroom + "&comments=" + this.scomments;
	submitHttp.onreadystatechange=lessonAddedToSlot;
	submitHttp.open("GET",url + "?" + params,true);
	//submitHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded;");
	submitHttp.setRequestHeader("Content-Type", "text/html; charset=UTF-8");
	submitHttp.setRequestHeader("Connection", "close");
	submitHttp.send(null);	
	return false;
}

function lessonAddedToSlot()
{
	if (submitHttp.readyState==4 || submitHttp.readyState=="complete")
	{
		var submitid = submitHttp.responseText;
		var tdElem = document.getElementById("slo" + sslot);
		var containsTable = false;
		var tableElement;
		for(var i=0;i<tdElem.childNodes.length;i++)
		{
			if(tdElem.childNodes[i].nodeType=="TABLE")
			{
				containsTable=true;
				tableElement = tdElem.childNodes[i];
				break;
			}
		}
		
		if(!containsTable)
		{
			tableElement = document.createElement("table");
			tableElement.setAttribute("class","innerSchedule");
			tdElem.appendChild(tableElement);
		}
		
		
		
		
		var temp;
		var trtemp = document.createElement("tr");
		trtemp.setAttribute("id","tr"+submitid);
		
		
		var tdelemen = document.createElement("td");
		tdelemen.setAttribute("class","innertd");
		var bgcolor;
		var semester = slots[slesson][3];
		if(semester==1||semester==2)
			bgcolor = bgcolor1;
		if(semester==3||semester==4)
			bgcolor = bgcolor2;
		if(semester==5||semester==6)
			bgcolor = bgcolor3;
		if(semester==7||semester==8)
			bgcolor = bgcolor4;
		tdelemen.setAttribute("bgcolor",bgcolor);
		
		var divelement = document.createElement("div");
		var imgelement = document.createElement("img");
		imgelement.setAttribute("src","styles/images/pin.png");
		divelement.appendChild(imgelement);
		tdelemen.appendChild(divelement);
		temp = document.createTextNode(slots[slesson][5]);
		tdelemen.appendChild(temp);
		tdelemen.appendChild(document.createElement("br"));
		temp = document.createTextNode(rooms[sroom]);
		tdelemen.appendChild(temp);
		tdelemen.appendChild(document.createElement("br"));
		
		var comdiv = document.createElement("div");
		comdiv.setAttribute("class","comments");
		var comtext = document.createTextNode(scomments);
		comdiv.appendChild(comtext);
		tdelemen.appendChild(comdiv);
		
		temp = document.createElement("a");
		temp.setAttribute("href","#a");
		temp.setAttribute("onclick","confirmation(" + submitid + "," + sslot + "," + sscheduleid + "," + slesson + ")");
		temp.appendChild(document.createTextNode("X"));
		tdelemen.appendChild(temp);
		
		trtemp.appendChild(tdelemen);
		tableElement.appendChild(trtemp);
		
		
		
		
		
		//clear rubbish!
		
		document.getElementById("overlay").setAttribute("style", "display:none;");
		var lightbox = document.getElementById("lightbox" + sslot);

		var elemen = up.getElementsByTagName("input");
		for ( var i = 0; i < elemen.length; i++) {
			if (elemen[i].name == "lesson") {
				elemen[i].removeAttribute("value");
			}
		}
		
		var submit;
		var reset;
		var commentinput = document.createElement("input");
		commentinput.setAttribute("type","text");
		commentinput.setAttribute("value","");
		commentinput.setAttribute("name","comments");
		while(lightbox.hasChildNodes())
		{
			var  removed = lightbox.removeChild(lightbox.firstChild);
			if(removed.nodeName=="INPUT")
			{
				if(removed.getAttribute("type")=="submit")
					submit=removed;
				if(removed.getAttribute("type")=="reset")
					reset=removed;
			}
		}
		lightbox.appendChild(commentinput);
		lightbox.appendChild(submit);
		lightbox.appendChild(reset);
		lightbox.setAttribute("style", "display:none;");
		up=null;
		upid=null;
		down=null;
		downid=null;
		
		
		
		
		
		//Update variables
		slots[slesson][1] = slots[slesson][1] + 1 ;
		updateLessonMenu(slesson);
		
		
		
		if(!isArray(inserted[sslot]))
			inserted[sslot] = new Array();
		var curslot = inserted[sslot];
		if(!isArray(curslot[slesson[4]]))
			curslot[slots[slesson][4]] = new Array();
		var curdepart = curslot[slots[slesson][4]];
		curdepart[slots[slesson][3]]=true;
		currentSlots++;
		updateRestOfSlots();
		
		
	}
}



function updateRestOfSlots()
{
	var atag = document.getElementById("restSlots");
	atag.removeChild(atag.firstChild);
	var texttag = document.createTextNode("Συμπληρωμένα Slots: " + currentSlots + " / " + totalSlots);
	atag.appendChild(texttag);
}
