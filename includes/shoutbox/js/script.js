/* Author: staticfloat.com

*/

$(document).ready(function() {

$('#shoutbox_form').submit(function() {
var form = $(this);
var data = form.serialize();

$.getJSON(form.attr('action'), data, function(result) {
	if(!result) return;

	var res_container = '<div id="shoutbox_entry_'+result['id']+'" class="entry" style="display:none;" data-date="'+result['dateraw']+'">';
		res_container += '<div class="header">'+result['username']+' gerade eben</div>';
		res_container += '<div class="text">';
		res_container += result['message'];
		res_container += '</div>';
		res_container += '</div>';

	$('.simplebar-content').prepend(res_container);
	$('#shoutbox_entry_'+result['id']).slideDown('slow');
	$('#message').val('');

	/*$("#shoutbox_content").customScrollbar({
		resize: true,
		keepPosition: true,
		updateOnWindowResize: true
	});*/

});

return false;

});
// 10000 steht für 10 Sekunden
window.setInterval('autoRefresh()', 10000);

});


//Auto Refresh
function autoRefresh() {
	var last_entry = {
		              date: $('.entry').first().attr('data-date'),
	                  id: $('.entry').first().attr('id')
	                 };

	$.getJSON('includes/shoutbox/includes/shoutbox_refresh.php', {lastEntry: last_entry.date}, function(result) {
		if(!result || last_entry.id === 'shoutbox_entry_'+result['id']) return;


		var res_container = '<div id="shoutbox_entry_'+result['id']+'" class="entry" style="display:none;" data-date="'+result['dateraw']+'">';
		res_container += '<div class="header">'+result['username']+' gerade eben</div>';
		res_container += '<div class="text">';
		res_container += result['message'];
		res_container += '</div>';
		res_container += '</div>';

		$('.simplebar-content').prepend(res_container);
		$('#shoutbox_entry_'+result['id']).slideDown('slow');

		/*$("#shoutbox_content").customScrollbar({
		resize: true,
		keepPosition: true,
		updateOnWindowResize: true
	});*/

	});
}
