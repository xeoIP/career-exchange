
/* Carousel Parameters */
if (typeof carouselItems == "undefined") {
	var carouselItems = 0;
}
if (typeof carouselAutoplay == "undefined") {
	var carouselAutoplay = false;
}
if (typeof carouselAutoplayTimeout == "undefined") {
	var carouselAutoplayTimeout = 1000;
}

$(document).ready(function ()
{
	/*==================================
	 Carousel
	 ==================================*/

	/* Featured Listings Carousel */
	var carouselObject = $('.featured-list-slider');
	var responsiveObject = {
		0:{
			items: 1,
			nav: true
		},
		600:{
			items: 3,
			nav: false
		},
		1000:{
			items: 5,
			nav: false,
			loop: (carouselItems > 5) ? true : false
		}
	};
	carouselObject.owlCarousel({
		nav:false,
		responsiveClass: true,
		responsive: responsiveObject,
		autoplay: carouselAutoplay,
		autoplayTimeout: carouselAutoplayTimeout,
		autoplayHoverPause: true
	});


	/*==================================
	 Ajax Tab || CATEGORY PAGE
	 ==================================*/

	$("#ajaxTabs li > a").click(function () {

		$("#allAds").empty().append("<div id='loading text-center'> <br> <img class='center-block' src='images/loading.gif' alt='Loading' /> <br> </div>");
		$("#ajaxTabs li").removeClass('active');
		$(this).parent('li').addClass('active');
		$.ajax({
			url: this.href, success: function (html) {
				$("#allAds").empty().append(html);
				$('.tooltipHere').tooltip('hide');
			}
		});
		return false;
	});

	urls = $('#ajaxTabs li:first-child a').attr("href");

	$("#allAds").empty().append("<div id='loading text-center'> <br> <img class='center-block' src='images/loading.gif' alt='Loading' /> <br>  </div>");
	$.ajax({
		url: urls, success: function (html) {
			$("#allAds").empty().append(html);
			$('.tooltipHere').tooltip('hide');

			/* default grid view class invoke into ajax content (product item) */
			$(function () {
				$('.hasGridView .item-list').addClass('make-grid');
				$('.hasGridView .item-list').matchHeight();
				$.fn.matchHeight._apply('.hasGridView .item-list');
			});
		}
	});


	/*==================================
	 List view clickable || CATEGORY
	 ==================================*/

	/* List view, Grid view  and compact view */

	var listItem = $('.item-list');
	var addDescBox = $('.item-list .add-desc-box');
	var addsWrapper = $('.adds-wrapper');

	/* Default view */
	var searchDisplayMode = readCookie('searchDisplayModeCookie');
	if (searchDisplayMode) {
		if (searchDisplayMode == 'grid') {
			gridView('.grid-view', listItem, addDescBox, addsWrapper);
		} else if (searchDisplayMode == 'list') {
			listView('.list-view', listItem, addDescBox, addsWrapper);
		} else if (searchDisplayMode == 'compact') {
			compactView('.compact-view', listItem, addDescBox, addsWrapper);
		} else {
			listView('.list-view', listItem, addDescBox, addsWrapper);
		}
	} else {
		createCookie('searchDisplayModeCookie', 'list', 7);
	}

	/* List */
	$('.list-view,#ajaxTabs li a').click(function (e) { /* use a class, since your ID gets mangled */
		e.preventDefault();
		listView('.list-view', listItem, addDescBox, addsWrapper);
		createCookie('searchDisplayModeCookie', 'list', 7);
	});

	/* Grid */
	$('.grid-view').click(function (e) { /* use a class, since your ID gets mangled */
		e.preventDefault();
		gridView(this, listItem, addDescBox, addsWrapper);
		createCookie('searchDisplayModeCookie', 'grid', 7);
	});

	$(function () {
		$('.hasGridView .item-list').matchHeight();
		$.fn.matchHeight._apply('.hasGridView .item-list');
	});

	$(function () {
		$('.row-featured .f-category').matchHeight();
		$.fn.matchHeight._apply('.row-featured .f-category');
	});

	$(function () {
		$('.has-equal-div > div').matchHeight();
		$.fn.matchHeight._apply('.row-featured .f-category');
	});

	/* Compac */
	$('.compact-view').click(function (e) { /* use a class, since your ID gets mangled */
		e.preventDefault();
		compactView(this, listItem, addDescBox, addsWrapper);
		createCookie('searchDisplayModeCookie', 'compact', 7);
	});


	/*==================================
	 Global Plugins ||
	 ==================================*/

	$('.long-list').hideMaxListItems({
		'max': 8,
		'speed': 500,
        'moreText': langLayout.hideMaxListItems.moreText + ' ([COUNT])',
        'lessText': langLayout.hideMaxListItems.lessText
	});

	$('.long-list-user').hideMaxListItems({
		'max': 12,
		'speed': 500,
        'moreText': langLayout.hideMaxListItems.moreText + ' ([COUNT])',
        'lessText': langLayout.hideMaxListItems.lessText
	});

	$('.long-list-home').hideMaxListItems({
		'max': 3,
		'speed': 500,
		'moreText': langLayout.hideMaxListItems.moreText + ' ([COUNT])',
		'lessText': langLayout.hideMaxListItems.lessText
	});


	$(function () {
		$('[data-toggle="tooltip"]').tooltip()
	});

	$(".scrollbar").scroller(); /* custom scroll bar plugin */


	/*=======================================================================================
	 cat-collapse Hmepage Category Responsive view
	 =======================================================================================*/

	$(window).bind('resize load', function () {
		if ($(this).width() < 767) {
			$('.cat-collapse').collapse('hide');
			$('.cat-collapse').on('shown.bs.collapse', function () {
				$(this).prev('.cat-title').find('.icon-down-open-big').addClass("active-panel");
				/* $(this).prev('.cat-title').find('.icon-down-open-big').toggleClass('icon-down-open-big icon-up-open-big'); */
			});
			$('.cat-collapse').on('hidden.bs.collapse', function () {
				$(this).prev('.cat-title').find('.icon-down-open-big').removeClass("active-panel");
			})
		} else {
			$('.cat-collapse').removeClass('out').addClass('in').css('height', 'auto');
		}
	});

	/* DEMO PREVIEW */

	$(".tbtn").click(function () {
		$('.themeControll').toggleClass('active')
	});

	/* Jobs */

	$("input:radio").click(function () {
		if ($('input:radio#job-seeker:checked').length > 0) {
			$('.forJobSeeker').removeClass('hide');
			$('.forJobFinder').addClass('hide');
		} else {
			$('.forJobFinder').removeClass('hide');
			$('.forJobSeeker').addClass('hide')
		}
	});

	$(".filter-toggle").click(function () {
		$('.mobile-filter-sidebar').prepend("<div class='closeFilter'>X</div>");
		$(".mobile-filter-sidebar").animate({"left": "0"}, 250, "linear", function () {
		});
		$('.menu-overly-mask').addClass('is-visible');
	});

	$(".menu-overly-mask").click(function () {
		$(".mobile-filter-sidebar").animate({"left": "-251px"}, 250, "linear", function () {
		});
		$('.menu-overly-mask').removeClass('is-visible');
	});


	$(document).on('click', '.closeFilter', function () {
		$(".mobile-filter-sidebar").animate({"left": "-251px"}, 250, "linear", function () {
		});
		$('.menu-overly-mask').removeClass('is-visible');
	});


	/* cityName will replace with selected location/area from location modal */

	$('#browseAdminCities').on('shown.bs.modal', function (e) {
		/* alert('Modal is successfully shown!'); */
		$("ul.list-link li a").click(function () {
			$('ul.list-link li a').removeClass('active');
			$(this).addClass('active');
			$(".cityName").text($(this).text());
			$('#browseAdminCities').modal('hide');
		});
	});


	/* Others */


	$("#checkAll").click(function () {
		$('.add-img-selector input:checkbox').not(this).prop('checked', this.checked);
	});
});





function listView(selecter, listItem, addDescBox, addsWrapper) {
	$('.grid-view,.compact-view').removeClass("active");
	$(selecter).addClass("active");
	listItem.addClass("make-list"); /* add the class to the clicked element */
	listItem.removeClass("make-grid");
	listItem.removeClass("make-compact");


	if ($('.adds-wrapper').hasClass('property-list')) {
		addDescBox.removeClass("col-sm-9");
		addDescBox.addClass("col-sm-6");
	} else {
		addDescBox.removeClass("col-sm-9");
		addDescBox.addClass("col-sm-12");
	}

	$(function () {
		listItem.matchHeight('remove');
	});
}

function gridView(selecter, listItem, addDescBox, addsWrapper) {
	$('.list-view,.compact-view').removeClass("active");
	$(selecter).addClass("active");
	listItem.addClass("make-grid"); /* add the class to the clicked element */
	listItem.removeClass("make-list");
	listItem.removeClass("make-compact");


	if (addsWrapper.hasClass('property-list')) {
		/* ... */
		addDescBox.toggleClass("");
		addDescBox.addClass("no");

	} else {
		/* ... */
	}

	$(function () {
		listItem.matchHeight();
		$.fn.matchHeight._apply('.item-list');
	});
}

function compactView(selecter, listItem, addDescBox, addsWrapper) {
	$('.list-view,.grid-view').removeClass("active");
	$(selecter).addClass("active");
	listItem.addClass("make-compact"); /* add the class to the clicked element */
	listItem.removeClass("make-list");
	listItem.removeClass("make-grid");


	if (addsWrapper.hasClass('property-list')) {
		addDescBox.addClass("col-sm-9").removeClass('col-sm-6');
	} else {
		addDescBox.toggleClass("col-sm-9 col-sm-7");
		addDescBox.addClass("no");
	}

	$(function () {
		$('.adds-wrapper .item-list').matchHeight('remove');
	});
}

/**
 * Create cookie
 * @param name
 * @param value
 * @param days
 */
function createCookie(name, value, days) {
	var expires;

	if (days) {
		var date = new Date();
		date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
		expires = "; expires=" + date.toGMTString();
	} else {
		expires = "";
	}
	document.cookie = encodeURIComponent(name) + "=" + encodeURIComponent(value) + expires + "; path=/";
}

/**
 * Read cookie
 * @param name
 * @returns {*}
 */
function readCookie(name) {
	var nameEQ = encodeURIComponent(name) + "=";
	var ca = document.cookie.split(';');
	for (var i = 0; i < ca.length; i++) {
		var c = ca[i];
		while (c.charAt(0) === ' ') c = c.substring(1, c.length);
		if (c.indexOf(nameEQ) === 0) return decodeURIComponent(c.substring(nameEQ.length, c.length));
	}
	return null;
}

/**
 * Delete cookie
 * @param name
 */
function eraseCookie(name) {
	createCookie(name, "", -1);
}

/**
 * Set Country Phone Code
 * @param countryCode
 * @param countries
 * @returns {boolean}
 */
function setCountryPhoneCode(countryCode, countries)
{
	if (typeof countryCode == "undefined" || typeof countries == "undefined") return false;
	if (typeof countries[countryCode] == "undefined") return false;

	$('#phoneCountry').html(countries[countryCode]['phone']);
}

/**
 * Set user type
 * @param userType
 * @returns {boolean}
 */
function setUserType(userType)
{
	if (typeof userType == "undefined") return false;

	if (userType == 3)
		$('#resumeBloc').show();
	else
		$('#resumeBloc').hide();
}

/**
 * Google Maps Generation
 * @param key
 * @param address
 * @param language
 */
function getGoogleMaps(key, address, language) {
	if (typeof address == "undefined") {
		var q = encodeURIComponent($('#address').text());
	} else {
		var q = encodeURIComponent(address);
	}
	if (typeof language == "undefined") {
		var language = 'en';
	}
	var googleMapsUrl = 'https://www.google.com/maps/embed/v1/place?key=' + key + '&q=' + q + '&language=' + language;

	$('#googleMaps').attr('src', googleMapsUrl);
}

/**
 * Show price & Payment Methods
 * @param packagePrice
 */
function showAmount(packagePrice)
{
	$('#payableAmount').html(packagePrice);
	if (packagePrice <= 0) {
		$('#packagesTable tbody tr:last').hide();
	} else {
		$('#packagesTable tbody tr:last').show();
	}
}

/**
 * Get the Selected Package Price
 * @param selectedPackage
 * @returns {*|jQuery}
 */
function getPackagePrice(selectedPackage)
{
	var price = $('#price-' + selectedPackage + ' .priceInt').html();
	price = parseFloat(price);

	return price;
}

/**
 * Redirect URL
 * @param url
 */
function redirect(url) {
	window.location.replace(url);
	window.location.href = url;
}

/**
 * Raw URL encode
 * @param str
 * @returns {string}
 */
function rawurlencode(str) {
	str = (str + '').toString();
	return encodeURIComponent(str).replace(/!/g, '%21').replace(/'/g, '%27').replace(/\(/g, '%28').replace(/\)/g, '%29').replace(/\*/g, '%2A');
}

/**
 * Check if a string is empty or null
 * @param str
 * @returns {boolean}
 */
function isEmptyValue(str) {
	return (!str || 0 === str.length);
}

/**
 * Check if a string is blank or null
 * @param str
 * @returns {boolean}
 */
function isBlankValue(str) {
	return (!str || /^\s*$/.test(str));
}
