// JavaScript Document

function changeType(type){
	document.getElementById('type_select').value=type;
	document.getElementById('category_select').value="-1";
	showResults();
}
function changeCategory(category){
	document.getElementById('category_select').value=category;
	document.getElementById('type_select').value="-1";
	showResults();
}
function showResults(){
	
	var category = document.getElementById('category_select').value;
	var type = document.getElementById('type_select').value;
	var essence = document.getElementById('essence_select').value;
	var pricerange = document.getElementById('pricerange_select').value;
	
	document.getElementById("products").innerHTML="";
	if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	} else {  // code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
		  document.getElementById("products").innerHTML=xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET","getResults.php"+window.location.search+"&category="+category+"&type="+type+"&essence="+essence+"&pricerange="+pricerange,true);
	xmlhttp.send();
	
}



