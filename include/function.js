var request = makeObject();
function makeObject()
{
	var x;
	var browser = navigator.appName;
	if(browser == "Microsoft Internet Explorer")
	{
		x = new ActiveXObject("Microsoft.XMLHTTP");
	}
	else
	{
		x = new XMLHttpRequest();
	}
	return x;
}
// JavaScript Document


var horizontal_offset="9px" //horizontal offset of hint box from anchor link

/////No further editting needed

var vertical_offset="0" //horizontal offset of hint box from anchor link. No need to change.
var ie=document.all
var ns6=document.getElementById&&!document.all
function format_number(str)
{
	 return str.toFixed(2)
}
function getposOffset(what, offsettype)
{
	var totaloffset=(offsettype=="left")? what.offsetLeft : what.offsetTop;
	var parentEl=what.offsetParent;
	while (parentEl!=null)
	{
		//totaloffset=(offsettype=="left")? totaloffset+parentEl.offsetLeft : totaloffset+parentEl.offsetTop;
		totaloffset=(offsettype=="left")? totaloffset+parentEl.offsetLeft : totaloffset+parentEl.offsetTop;
		parentEl=parentEl.offsetParent;
	}
	return totaloffset;
}

function iecompattest()
{
	return (document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
}

function clearbrowseredge(obj, whichedge)
{
	var edgeoffset=(whichedge=="rightedge")? parseInt(horizontal_offset)*-1 : parseInt(vertical_offset)*-1
	if (whichedge=="rightedge")
	{
		var windowedge=ie && !window.opera? iecompattest().scrollLeft+iecompattest().clientWidth-30 : window.pageXOffset+window.innerWidth-40
		dropmenuobj.contentmeasure=dropmenuobj.offsetWidth
		if (windowedge-dropmenuobj.x < dropmenuobj.contentmeasure)
			edgeoffset=dropmenuobj.contentmeasure+obj.offsetWidth+parseInt(horizontal_offset)
	}
	else
	{
		var windowedge=ie && !window.opera? iecompattest().scrollTop+iecompattest().clientHeight-15 : window.pageYOffset+window.innerHeight-18
		dropmenuobj.contentmeasure=dropmenuobj.offsetHeight
		if (windowedge-dropmenuobj.y < dropmenuobj.contentmeasure)
			edgeoffset=dropmenuobj.contentmeasure-obj.offsetHeight
	}
	return edgeoffset
}

function showhint(menucontents, obj, e, tipwidth)
{
	if ((ie||ns6) && document.getElementById("hintbox"))
	{
		dropmenuobj=document.getElementById("hintbox")
		dropmenuobj.innerHTML=menucontents
		dropmenuobj.style.left=dropmenuobj.style.top=-500
		if (tipwidth!="")
		{
			dropmenuobj.widthobj=dropmenuobj.style
			dropmenuobj.widthobj.width=tipwidth
		}
		dropmenuobj.x=getposOffset(obj, "left")
		dropmenuobj.y=getposOffset(obj, "top")
		//dropmenuobj.style.left=dropmenuobj.x-clearbrowseredge(obj, "rightedge")+obj.offsetWidth+"px"
		dropmenuobj.style.left=dropmenuobj.x-clearbrowseredge(obj, "topedge")+obj.offsetWidth+"px"
		dropmenuobj.style.top=dropmenuobj.y-clearbrowseredge(obj, "bottomedge")+"px"
		dropmenuobj.style.visibility="visible"
		obj.onmouseout=hidetip
	}
}

function hidetip(e)
{
	dropmenuobj.style.visibility="hidden"
	dropmenuobj.style.left="-500px"
}

function createhintbox()
{
	var divblock=document.createElement("div")
	divblock.setAttribute("id", "hintbox")
	document.body.appendChild(divblock)
}

if (window.addEventListener)
	window.addEventListener("load", createhintbox, false)
else if (window.attachEvent)
	window.attachEvent("onload", createhintbox)
else if (document.getElementById)
	window.onload=createhintbox

function space_deduct(str)
{
	var t="";
	var t1=" ";
	if(str=="")
		return t1;
	
	for(ii=0;ii<str.length;ii++)
	{
		if(str.charAt(ii)==" ")
		{
			if(str.charAt(ii+1)!=" ")
			{
				t=t+" "+str.charAt(ii+1);
				ii=ii+1;
			}
		}
		else
			t=t+str.charAt(ii);
	
	}
	return t;
		
	
}



function check_number(str)
{
	var flag=0;	
	
		
	for(ii=0;ii<str.length;ii++)
	{
		if(str.charAt(ii)=="1" || str.charAt(ii)=="2" || str.charAt(ii)=="3" || str.charAt(ii)=="4" || str.charAt(ii)=="5" || str.charAt(ii)=="6" || str.charAt(ii)=="7" || str.charAt(ii)=="8" || str.charAt(ii)=="9" || str.charAt(ii)=="0" || str.charAt(ii)==".")
		{
			
			
		}
		else
			flag=1;
				
				
	}
	if(flag==1)
		return false;
	else	
		return true;
}
function check_phone(str)
{
	var flag=0;	
	
		
	for(ii=0;ii<str.length;ii++)
	{
		if(str.charAt(ii)=="1" || str.charAt(ii)=="2" || str.charAt(ii)=="3" || str.charAt(ii)=="4" || str.charAt(ii)=="5" || str.charAt(ii)=="6" || str.charAt(ii)=="7" || str.charAt(ii)=="8" || str.charAt(ii)=="9" || str.charAt(ii)=="0" || str.charAt(ii)=="+" || str.charAt(ii)=="-" || str.charAt(ii)=="." || str.charAt(ii)==" " || str.charAt(ii)=="(" || str.charAt(ii)==")")
		{
			
			
		}
		else
			flag=1;
				
				
	}
	if(flag==1)
		return false;
	else	
		return true;
}/* TWO STEPS TO INSTALL EMAIL VALIDATION - BASIC:

  1.  Copy the coding into the HEAD of your HTML document
  2.  Add the last code into the BODY of your HTML document  -->

<!-- STEP ONE: Paste this code into the HEAD of your HTML document  -->

<HEAD>

<SCRIPT LANGUAGE="JavaScript">

<!-- This script and many more are available free online at -->
<!-- The JavaScript Source!! http://javascript.internet.com -->

<!-- Begin*/
function check_email(str) 
{
	if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(str))
	{
		return (true)
	}
	
	return (false)
}

function check_url(str)
{
	
	if(/^[A-Za-z]+:\/\/www\.[A-Za-z0-9-]+\.[A-Za-z0-9]+/.test(str))
	{
		return (true)
	}
	return false;
	
}

<!-- Original:  Sandeep V. Tamhankar (stamhankar@hotmail.com) -->

<!-- This script and many more are available free online at -->
<!-- The JavaScript Source!! http://javascript.internet.com -->

<!-- Begin
function isValidDate(dateStr) 
{
	// Checks for the following valid date formats:
	// MM/DD/YY   MM/DD/YYYY   MM-DD-YY   MM-DD-YYYY
	// Also separates date into month, day, and year variables
	
	var datePat = /^(\d{1,2})(\/|-)(\d{1,2})\2(\d{2}|\d{4})$/;
	
	// To require a 4 digit year entry, use this line instead:
	// var datePat = /^(\d{1,2})(\/|-)(\d{1,2})\2(\d{4})$/;

	var matchArray = dateStr.match(datePat); // is the format ok?
	if (matchArray == null) 
	{
		alert("Please enter the date")
		return false;
	}
	/*month = matchArray[1]; // parse date into variables
	day = matchArray[3];
	year = matchArray[4];*/
	day = matchArray[1]; // parse date into variables
	month = matchArray[3];
	year = matchArray[4];
	if (month < 1 || month > 12) 
	{ // check month range
		alert("Please enter the date");
		return false;
	}
	if (day < 1 || day > 31) 
	{
		alert("Please enter the date");
		return false;
	}
	if ((month==4 || month==6 || month==9 || month==11) && day==31) 
	{
		alert("Please enter the date")
		return false
	}
	if (month == 2) 
	{ // check for february 29th
		var isleap = (year % 4 == 0 && (year % 100 != 0 || year % 400 == 0));
		if (day>29 || (day==29 && !isleap)) 
		{
			alert("Please enter the date");
			return false;
	  }
	}
	return true;  // date is valid
}
function valid_file_format(file,field)
{
		x = file.split(",");
		flag = 0;
		for(ii=0;ii<x.length;ii++)
		{
			if(field.indexOf(x[ii])!= -1 || field.indexOf(x[ii].toUpperCase())!= -1)
			{
				
				flag = 1
				
			}
			
		}
		if(flag == 1)
			return true;
		else
			return false;
}
//  End -->


function addBookmark(title,url) 
{
	if(title=="")
		title = document.title;	
	if (window.sidebar) 
	{ 
		window.sidebar.addPanel(title, url,""); 
	} 
	else if( document.all ) 
	{
		window.external.AddFavorite( url, title);
	} 
	else if(window.opera && window.print ) 
	{
		return true;
	}
}
	