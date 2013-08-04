$(document).ready(function() {
	$(document).on('click', '#start-register', function() {
		$.post('post/index/', {'operation': 'start_register'}, function(data) {
			$('#main-form').slideUp(function() {
				$(this).html(data);
				$(this).slideDown();
			})
		})
	})
	$(document).on('click', '#fallback_procedure', function() {
		$.post('post/index/', {'operation': 'start_fallback'}, function(data) {
			$('#main-form').slideUp(function() {
				$(this).html(data);
				$(this).slideDown();
			})
		})
	})
	$(document).on('click', '#continue-register', function() {
		$.post('post/index/', {'operation': 'continue_register', 'key': $('#register-beta-key').val()}, function(data) {
			if (data == 'err') {
				$.pnotify({
				    title: 'Error',
				    text: 'The BETA key provided is incorrect!',
				    history: false,
				    type: 'error'
				});
			} else {
				$('#main-form').slideUp(function() {
					$(this).html(data);
					$(this).slideDown();
				})
			}
		})
	})
	$(document).on('click', '#show-login', function() {
		$.post('post/index/', {'operation': 'show_login'}, function(data) {
			$('#main-form').slideUp(function() {
				$(this).html(data);
				$(this).slideDown();
			})
		})
	})
	$(document).on('click', '#finish-register', function() {
		$.post('post/index/', {
			'operation': 'finish_register', 
			'email': $('#register-email').val(), 
			'password': $('#register-password').val()}, function(data) {
			if (data.indexOf('err') > -1) {
				$.pnotify({
				    title: 'Error',
				    text: data.replace('err;', ''),
				    history: false,
				    type: 'error'
				});
			} else {
				$('#main-form').fadeOut(function() {
					$(this).html(data);
					$(this).fadeIn();
				})
			}
		})
	})
	$(document).on('click', '#show-characters', function() {
		$.post('post/index/', {'operation': 'show_characters'}, function(data) {
			$('#main-form').slideUp(function() {
				$(this).html(data);
				$(this).slideDown();
			})
		})
	})
	$(document).on('click', '#login-submit', function() {
		$.post('post/index/', {'operation': 'start_login', 'sec_key': $('#login-secret-key').val()}, function(data) {
			if (data.indexOf('err') > -1) {
				$.pnotify({
				    title: 'Error',
				    text: data.replace('err;', ''),
				    history: false,
				    type: 'error'
				});
			} else {
				$('#main-form').fadeOut(function() {
					$(this).html(data);
					$(this).fadeIn();
				})
			}
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
				$('#main-form').fadeOut(function() {
					$(this).html(data);
					$(this).fadeIn();
				})
			}
		})
	})

	$(document).on('keypress', '#login-password', function(e) {
		if (e.keyCode == 13) {
			$.post('post/index/', {
				'operation': 'finish_fallback', 
				'email': $('#login-email').val(), 
				'password': $('#login-password').val()}, function(data) {
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
		}
	})
	
	$(document).on('click', '#finish-fallback', function() {
		$.post('post/index/', {
			'operation': 'finish_fallback', 
			'email': $('#login-email').val(), 
			'password': $('#login-password').val()}, function(data) {
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