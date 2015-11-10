
function IsEmpty(aTextField) {
   if ((aTextField.value.length==0) ||
   (aTextField.value==null)) {
      return true;
   }
   else { return false; }
}

function IsNegative(aTextField) {
   if (aTextField.value<0)
   {
      return true;
   }
   else { return false; }
}

function ValidateSchedForm(form)
{
	var checked = false; 
	var buttons = form.elements.room; 
	for (var i=0; i<buttons.length; i++)  
	{  
		if (buttons[i].checked) 
		{  
			checked = true; 
			break;  
		}  
	} 
	if(!checked) 
		alert("Δεν έχετε επιλέξει αίθουσα");  
	return checked ; 
}

function ValidateForm2(form)
{

   if(IsEmpty(form.size))
   {
      alert('Δεν έχετε εισάγει μέγεθος')
      form.size.focus();
      return false;
   }
   if(IsNegative(form.size))
   {
      alert('Έχετε εισάγει αρνητικό μέγεθος')
      form.size.focus();
      return false;
   }
   return true;


}
function ValidateForm(form)
{
  
   
   if(IsEmpty(form.title))
   { 
      alert('Δεν έχετε εισάγει Τίτλο')
      form.title.focus();
      return false; 
   } 
    
   if(IsEmpty(form.size))
   { 
      alert('Δεν έχετε εισάγει μέγεθος')
      form.size.focus();
      return false; 
   } 
   
   if(IsEmpty(form.dep_id))
   { 
      alert('Δεν έχετε εισάγει τμήμα')
      form.dep_id.focus();
      return false; 
   } 
   
   if(IsEmpty(form.id))
   { 
      alert('Δεν έχετε εισάγει id')
      form.id.focus();
      return false; 
   } 
   
   if(IsEmpty(form.comment))
   { 
      alert('Δεν έχετε εισάγει σχόλια')
      form.comment.focus();
      return false; 
   } 
   
   if(IsEmpty(form.professor))
   { 
      alert('Δεν έχετε εισάγει καθηγητή')
      form.city.focus(); 
      return false; 
   } 

   return true;
 
} 


function isArray(obj) {
		if(obj==null)
			return false;
	   if (obj.constructor.toString().indexOf("Array") == -1)
	      return false;
	   else
	      return true;
	}



