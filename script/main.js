pre_className = '';
function listover(obj){
	pre_className = obj.className;
	obj.className = "listover";
}

function listout(obj){
	obj.className = pre_className;
	pre_className = obj.className;
}




/* Call from <form> tag: onSubmit='disable_submit_button(this)' */
function disable_submit_button(form_obj)
{
  /* form.elements:
   * Retrieves a collection, in source order, of all controls in a given form.
   * input type=image objects are excluded from the collection. 
   */
  for(i=0; i<form_obj.elements.length; i++) {
    if(form_obj.elements[i].type=="submit") {
      form_obj.elements[i].disabled=true;
    }
  }
}



