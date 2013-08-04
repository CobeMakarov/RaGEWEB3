var chromide = {
	online : 0,
	updateOnline : function() {
		$.post('post/online/', {'operation': 'get_online_count'}, function(data) {
			if (chromide.online == 0) {
				$('#full_online_text').fadeOut(function() {
					$('#full_online_text').html('<div id="online_count" style="display: inline-block;">' + data + '</div> <b>HC</b>s online!');
					$('#full_online_text').fadeIn();
				})			
			} else {
				if (chromide.online != data) {
					$('#online_count').slideUp('slow', function() {
						$('#online_count').html(data);
						$('#online_count').slideDown('slow');
					})
				}
			}

			chromide.online = data;
		})
	}
}

$(document).ready(function() {
	chromide.updateOnline();
	setInterval(function() {
		chromide.updateOnline();
	}, 5000);

	$('#show_twitter_modal').click(function() {
		$('body').append(twitter_dev_modal);

		$('#twitter_dev_modal').modal('show');
	})

	setTimeout(function() {
		$('#frontpage-img').fadeIn('slow');
	}, 1500)
})

