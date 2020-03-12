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
		if($(this).visible( true ) && !$(this).hasClass('loaded')) {
			$(this).attr('srcset', $(this).data('srcset')).removeAttr('data-srcset').addClass('loaded');
		}
	});
	$('.lazy').each(function() {
		if($(this).visible( true ) && !$(this).hasClass('loaded')) {
			$(this).attr('src', $(this).data('src')).removeAttr('data-src').addClass('loaded');
		}	
	});
	
	$(window).on('scroll', function() {
	
		$('.lazyset').each(function() {
			if($(this).visible( true ) && !$(this).hasClass('loaded')) {
				$(this).attr('srcset', $(this).data('srcset')).removeAttr('data-srcset').addClass('loaded');
			}
		});
		$('.lazy').each(function() {
			if($(this).visible( true ) && !$(this).hasClass('loaded')) {
				$(this).attr('src', $(this).data('src')).removeAttr('data-src').addClass('loaded');
			}	
		});

	});

}

function knowledgeFilterToggle()
{
	$('.knowledge__filters').find('.card').on('click', function() {
		if($(window).width() <= 991 && $('.knowledge__filters').hasClass('not-opened')) {
			$('.knowledge__filters').removeClass('not-opened');
		}
	});

	$('.knowledge__filters').find('.closethis').on('click', function() {
		if($(window).width() <= 991) {
			$('.knowledge__filters').addClass('not-opened');
		}
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

function homepageClients()
{
	var $owl = $('.owl-carousel');
	$owl.owlCarousel({
		loop: true,
		margin: 0,
		nav: false,
		dots: false,
		lazyLoad: true,
		autoplay: true,
		autoplayTimeout: 2500,
		autoplayHoverPause: true,
		responsive: {
			0: {
				items: 1
			},
			420: {
				items: 2
			},
			767: {
				items: 3
			},
			991: {
				items: 6
			}
		}
	});

	$('.custom-owl-prev').click(function() {
		$owl.trigger('prev.owl.carousel');
	});
	$('.custom-owl-next').click(function() {
		$owl.trigger('next.owl.carousel');
	});
}

function homeHashtags()
{
	$('.home__middle-hashtags').find('li').on('click', function() {
		$('#homeSearchInput').val($(this).text()).focus();
	});
}

function filterSelect()
{
	$('#filter').find('.filters').find('li').find('input').on('change', function() {
		if($(this).parent().parent().hasClass('active')) {
			$(this).parent().parent().removeClass('active');
			$(this).parent().next().find('li').removeClass('active').find('input').prop('checked', false);
		} else {
			$(this).parent().parent().addClass('active');
		}
	});
	$('#filter').find('.filter-title').on('click', function() {
		if($(this).hasClass('active')) {
			$(this).removeClass('active').parent().removeClass('active');
			$(this).next('.filters').find('li').find('input').prop('checked', false).parent().parent().removeClass('active');
			console.log($(this).next('.filters').find('li').find('input:checked').length);
		} else {
			$(this).addClass('active').parent().addClass('active');
		}
	});
}

function menuMobile()
{
	$('button.mobileMenu').on('click', function() {
		$(this).toggleClass('active');
		$('.navigation__upper').toggleClass('d-block');
	});
}

$(document).ready(function() {
	
	lazyImages();
	uglyInput();
	filterSelect();
	menuMobile();
	knowledgeFilterToggle();

	if($('.home__middle-hashtags').length != 0) {
		homeHashtags();
	}

});

$(window).on('load', function() {
	
	lazyImages();

	if($('.home__clients').length != 0) {
		homepageClients();
	}

});

$(window).on('load resize', function() {
	// if($(window).width() < 768) {
	// 	$('.navigation__menu').addClass('dropdown-menu dropdown-menu-right');
	// } else {
	// 	$('.dropdown-toggle').dropdown('dispose');
	// 	$('.navigation__menu').removeClass('dropdown-menu dropdown-menu-right').removeAttr('style').removeAttr('x-placement');
	// }
});

// jQuery(function($){
// 	$('#filter').submit(function(){
// 		var filter = $('#filter');
// 		$.ajax({
// 			url:filter.attr('action'),
// 			data:filter.serialize(), // form data
// 			type:filter.attr('method'), // POST
// 			beforeSend:function(xhr){
// 				filter.find('button').text('Processing...'); // changing the button label
// 			},
// 			success:function(data){
// 				filter.find('button').text('Filter'); // changing the button label back
// 				$('main.jobs__list-items').html(data); // insert data
// 				$('nav.pagination').trigger('yith-wcan-ajax-filtered');
// 			}
// 		});
// 		return false;
// 	});
// });