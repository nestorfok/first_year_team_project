/*****
 * This Javscript file uses the toggle button state to display data
 *
 */


// get Tab nav Child Node
var tab_nav = document.querySelector("#tab_nav").querySelectorAll("div");
// get tab context Node
var tab_context = document.querySelector(".context").querySelectorAll(".tab_child_context");

for (var i = 0; i<tab_nav.length; i++){
     // the index value is click id
     (function(index){
          tab_nav[i].addEventListener("click",(e)=>{
               for (var j = 0; j<tab_nav.length; j++){
                    tab_nav[j].className = "";
                    tab_context[j].className = "tab_child_context hiden";
               }
               tab_nav[index].className = "tabl-click";
               tab_context[index].className = "tab_child_context show";
          },false);
     })(i);
}