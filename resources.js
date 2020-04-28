//////////////////////////////////////////////////
// THIS IS A FILE TO KEEP ANY JS FUNCTIONS THAT //
// NEED TO BE REFERENCED BY MULTIPLE FILES      //
//////////////////////////////////////////////////

// Simple helper method to construct the 2D array
function initArray(nrows, ncols) {
    let array = new Array(nrows);
    for (let i=0; i<nrows; i++) {
      array[i] = new Array(ncols);
    }
    return array;
}

// Get a given cookie by name
// Credit: https://www.w3schools.com/js/js_cookies.asp
function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
        c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
        return c.substring(name.length, c.length);
        }
    }
    return "";
}