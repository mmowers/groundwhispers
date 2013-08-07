var timeoutVar;
if( navigator.geolocation ){
  var timeoutVal = 20 * 1000;
  // Call getCurrentPosition with success and failure callbacks
  navigator.geolocation.getCurrentPosition( success, fail, { enableHighAccuracy: true, timeout: timeoutVal, maximumAge: 0 });
  //navigator.geolocation.watchPosition( success, fail, { enableHighAccuracy: true, timeout: timeoutVal, maximumAge: 0 });
}
else{
  alert("Sorry, geolocation is not supported by this browser :(");
}            

//success and fail functions for navigator.geolocation.getCurrentPosition()
function success(position){
  //clearTimeout(timeoutVar);
  //timeoutVar = setTimeout(function() {
    //if we get the position, send an ajax request for the list of nearby posts.
    //I'm not using jquery here because this file is loaded before jquery is, and
    //i wanted this to happen asap.
    var roundnum = 1000000; //rounding to 6 decimal places
    var lat = Math.round(position.coords.latitude*roundnum)/roundnum;
    var lng = Math.round(position.coords.longitude*roundnum)/roundnum;
    //the following lines are needed for returning an updated post list after ajax voting
    document.getElementById("curlatitude").value = lat;
    document.getElementById("curlongitude").value = lng;

    var sortby = document.getElementById("sortby").value;
    var xmlhttp;
    if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
      xmlhttp=new XMLHttpRequest();
    }
    else
      {// code for IE6, IE5
      xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
      }
    xmlhttp.onreadystatechange=function(){
      if (xmlhttp.readyState==4 && xmlhttp.status==200)
        {
        document.getElementById("postlist").innerHTML=xmlhttp.responseText;
        }
      else
        {
        document.getElementById("postlist").innerHTML='Still loading list...';
        }
    }
    document.getElementById("postlist").innerHTML='Loading list...';
    xmlhttp.open("GET",BASE+'/getposts?latitude='+lat+'&longitude='+lng+'&sortby='+sortby,true);
    xmlhttp.send();  
    
    //another way, like what i did with Drupal, is filling out and submitting a form, which prevents the rest of the page from loading
    //manipulating forms with javascript instead of jquery: to submit form with id of "formid", document.forms["formid"].submit();
    //to change an input named "inputname", document.forms["formid"].inputname.value="does this work?";
  //}, 1500);

}
function fail(error){
  var errors = { 
    1: 'Sorry, there was a problem getting your location. You may need to enable location services on your browser.',
    2: 'Sorry, your current position is unavailable.',
    3: 'Sorry, there was a problem getting your location. You may need to enable location services on your browser.'
  };
  alert(errors[error.code]);
}
