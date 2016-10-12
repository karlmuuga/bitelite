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
					$('input, textarea, select').on('focusin', function() {
						$(this).data('holder', $(this).attr('placeholder'));
						$(this).attr('placeholder', '');
					});
					$('input, textarea, select').on('focusout', function() {
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
										console
												.log('http://www.bitelite.org/php/search.php?q='
														+ query);
										var searchResults = $('#searchResults');
										var error = $("<span class = 'searchError'>I'm sorry, but I couldn't find what You were looking for. </span>");
										var tooShort;
										var premiumError = $("<span class = 'premiumSearchError'>I'm sorry, but I couldn't find what You were looking for. </span>");
										if ($('#premiumAccount').val() === '1') {
											tooShort = $("<span class = 'premiumSearchError'>Search query must be at least 3 characters long.</span>");
										} else {
											tooShort = $("<span class = 'searchError'>Search query must be at least 3 characters long.</span>");
										}
										if (query.length < 3) {
											searchResults.empty();
											$('#searchInput').val('');
											searchResults.html(tooShort).hide()
													.slideDown(1000);
										} else {
											$
													.ajax({
														cache : false,
														url : 'http://www.bitelite.org/php/search.php?diner='
																+ query,
														type : 'GET',
														beforeSend : function() {
															searchResults
																	.empty();
															if ($(
																	'#premiumAccount')
																	.val() === '1') {
																searchResults
																		.html("<img class = 'loaderGif' src = '../img/premium.gif' alt = 'Loading'>");
															} else {
																searchResults
																		.html("<img class = 'loaderGif' src = '../img/diner.gif' alt = 'Loading'>");
															}
															$('#searchInput')
																	.val('');
														},
														error : function() {
															searchResults
																	.empty();
															if ($(
																	'#premiumAccount')
																	.val() === '1') {
																searchResults
																		.html(premiumError);
															} else {
																searchResults
																		.html(error);
															}
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
															if ($(
																	'#premiumAccount')
																	.val() === '1') {
																$('body')
																		.find(
																				'.resultName')
																		.css(
																				'color',
																				'#EDE275');
																$('body')
																		.find(
																				'.searchError')
																		.css(
																				'background',
																				'#EDE275');
																$('body')
																		.find(
																				'.verifiedRing')
																		.css(
																				'background',
																				'#EDE275');
															}
														}
													});
										}
									});
					$('.foodName')
							.on(
									'click',
									function() {
										var thisFood = $(this);
										$('*').removeClass('active');
										$('*').removeClass('premiumActive');
										var thisFoodName = $(this).attr(
												'data-foodname');
										console.log(thisFoodName);
										var foodNut = $('#foodNut');
										var dinerID = $('#dinerName').val();
										$
												.ajax({
													cache : false,
													url : '/php/dishInfo.php?name='
															+ thisFoodName
															+ '&id=' + dinerID,
													type : 'GET',
													dataType : 'html',
													beforeSend : function() {
														if ($('#premiumAccount')
																.val() === '1') {
															thisFood
																	.addClass('premiumActive');
														} else {
															thisFood
																	.addClass('active');
														}
														foodNut.empty();
														if ($('#premiumAccount')
																.val() === '1') {
															foodNut
																	.html("<img class = 'loaderGif' src = '../img/premium.gif' alt = 'Loading'>");
														} else {
															foodNut
																	.html("<img class = 'loaderGif' src = '../img/diner.gif' alt = 'Loading'>");
														}
													},
													error : function() {
														Apprise('Error occurred! Please try again!');
														foodNut.empty();
													},
													success : function(data) {
														if ($('#premiumAccount')
																.val() === '1') {
															thisFood
																	.addClass('premiumActive');
														} else {
															thisFood
																	.addClass('active');
														}
														foodNut.empty();
														$('#foodNut')
																.append(data)
																.hide()
																.slideDown(1000);
														if ($('#premiumAccount')
																.val() === '1') {
															$('.nutRow:even')
																	.css(
																			'color',
																			'#DFD681');
														} else {
															$('.nutRow:even')
																	.css(
																			'color',
																			'#6699ff');
														}
													}
												});
									});
					if ($('#premiumAccount').val() === '1') {
						$('body').addClass('premium');
					} else {
						$('body').css('background', '#6699ff');
					}
					$('input,select,textarea').on('focusin', function() {
						if ($('#premiumAccount').val() === '1') {
							$(this).css('outline', '1px solid #EDE275');
						} else {
							$(this).css('outline', '1px solid #6699ff');
						}
					});
					$('input,select,textarea').on('focusout', function() {
						$(this).css('outline', '');
					});
					$('#searchButton').on('mousedown', function() {
						if ($('#premiumAccount').val() === '1') {
							$(this).addClass('premiumButton');
						} else {
							$(this).addClass('activeButton');
						}
					});
					$('body').on('mouseup', function() {
						$('*').removeClass('activeButton');
						$('*').removeClass('premiumButton');
					});
				});