$(document).ready(function() {
	$(document).on('click', '#activate-user', function() {
		var string = $(this).attr('user');
		$.post('post/index/', {'operation': 'activate_user', 'string': string}, function(data) {
			window.location.reload();
		})
	})
	$(document).on('click', '#delete-user', function() {
		var string = $(this).attr('user');
		$.post('post/index/', {'operation': 'delete_user', 'string': string}, function(data) {
			window.location.reload();
		})
	})
	$(document).on('click', '#create-character', function() {
		$.post('post/index/', {'operation': 'create_character', 'username': $('#character-name').val()}, function(data) {
			if (data.indexOf('err') > -1) {
				$.pnotify({
				    title: 'Error',
				    text: data.replace('err;', ''),
				    history: false,
				    type: 'error'
				});
			} else {
				window.location.reload();
			}
		})
	})
})