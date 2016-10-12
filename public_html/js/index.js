$(document)
		.ready(
				function() {
					$('a[href^="#"]').on('click', function(e) {
						e.preventDefault();
						var target = this.hash, $target = $(target);
						$('html, body').stop().animate({
							'scrollTop' : $target.offset().top - 90
						}, 900, 'swing', function(e) {
							e.preventDefault();
							window.location.hash = target;
						});
					});
					$('#main').css('display', 'block');
					$('nav').css('display', 'inline-block');
					$('.errorDiv').hide();
					$('input').on('focusin', function() {
						$(this).data('holder', $(this).attr('placeholder'));
						$(this).attr('placeholder', '');
					});
					$('input').on('focusout', function() {
						$(this).attr('placeholder', $(this).data('holder'));
					});
					$('#searchForm')
							.on(
									'submit',
									function(event) {
										event.preventDefault();
										var searchValue = $('#searchInput')
												.val();
										var query = $.trim(searchValue);
										query = encodeURIComponent(query);
										console.log('Päringu pikkus on '
												+ query.length + ' tähemärki.');
										var searchResults = $('#searchResults');
										var error = $("<span class = 'searchError'>I'm sorry, but I couldn't find what You were looking for. </span>");
										var tooShort = $("<span class = 'searchError'>Search query must be at least 3 characters long.</span>");
										if (query.length < 3) {
											searchResults.empty();
											$('#searchInput').val('');
											searchResults.html(tooShort).hide()
													.slideDown(1000);
										} else {
											$
													.ajax({
														cache : false,
														url : 'php/search.php?diner='
																+ query,
														type : 'GET',
														beforeSend : function() {
															searchResults
																	.empty();
															searchResults
																	.html("<img class = 'loaderGif' src = '/img/index.gif' alt = 'Loading'>");
															$('#searchInput')
																	.val('');
														},
														error : function() {
															searchResults
																	.empty();
															searchResults
																	.html(error);
														},
														success : function(data) {
															searchResults
																	.empty();
															searchResults
																	.append(
																			data)
																	.hide()
																	.slideDown(
																			1000);
														}
													});
										}
									});
					$('#registerForm')
							.validate(
									{
										focusInvalid : false,
										errorElement : 'div',
										errorClass : 'registerError',
										highlight : function(element) {
											$(element).removeClass(
													"registerError");
										},
										messages : {
											registerEmail : {
												remote : 'Someone is already using that email. Please choose another!'
											}
										},
										rules : {
											registerEmail : {
												required : true,
												maxlength : 80,
												remote : "/php/checkEmail.php"
											},
											restaurantName : {
												required : true,
												minlength : 3,
												maxlength : 80
											}
										}
									});
					$('#forgotPass')
							.on(
									'click',
									function(e) {
										e.preventDefault();
										console.log('1');
										$('#loginForm').hide();
										console.log('2');
										$(
												"<form id = 'forgotPassword' method = 'POST' action='http://www.bitelite.org/index.php#account'><h2 class = 'accountHeader'>Recover Your password</h2><input type = 'email' placeholder = 'Please type here the email address, what You are using to login.' name = 'currentEmail' id = 'currentEmail' ><input name='recover'  id = 'recover' type = 'submit' value = 'Reset my password'><input id = 'backLogin' type = 'button' value = 'I know my password'></form>")
												.prependTo(
														'#account .contentDiv');
										console.log('3');
										$('#backLogin').on(
												'click',
												function(e) {
													e.preventDefault();
													$('#forgotPassword')
															.remove();
													$('#loginForm').show();
												});
										$('input')
												.on(
														'focusin',
														function() {
															$(this)
																	.data(
																			'holder',
																			$(
																					this)
																					.attr(
																							'placeholder'));
															$(this)
																	.attr(
																			'placeholder',
																			'');
														});
										$('input').on(
												'focusout',
												function() {
													$(this).attr(
															'placeholder',
															$(this).data(
																	'holder'));
												});
									});
				});