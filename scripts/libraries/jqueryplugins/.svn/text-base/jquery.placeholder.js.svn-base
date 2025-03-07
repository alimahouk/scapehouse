$(document).ready(function() {
	$( function() {

	    $("input[type=text][placeholder],textarea[placeholder]")
	        .each( showPlaceholder )
	        .blur( showPlaceholder )
	        .focus( hidePlaceholder );

	    $("form").submit( function() {
	    	$( "input[type=text][placeholder],textarea[placeholder]", this ).each( hidePlaceholder );
	    } );

	    function showPlaceholder() {
	    	var $control = $(this);
	        var placeholderText = $control.attr("title");
	        if ( $control.val() === "" || $control.val() === placeholderText ) {
	        	$control.addClass("placeholder");
	            $control.val(placeholderText);
	        }
	    };

	    function hidePlaceholder() {
	    	var $control = $(this);
	        if ( $control.val() === $control.attr("title") ) {
	        	$control.removeClass("placeholder");
	        	$control.val("");
	        }
	    }

	});
});