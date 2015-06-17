//global vars
var step = 1;

//update our status
jQuery( document ).ready(function($){
	$( "ul#status" ).prepend( "<li id='status-" + step + "' style='font-weight: 700;'>Asynchronously iterating through food inspection results&hellip;</li>" );

	

	//mark this step complete
	$("li#status-" + step ).append( "done" ).delay(1000).css( "font-weight", "400" );
	step++;
});