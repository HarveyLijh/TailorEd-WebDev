// $(function() {
// $( document ).ready(function() {
	// init()
// });
// $( window ).load(function() {
$(window).on('load', function() {
// $(window).load(function(){
	init()
});

// $(document).on("load",function(){
function init() {
  page_scroll_handler();
  init_sidebar();
  init_email();
}

//jQuery for page scrolling feature - requires jQuery Easing plugin
function page_scroll_handler() {
    $('a.page-scroll').bind('click', function(event) {
        var $anchor = $(this);
        $('html, body').stop().animate({
            scrollTop: $($anchor.attr('href')).offset().top
        }, 500, 'easeInOutExpo');
        event.preventDefault();
    });
}


function init_sidebar() {

	var slideout = new Slideout({
		'panel': document.getElementById('main'),
		'menu': document.getElementById('menu'),
		'padding': 256,
		'tolerance': 100,
		'side': 'right'
	});

	document.querySelector('.js-slideout-toggle').addEventListener('click', function() {
		slideout.toggle();
	});

	document.querySelector('.menu').addEventListener('click', function(eve) {
		if (eve.target.nodeName === 'A') { slideout.close(); }
	});

	slideout.on('beforeopen', function() {
		document.querySelector('.fixed').classList.add('fixed-open-right');
	});

	slideout.on('beforeclose', function() {
		document.querySelector('.fixed').classList.remove('fixed-open-right');
	});
}


function init_email() {
	// Jeanette Bohg Email
    var jb_email_link = $("a#encoded_email_jb")
    var jb_email = function () { return atob('amJvaGdAdHVlLm1wZy5kZQ==') };
    jb_email_link.attr("href", 'mailto:' + jb_email());
    jb_email_link.text(jb_email());

    // Oliver Brock Email
    var ob_email_link = $("a#encoded_email_ob")
    var ob_email = function () { return atob('b2xpdmVyLmJyb2NrQHR1LWJlcmxpbi5kZQ') };
    ob_email_link.attr("href", 'mailto:' + ob_email());
    ob_email_link.text(ob_email());

    // Rss2017 Event Email
    var rss_email_link = $("a#encoded_email_rss")
    var rss_email = function () { return atob('cnNzMjAxN3dzQHR1ZWJpbmdlbi5tcGcuZGU') };
    rss_email_link.attr("href", 'mailto:' + rss_email());
    rss_email_link.text(rss_email()); 
}