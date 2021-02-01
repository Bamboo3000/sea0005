function onFormSubmit()
{
	$(document).on('submit', '#job-application-form, #cv-upload-form', function() {
		$(this).addClass('disabled').find('input[type="submit"]').attr("disabled", true).addClass('disabled');
		if( $(this).attr('id') === 'job-application-form' ) {
			setCookie('jobid_'+$(this).find('input.app-jobid').val(), $(this).find('input.app-jobid').val(), 365);
		} else if( $(this).attr('id') === 'cv-upload-form' ) {
			setCookie('cvform', 'sent', 365);
		}
	});
}

function onFormLoad()
{
	var $jobid = $('#job-application-form').find('input.app-jobid').val();
	var $cookie = getCookie('jobid_'+$jobid);

	if(getCookie('cvform')) {
		$('#cv-upload-form').addClass('cv-sent');
	}

	if($jobid !== 188 && $jobid == $cookie) {
		$('#job-application-form').addClass('application-sent');
	}

}

function appValidation()
{

	if( $('#job-application-form').length > 0 ) {
		var formApp = $('#job-application-form');
		formApp.validate({
			onfocusout: true,
			focusInvalid: true,
			focusCleanup: true,
			onkeyup: false
		});
		formApp.find('input.required').on('change focusout', function() {
			if( formApp.valid() ) {
				$('.fake_btn_app').addClass('d-none');
				$('.g-recaptcha.app').removeClass('d-none');
			} else {
				$('.fake_btn_app').removeClass('d-none');
				$('.g-recaptcha.app').addClass('d-none');
			}
		});
		formApp.find('.fake_btn_app').on('click', function (e) {
			e.preventDefault();
			if( formApp.valid() ) {
				$('.fake_btn_app').addClass('d-none');
				$('.g-recaptcha.app').removeClass('d-none');
				$(this).next('button.g-recaptcha.app').trigger('click').remove();
			}
		});
	}

	if( $('#cv-upload-form').length > 0 ) {
		var formCV = $('#cv-upload-form');
		formCV.validate({
			onfocusout: true,
			focusInvalid: true,
			focusCleanup: true,
			onkeyup: false
		});
		formCV.find('input.required').on('change focusout', function() {
			if( formCV.valid() ) {
				$('.fake_btn_cv').addClass('d-none');
				$('.g-recaptcha.cvBTN').removeClass('d-none');
			} else {
				$('.fake_btn_cv').removeClass('d-none');
				$('.g-recaptcha.cvBTN').addClass('d-none');
			}
		});
		formCV.find('.fake_btn_cv').on('click', function (e) {
			e.preventDefault();
			if( formCV.valid() ) {
				$('.fake_btn_cv').addClass('d-none');
				$('.g-recaptcha.cvBTN').removeClass('d-none');
				$(this).next('button.g-recaptcha.cvBTN').trigger('click').remove();
			}
		});
	}

	if( $('#subscribe-popup-form').length > 0 ) {
		var formSub = $('#subscribe-popup-form');
		formSub.validate({
			onfocusout: true,
			focusInvalid: true,
			focusCleanup: true,
			onkeyup: false
		});
		formSub.find('input.required').on('change focusout', function() {
			if( formSub.valid() ) {
				$('.fake_btn_subscribe').addClass('d-none');
				$('.g-recaptcha.subscribe').removeClass('d-none');
			} else {
				$('.fake_btn_subscribe').removeClass('d-none');
				$('.g-recaptcha.subscribe').addClass('d-none');
			}
		});
		formSub.find('.fake_btn_subscribe').on('click', function (e) {
			e.preventDefault();
			if( formSub.valid() ) {
				$('.fake_btn_subscribe').addClass('d-none');
				$('.g-recaptcha.subscribe').removeClass('d-none');
				$(this).next('button.g-recaptcha.subscribe').trigger('click').remove();
			}
		});
	}

	if( $('#contact-popup-form').length > 0 ) {
		var formCon = $('#contact-popup-form');
		formCon.validate({
			onfocusout: true,
			focusInvalid: true,
			focusCleanup: true,
			onkeyup: false
		});
		formCon.find('input.required').on('change focusout', function() {
			if( formCon.valid() ) {
				$('.fake_btn_contact').addClass('d-none');
				$('.g-recaptcha.contactF').removeClass('d-none');
			} else {
				$('.fake_btn_contact').removeClass('d-none');
				$('.g-recaptcha.contactF').addClass('d-none');
			}
		});
		formCon.find('.fake_btn_contact').on('click', function (e) {
			e.preventDefault();
			if( formCon.valid() ) {
				$('.fake_btn_contact').addClass('d-none');
				$('.g-recaptcha.contactF').removeClass('d-none');
				$(this).next('button.g-recaptcha.contactF').trigger('click').remove();
			}
		});
	}

}

function onAppSubmit(token)
{
	var $globHash = getCookie('appHash');
	var $formHash = document.getElementById('jobUploadHash').value;
	if( $globHash === $formHash ) {
		setCookie('appHash', 'false', 1);
		$('#job-application-form').find('button.g-recaptcha').remove();
		$('#job-application-form').find('button.fake_btn_app').addClass('d-none');
		$('#job-application-form').find('button.fake_btn_app_loading').removeClass('d-none');
		document.getElementById('job-application-form').submit();
	}
}

function onCVSubmit(token)
{
	var $globHash = getCookie('cvHash');
	var $formHash = document.getElementById('cvUploadHash').value;
	if( $globHash === $formHash ) {
		setCookie('cvHash', 'false', 1);
		$('#cv-upload-form').find('button.g-recaptcha').remove();
		$('#cv-upload-form').find('button.fake_btn_cv').addClass('d-none');
		$('#cv-upload-form').find('button.fake_btn_cv_loading').removeClass('d-none');
		document.getElementById('cv-upload-form').submit();
	}
}

function onSubscribeSubmit(token)
{
	var $globHash = getCookie('subscribeHash');
	var $formHash = document.getElementById('subscribeHash').value;
	if( $globHash === $formHash ) {
		setCookie('subscribeHash', 'false', 1);
		$('#subscribe-popup-form').find('button.g-recaptcha').remove();
		$('#subscribe-popup-form').find('button.fake_btn_subscribe').addClass('d-none');
		$('#subscribe-popup-form').find('button.fake_btn_subscribe_loading').removeClass('d-none');
		document.getElementById('subscribe-popup-form').submit();
	}
}

function onContactSubmit(token)
{
	var $globHash = getCookie('contactHash');
	var $formHash = document.getElementById('contactHash').value;
	if( $globHash === $formHash ) {
		setCookie('cvHash', 'false', 1);
		$('#contact-popup-form').find('button.g-recaptcha').remove();
		$('#contact-popup-form').find('button.fake_btn_contact').addClass('d-none');
		$('#contact-popup-form').find('button.fake_btn_contact_loading').removeClass('d-none');
		document.getElementById('contact-popup-form').submit();
	}
}


/**
 * Parse url
 */
function urlParser($url)
{
	var parser = document.createElement('a');
	parser.href = $url;

	var $result = parser.hostname;

	return $result;
}

/**
 * Get referrer address
 */
function getReferrer()
{
	var $url = document.referrer;

	if( getCookie('referrerURL') ) {
		var $oldURL = getCookie('referrerURL');
	}

	if($url.length > 0 || $oldURL) {

		if(typeof($oldURL) != "undefined" && $oldURL !== null) {
			var $hostname = urlParser($oldURL);
			var $search = $oldURL.split("?")[1];
		} else {
			var $hostname = urlParser($url);
			var $search = $url.split("?")[1];
		}

		var $searchAdwords = false;
		var $host = $hostname;

		if(typeof($search) != "undefined" && $search !== null) {
			var $searchParts = $search.split("&");
			var $searchPhrase = 'gclid';
			var $searchPartsArr = [];

			for(var $i = 0; $i < $searchParts.length; $i++) {
				$searchPartsArr.push($searchParts[$i].split("="));
			}
			for(var $i = 0; $i < $searchPartsArr.length; $i++) {
				var $part = $searchPartsArr[$i];
				for(var $j = 0; $j < $part.length; $j++) {

					if( $part[$j].match($searchPhrase) !== null ) {
						$searchAdwords = true;
						break;
					}
				}
			}
		}

		if($hostname !== window.location.hostname) {

			if(!$oldURL) {
				setCookie('referrerURL', $url, '7');
			}

			if( $searchAdwords === true ) {
				$('#cv-upload-form, #job-application-form').find('input[name="applicant-find"]').val('Google Adwords');
			} else {
				$('#cv-upload-form, #job-application-form').find('input[name="applicant-find"]').val($host);
			}

		}

	}

}
