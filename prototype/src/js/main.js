'use strict';

(function($) {
	$.each(['show', 'hide'], function(i, ev) {
		var el = $.fn[ev];
		$.fn[ev] = function() {
			this.trigger(ev);
			return el.apply(this, arguments);
		};
	});
})(jQuery);

function slideTo(el)
{
	$('html, body').animate({
		scrollTop: $(el).offset().top
	}, 500);
}

function lazyImages()
{

	$('.lazyset').each(function() {
		if($(this).visible( true, true ) && !$(this).hasClass('loaded')) {
			$(this).attr('srcset', $(this).data('srcset')).removeAttr('data-srcset').addClass('loaded');
		}
	});
	$('.lazy').each(function() {
		if($(this).visible( true, true ) && !$(this).hasClass('loaded')) {
			$(this).attr('src', $(this).data('src')).removeAttr('data-src').addClass('loaded');
		}	
	});
	
	$(window).on('scroll', function() {
	
		$('.lazyset').each(function() {
			if($(this).visible( true, true ) && !$(this).hasClass('loaded')) {
				$(this).attr('srcset', $(this).data('srcset')).removeAttr('data-srcset').addClass('loaded');
			}
		});
		$('.lazy').each(function() {
			if($(this).visible( true, true ) && !$(this).hasClass('loaded')) {
				$(this).attr('src', $(this).data('src')).removeAttr('data-src').addClass('loaded');
			}	
		});

	});

}

function uglyInput() 
{
	$('.ugly').each(function() {
		var $input = $(this).find('input, textarea');
		$input.on('change', function() {
			if(!$input.val()) {
				$input.next('label').css({'opacity':1});
			} else {
				$input.next('label').css({'opacity':0});
			}
		});
	});
}

function chatClose()
{
	$('#chat').addClass('closed');
}


$(document).ready(function() {
	uglyInput();
});

$(window).on('load', function() {
	lazyImages();
});

$(window).on('load resize', function() {
	// if($(window).width() < 768) {
	// 	$('.navigation__menu').addClass('dropdown-menu dropdown-menu-right');
	// } else {
	// 	$('.dropdown-toggle').dropdown('dispose');
	// 	$('.navigation__menu').removeClass('dropdown-menu dropdown-menu-right').removeAttr('style').removeAttr('x-placement');
	// }
});