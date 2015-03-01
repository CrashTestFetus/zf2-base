$( document ).ready(function(){
	$(".button-collapse").sideNav({
		activationWidth: 70
	});
	$('.dropdown-button').dropdown({
	    inDuration: 300,
	    outDuration: 225,
	    constrain_width: false, // Does not change width of dropdown to that of the activator
	    hover: false, // Activate on click
	    alignment: 'left', // Aligns dropdown to left or right edge (works with constrain_width)
	    gutter: 0, // Spacing from edge
	    belowOrigin: false // Displays dropdown below the button
	});

//delete modal
	$('.deletemodal-trigger').leanModal({
      dismissible: true, // Modal can be dismissed by clicking outside of the modal
      opacity: .5, // Opacity of modal background
      in_duration: 300, // Transition in duration
      out_duration: 200, // Transition out duration
      ready: function() { alert('Ready'); }, // Callback for Modal open
      complete: function() { alert('Closed'); } // Callback for Modal close
    }
  );
})
var deleteConfirm = function(user, href){
	console.log(user, href);
	var htmlModal = 
	'<div id="del'+user.id+'" class="modal">'+
    	'<div class="modal-content">'+
      		'<h4>Delete User</h4>'+
      		'<p>Delete User "'+user.name+'"?</p>'+
      		'<p>This User will be deleted completely from the database.</p>'+
    	'</div>'+
    	'<div class="modal-footer">'+
      		
			'<a href="'+href+'" class="waves-effect red-text text-darken-2 waves-red btn-flat modal-action modal-close" onClick="document.location = \''+href+'\'" >delete</a>'+
			'<a href="#" class="waves-effect waves-green btn-flat modal-action modal-close" onClick="removeConfirm('+user.id+')">dismiss</a>'+
    	'</div>'+
  	'</div>';
  	$("main").append(htmlModal);
  	$('#del'+user.id).openModal();

	return true;
}
var removeConfirm = function(id){
	setTimeout(function(){
		$('#del'+id).remove();
	},1000)
}