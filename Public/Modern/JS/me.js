$(document).ready(function() {
	$('img[rel=tooltip]').each(function(index) {
		$(this).tooltip();
	})
	$('#show-badges').click(function() {
		if($('#badges').is(':visible')) {
			$('#badges').slideUp();
		} else {
			$('#badges').slideDown();
		}
	})
	$('#show-characters').click(function() {
		if($('#characters').is(':visible')) {
			$('#characters').slideUp();
		} else {
			$('#characters').slideDown();
		}
	})
	$('#start-logout').click(function() {
		$.post('post/leave/', function() {
			window.location.reload();
		})
	})
	$(document).on('click', '#mini-character', function() {
		var string = $(this).attr('user');
		$.post('post/index/', {'operation': 'activate_user', 'string': string}, function(data) {
			window.location.reload();
		})
	})
})