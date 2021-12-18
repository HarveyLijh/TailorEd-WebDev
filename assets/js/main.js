(function($) {
	
	var	$window = $(window),
		$header = $('#header'),
		$body = $('body');
	
	// Breakpoints.
		breakpoints({
			xlarge:   [ '1281px',  '1680px' ],
			large:    [ '981px',   '1280px' ],
			medium:   [ '737px',   '980px'  ],
			small:    [ '481px',   '736px'  ],
			xsmall:   [ '361px',   '480px'  ],
			xxsmall:  [ null,      '360px'  ]
		});
	
	// Play initial animations on page load.
		$window.on('load', function() {
			window.setTimeout(function() {
				$body.removeClass('is-preload');
			}, 20);
			var hide = document.getElementsByClassName("error-message");
		       for (var i = 0; i < hide.length; i++){
		           hide[i].style.visibility = "hidden";
		       }
			
			setTimeout(function() {
			    var heightOffset = window.outerHeight - window.innerHeight;
			    var widthOffset = window.outerWidth - window.innerWidth;
			    var height = document.getElementById("content").clientHeight + heightOffset;
			    var width = document.getElementById("content").clientWidth + widthOffset;
			    window.resizeTo(width, height);
			}, 100);	
		});
	
	// Tweaks/fixes.
	
		// Polyfill: Object fit.
			if (!browser.canUse('object-fit')) {
	
				$('.image[data-position]').each(function() {
	
					var $this = $(this),
						$img = $this.children('img');
	
					// Apply img as background.
						$this
							.css('background-image', 'url("' + $img.attr('src') + '")')
							.css('background-position', $this.data('position'))
							.css('background-size', 'cover')
							.css('background-repeat', 'no-repeat');
	
					// Hide img.
						$img
							.css('opacity', '0');
	
				});
	
			}
	
	// Dropdowns.
	$('#nav > ul').dropotron({
		alignment: 'right',
		hideDelay: 350,
		baseZIndex: 100000
	});
	
	// Scroll Effect
	$( "a.down" ).click(function () {
	    	event.preventDefault();
	    	$("html, body").animate({ scrollTop: $($(this).attr("href")).offset().top }, 1000);
	});
	
	// Checkboxes Rules
	$('#any-race').click(function() {
		if ($('#any-race').is(':checked')) {
     	      		$('.race').prop('checked', false);   
     	  	} else {
     	      		$('.race').removeAttr('disabled');
     	  	}
	});
	
	$("input[type='checkbox'].race").change(function(){
	    var a = $("input[type='checkbox'].race");
	    if(a.length == a.filter(":checked").length){
		 $('#any-race').prop('checked', true);
		 $('.race').prop('checked', false);   
	    }
	    else {
		 $('#any-race').prop('checked', false);
	    }
	});
	
	// Dropdown Menus 
	$(".home").hide();
	
	$('#disciplinary').on('change', function(){
		var disciplinary = $(this).val();
		if (disciplinary) {
			$("#department").hide();
			$("#course").hide();	
			$.ajax({
				type:'POST',
				url:'ajaxDropdown.php',
				data:'disciplinary='+disciplinary,
				success:function(html){
					$('#department').html(html);
				}
			}); 
			$("#department").show();
		} else {
			$("#department").hide();
			$("#course").hide();	
		}
	});

	$('#department').on('change', function(){
		var department = $(this).val();
		if(department){
			$("#course").hide();	
			$.ajax({
				type:'POST',
				url:'ajaxDropdown.php',
				data:'department='+department,
				success:function(html){
					$('#course').html(html);
				}
			}); 
			$("#course").show();	
		} else {
			$("#course").hide();	
		}
	});
	
	$('#course').on('change', function(){
		var course = $(this).val();
		if(course){
			$.ajax({
				type:'POST'
			}); 
		}
	});
	
	// AJAX post request --- This DOESN'T really work yet, so umm we'll keep working on it
	// $("input[type='radio']").click(function(){
	// 	$('#results').empty();
	// 	$.ajax({
	// 		type:'POST',
	// 		url:'ajaxResults.php',
	// 		data: {
	// 			disciplinary: $("#disciplinary").val(), 
	// 			department: $("#department").val(), 
	// 			course: $("#course").val(), 
	// 
	// 			sex: $(".category4:checked").val(), 
	// 			fg: $(".category3:checked").val(), 
	// 
	// 			anyrace: $('#any-race:checked').val(),
	// 			white: $('#race-option-2:checked').val(),
	// 			asian: $('#race-option-3:checked').val(),
	// 			black: $('#race-option-4:checked').val(),
	// 			hispanic: $('#race-option-5:checked').val(),
	// 
	// 			lecture: $('input[name = lecture]').val(),
	// 			discussion: $('input[name = discussion]').val(),
	// 			groupwork: $('input[name = group-work]').val()
	// 		},
	// 		success:function(html){
	// 			$('#results').html(html);
	// 		}
	// 	}); 
	// });

	// Form Validation
	$('#submit').click(function() {
		
		percentage = Number(document.getElementById("lecture").value) + Number(document.getElementById("group-work").value) + 
			Number(document.getElementById("discussion").value);
			
		if(percentage != 100) {
			document.getElementById("class-style-error").style.visibility = "visible";
		} else {
			document.getElementById("class-style-error").style.visibility = "hidden";
		}
		
		if (document.getElementById('disciplinary').value == "") {
			document.getElementById("class-subject-error").style.visibility = "visible";
		} else {
			document.getElementById("class-subject-error").style.visibility = "hidden";
		}
		
		sex = $(".category1:checked").length;
		race = $(".category2:checked").length;
		fg = $(".category3:checked").length;

		if(!race) {
			document.getElementById("race-error").style.visibility = "visible";
		} else {
			document.getElementById("race-error").style.visibility = "hidden";
		}
		if(!sex) {
			document.getElementById("sex-error").style.visibility = "visible";
		} else {
			document.getElementById("sex-error").style.visibility = "hidden";
		}
		if(!fg) {
			document.getElementById("fg-error").style.visibility = "visible";
		} else {
			document.getElementById("fg-error").style.visibility = "hidden";
		}
				
		if (percentage!=100) {
			$('html,body').animate({ scrollTop: $("#class-style").offset().top},1000);
			return false;
		}
		if (document.getElementById('disciplinary').value == "") {
			$('html,body').animate({ scrollTop: $("#class-subject").offset().top},1000);
			return false;
		}
		else if (!race || !fg || !sex) {
			$('html,body').animate({ scrollTop: $("#student-sample").offset().top},1000);
			return false;
		}	
		return true;			
		
	});
	
	$('#modify-submit').click(function() {
		
		percentage = Number(document.getElementById("lecture").value) + Number(document.getElementById("group-work").value) + 
		Number(document.getElementById("discussion").value);
		
		race = $(".category1:checked").length;
		grade = $(".category2:checked").length;
		fg = $(".category3:checked").length;
		sex = $(".category4:checked").length;
				
		if (percentage!=100) {
			return false;
		}
		if (document.getElementById('disciplinary').value == "") {
			return false;
		}
		else if (!race || !fg || !sex) {
			return false;
		}	
		
		return true;			
		
	});
	
	// Menu.
	$('<a href="#navPanel" class="navPanelToggle">Menu</a>')
		.appendTo($header);

	$(	'<div id="navPanel">' +
			'<nav>' +
				$('#nav') .navList() +
			'</nav>' +
			'<a href="#navPanel" class="close"></a>' +
		'</div>')
			.appendTo($body)
			.panel({
				delay: 500,
				hideOnClick: true,
				hideOnSwipe: true,
				resetScroll: true,
				resetForms: true,
				side: 'right'
			});

})(jQuery);