$(document)
		.ready(
				function() {
					/* Toitude lisamine */
					$(function() {
						var typingTimer;
						var doneTypingInterval = 2000;
						$('#addItemSearch').keyup(
								function() {
									clearTimeout(typingTimer);
									if ($('#addItemSearch').val) {
										typingTimer = setTimeout(doneTyping,
												doneTypingInterval);
									}
								});

						function doneTyping(e) {
							var addItemSearch = $('#addItemSearch');
							var foodQuery = $.trim(addItemSearch.val());
							var foodContainer = $('#foodResults');
							var error = $("<span class = 'searchError'>I'm sorry, but I couldn't find what You were looking for. </span>");
							if (foodQuery === '') {
								foodContainer.empty();
							} else {
								$
										.ajax({
											type : 'GET',
											url : '/php/search.php?food='
													+ encodeURIComponent(foodQuery),
											beforeSend : function() {
												foodContainer.empty();
												foodContainer
														.html("<img class = 'loaderGif' src = '../img/account.gif' alt = 'Loading'>");
											},
											error : function() {
												foodContainer.html(error);
												addItemSearch.val(' ');
											},
											success : function(data) {
												foodContainer.html(data);
												addItemSearch.val(' ');
											}
										});
							}
						}
						foodLenght = 0;
						$('#foodResults')
								.on(
										'click',
										'.resultsList li',
										function(e) {
											e.preventDefault();
											var foodName = $(this).text();
											var puhasFoodName = escape(foodName);
											var foodValue = foodName.replace(
													new RegExp("'", "g"),
													'&#39');
											var container = $('#addedFoods');
											$
													.ajax({
														beforeSend : function() {
														},
														type : 'GET',
														url : '/php/foodunits.php?q='
																+ puhasFoodName,
														error : function() {
															Apprise('Error occurred! Please try again.');
														},
														success : function(data) {
															$('#addedFoods')
																	.show();
															$(
																	"<div class = 'newElement'><input readonly class = 'foodElement' value = '"
																			+ foodValue
																			+ "' name = 'foodItem"
																			+ foodLenght
																			+ "'><input type = 'text' placeholder = 'Quantity' class = 'mealWeight' name = 'weight"
																			+ foodLenght
																			+ "'><select class = 'mealUnit' name = 'unit"
																			+ foodLenght
																			+ "'> "
																			+ data
																			+ "</select><input class = 'removeItem' value = 'Remove' type = 'button'></div>")
																	.appendTo(
																			container);
															foodLenght++;
														}
													});
										});
						$('#addedFoods').on('click', '.foodElement',
								function() {
									var thisElement = $(this).attr('value');
									Apprise(thisElement);
								});
						$('#addedFoods').on(
								'click',
								'.removeItem',
								function(e) {
									e.preventDefault();
									var activeItem = $(this);
									activeItem.parent().fadeOut(500,
											function() {
												activeItem.parent().remove();
												foodLenght--;
											});
									if (foodLenght < 2) {
										$('#addedFoods').hide();
									}
								});
						$('#addFoodButton')
								.on(
										'click',
										function(e) {
											e.preventDefault();
											var mealName = $('#mealName').val();
											var mealDesc = $('#mealDesc').val();
											var isNum;
											$('input.mealWeight')
													.each(
															function() {
																var value = $(
																		this)
																		.val();
																if (value.length === 0) {
																	isNum = 0;
																	return isNum;
																} else if (isNaN(value)) {
																	isNum = 1;
																	return isNum;
																}
															});
											var collectFoodInfo = function() {
												var foodArray = [];
												var foodString = '';
												$(
														'#foodInfo input, #foodInfo select, #foodInfo textarea')
														.each(
																function() {
																	if ($(this)
																			.attr(
																					'value') === 'Remove') {
																	} else if ($(
																			this)
																			.attr(
																					'id') != 'addItemSearch') {
																		foodArray
																				.push($(
																						this)
																						.val());
																	}
																});
												foodString = foodArray
														.join('¤');
												return foodString;
											};
											var foodInfo = collectFoodInfo();
											if (!$
													.trim($('#addedFoods')
															.html()).length) {
												Apprise("Food must contain at least 1 ingredient");
											} else if (isNum === 0) {
												Apprise("Quantity can't be empty.");
											} else if (isNum === 1) {
												Apprise("Use only numbers in quantity or use dot instead of comma.");
											} else if (mealDesc.length === 0
													&& mealName.length === 0) {
												Apprise("Please add a name of food and its description.");
											} else if (mealName.length === 0) {
												Apprise("Please add a name of food.");
											} else if (mealDesc.length === 0) {
												Apprise("Please add description of food.");
											} else {
												$
														.ajax({
															type : 'GET',
															cache : false,
															url : '/php/manageFood.php?new='
																	+ foodInfo,
															beforeSend : function() {
																console
																		.log(foodInfo);
															},
															error : function() {
																Apprise('Error occurred! Please try again.');
															},
															success : function(
																	data) {
																if (data === 'SUCCESS') {
																	window.location.href = 'http://www.bitelite.org/account/';
																} else if (data === 'foodLimit') {
																	Apprise('You must have Premium account to add more than 10 foods.');
																} else {
																	Apprise('Unsuccess.');
																}
															}
														});
											}
										});
					}); /* Toidu muutmine */
					editFoodFunction = function() {
						foodLenght2 = $('#addedFoods2 .newElement2').length;
						var typingTimer2;
						var doneTypingInterval = 2000;
						$('#addItemSearch2').keyup(
								function() {
									clearTimeout(typingTimer2);
									if ($('#addItemSearch2').val) {
										typingTimer2 = setTimeout(doneTyping,
												doneTypingInterval);
									}
								});

						function doneTyping(e) {
							var addItemSearch2 = $('#addItemSearch2');
							var foodQuery2 = $.trim(addItemSearch2.val());
							var foodContainer2 = $('#foodResults2');
							var error2 = $("<span class = 'searchError'>I'm sorry, but I couldn't find what You were looking for. </span>");
							if (foodQuery2 === '') {
								foodContainer2.empty();
							} else {
								$
										.ajax({
											type : 'GET',
											url : '/php/search.php?food='
													+ encodeURIComponent(foodQuery2),
											beforeSend : function() {
												foodContainer2.empty();
												foodContainer2
														.html("<img class = 'loaderGif' src = '../img/account.gif' alt = 'Loading'>");
											},
											error : function() {
												foodContainer2.html(error2);
												addItemSearch2.val(' ');
											},
											success : function(data) {
												foodContainer2.html(data);
												addItemSearch2.val(' ');
											}
										});
							}
						}
						$('#foodResults2')
								.on(
										'click',
										'.resultsList li',
										function(e) {
											e.preventDefault();
											var foodName2 = $(this).text();
											var puhasfoodName2 = escape(foodName2);
											var foodValue2 = foodName2.replace(
													new RegExp("'", "g"),
													'&#39');
											var container2 = $('#addedFoods2');
											$
													.ajax({
														beforeSend : function() {
														},
														type : 'GET',
														url : '/php/foodunits.php?q='
																+ puhasfoodName2,
														error : function() {
															Apprise('Error occurred! Please try again.');
														},
														success : function(data) {
															$('#addedFoods2')
																	.show();
															$(
																	"<div class = 'newElement2'><input readonly class = 'foodElement2' value = '"
																			+ foodValue2
																			+ "' name = 'foodItem"
																			+ foodLenght2
																			+ "'><input type = 'text' placeholder = 'Quantity' class = 'mealWeight2' name = 'weight"
																			+ foodLenght2
																			+ "'><select class = 'mealUnit2' name = 'unit"
																			+ foodLenght2
																			+ "'> "
																			+ data
																			+ "</select><input class = 'removeItem2' value = 'Remove' type = 'button'></div>")
																	.appendTo(
																			container2);
															foodLenght2++;
														}
													});
										});
						$('#addedFoods2').on('click', '.foodElement2',
								function() {
									var thisElement2 = $(this).attr('value');
									Apprise(thisElement2);
								});
						$('#addedFoods2').on(
								'click',
								'.removeItem2',
								function(e) {
									e.preventDefault();
									var activeItem2 = $(this);
									activeItem2.parent().fadeOut(500,
											function() {
												activeItem2.parent().remove();
												foodLenght2--;
											});
									if (foodLenght2 < 2) {
										$('#addedFoods2').hide();
									}
								});
						$('.submitFoodChange')
								.on(
										'click',
										function(e) {
											e.preventDefault();
											var foodString2 = '';
											console.log($('#foodInfo2 input')
													.val());
											var collectFoodInfo2 = function() {
												var foodArray2 = [];
												$(
														'#foodInfo2 input, #foodInfo2 select, #foodInfo2 textarea')
														.each(
																function() {
																	if ($(this)
																			.attr(
																					'value') === 'Remove') {
																	} else if ($(
																			this)
																			.attr(
																					'id') != 'addItemSearch2') {
																		foodArray2
																				.push($(
																						this)
																						.val());
																	}
																});
												foodString2 = foodArray2
														.join('¤');
												return foodString2;
											};
											var foodInfo2 = collectFoodInfo2();
											var mealName2 = $('#mealName2')
													.val();
											var mealDesc2 = $('#mealDesc2')
													.val();
											var isNum2;
											$('input.mealWeight2')
													.each(
															function() {
																var value = $(
																		this)
																		.val();
																if (value.length === 0) {
																	isNum2 = 0;
																	return isNum2;
																} else if (isNaN(value)) {
																	isNum2 = 1;
																	return isNum2;
																}
															});
											if (!$.trim($('#addedFoods2')
													.html()).length) {
												Apprise("Food must contain at least 1 ingredient");
											} else if (isNum2 === 0) {
												Apprise("Quantity can't be empty.");
											} else if (isNum2 === 1) {
												Apprise("Use only numbers in quantity or use dot instead of comma.");
											} else if (mealDesc2.length === 0
													&& mealName2.length === 0) {
												Apprise("Please add a name of food and its description.");
											} else if (mealName2.length === 0) {
												Apprise("Please add a name of food.");
											} else if (mealDesc2.length === 0) {
												Apprise("Please add description of food.");
											} else {
												$
														.ajax({
															type : 'GET',
															cache : false,
															url : '/php/manageFood.php?change='
																	+ foodInfo2
																	+ '&oldname='
																	+ editFoodName,
															error : function() {
																Apprise('Error occurred! Please try again.');
															},
															beforeSend : function() {
																console
																		.log(foodInfo2);
															},
															success : function(
																	data) {
																if (data === 'SUCCESS') {
																	$(
																			'.editFoodDiv')
																			.remove();
																	Apprise('Success.');
																	var target = $('#listOfFoods');
																	$target = $(target);
																	$(
																			'html, body')
																			.stop()
																			.animate(
																					{
																						'scrollTop' : $target
																								.offset().top - 90
																					},
																					900,
																					'swing',
																					function(
																							e) {
																						e
																								.preventDefault();
																						window.location.hash = target;
																					});
																} else {
																	Apprise('Unsuccess.');
																}
															}
														});
											}
										});
						$('input, textarea, select').on(
								'focusin',
								function() {
									$(this).data('holder',
											$(this).attr('placeholder'));
									$(this).attr('placeholder', '');
								});
						$('input, textarea, select').on(
								'focusout',
								function() {
									$(this).attr('placeholder',
											$(this).data('holder'));
								});
						$('.removeFood')
								.on(
										'click',
										function(e) {
											e.preventDefault();
											$
													.ajax({
														url : '/php/manageFood.php?del='
																+ editFoodName,
														type : 'GET',
														cache : false,
														error : function() {
															Apprise('Error occurred! Please try again.');
														},
														success : function(data) {
															if (data === 'SUCCESS') {
																$(
																		'.editFoodDiv')
																		.remove();
																$(
																		'.mealToEdit[value="'
																				+ editFoodName
																				+ '"]')
																		.remove();
																Apprise('Success.');
																var target = $('#listOfFoods');
																$target = $(target);
																$('html, body')
																		.stop()
																		.animate(
																				{
																					'scrollTop' : $target
																							.offset().top - 90
																				},
																				900,
																				'swing',
																				function(
																						e) {
																					e
																							.preventDefault();
																					window.location.hash = target;
																				});
															} else {
																Apprise('Unsuccess.');
															}
														}
													});
										});
						$(
								'input[type = "button"], input[type = "submit"], .mealToEdit, .submitFoodChange')
								.on('focusin', function() {
									$(this).css('outline', '0 !important');
								});
					};
					$('.mealToEdit')
							.on(
									'click',
									function(e) {
										e.preventDefault();
										editFood = $(this);
										editFoodName = editFood.val();
										$
												.ajax({
													url : '/php/getfood.php?q='
															+ encodeURIComponent(editFoodName),
													type : 'GET',
													cache : false,
													beforeSend : function() {
														$(
																"<img class = 'loaderGif' src = '/img/account.gif' alt = 'Loading'>")
																.insertAfter(
																		editFood);
														$('.editFoodDiv')
																.remove();
													},
													error : function() {
														Apprise('Error occurred! Please try again.');
													},
													success : function(data) {
														$('.editFoodDiv')
																.remove();
														$('.loaderGif')
																.remove();
														$(
																"<div class = 'editFoodDiv'>"
																		+ data
																		+ "<div class = 'submitFoodChangeSeparator'></div><input value = 'Update dish' type = 'submit' class = 'submitFoodChange'><input value = 'Remove dish' type = 'submit' class = 'removeFood'></div>")
																.insertAfter(
																		editFood)
																.hide()
																.slideDown(1000);
														editFoodFunction();
														var target = $('.editFoodDiv');
														$target = $(target);
														$('html, body')
																.stop()
																.animate(
																		{
																			'scrollTop' : $target
																					.offset().top - 90
																		},
																		900,
																		'swing',
																		function(
																				e) {
																			e
																					.preventDefault();
																			window.location.hash = target;
																		});
													}
												});
									}); /* Toitude muutmise lõpp */
					$('#addedFoods').hide();
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
					$('#desc')
							.on(
									'click',
									function() {
										var inputVal = $('#textareaInput')
												.val();
										if (inputVal.length === 0) {
											Apprise('Please provide at least something.');
										} else if (inputVal.length > 1000) {
											Apprise('You can use a maximum of 1000 characters.');
										} else {
											$
													.ajax({
														type : 'GET',
														cache : false,
														url : '/php/change.php?desc'
																+ '='
																+ encodeURIComponent(inputVal),
														error : function() {
															Apprise('Error occurred! Please try again.');
														},
														success : function(e) {
															Apprise('Success.');
														}
													});
										}
									});
					$('.descButton')
							.on(
									'click',
									function() {
										var thisButton = $(this);
										var inputVal = thisButton.prev('input')
												.val();
										var emailInput = $('#email').val();
										if (inputVal.length === 0) {
											Apprise('Please provide at least something.');
										} else {
											$
													.ajax({
														type : 'GET',
														cache : false,
														url : '/php/change.php?'
																+ thisButton
																		.attr("id")
																+ '='
																+ encodeURIComponent(inputVal),
														error : function() {
															Apprise('Error occurred! Please try again.');
														},
														success : function() {
															Apprise('Success.');
														}
													});
										}
									});

					function validateEmail(email) {
						var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
						return re.test(email);
					}
					$('.settingsInput').on('focusin', function() {
						$(this).data('holder', $(this).attr('placeholder'));
						$(this).attr('placeholder', '');
						$(this).css('background-color', 'white');
					});
					$('.settingsInput').on('focusout', function() {
						$(this).attr('placeholder', $(this).data('holder'));
						$(this).css('background-color', '#FAFAFA');
					}); /* Verfikatsioon */
					$('#verificationButton')
							.on(
									'click',
									function(e) {
										e.preventDefault();
										$('#step2info').empty();
										var nextButton = $(this);
										var leheUrl = nextButton.prev('input')
												.val();
										var validUrl = /(^|\s)((https?:\/\/)?[\w-]+(\.[\w-]+)+\.?(:\d+)?(\/\S*)?)/gi;
										if (!leheUrl.match(validUrl)) {
											Apprise('Provide a valid URL.');
										} else {
											$
													.ajax({
														type : 'GET',
														cache : false,
														url : '/php/getkey.php?url='
																+ leheUrl,
														error : function() {
															Apprise('Error occurred! Please try again.');
														},
														beforeSend : function() {
															$('#step2info')
																	.empty();
															$(
																	"<img class = 'loaderGif' src = '/img/account.gif' alt = 'Loading'>")
																	.appendTo(
																			'#step2info');
														},
														success : function(data) {
															$('#step1info')
																	.remove();
															$('#step2info')
																	.empty();
															$(
																	'<p id = "step2"><span id = "step2text">Step two!</span> Please create a text file called <span class = "urlCode">'
																			+ data
																			+ '</span> to main folder (also known as root directory) of previously added website. Text file ends with .txt extension, so it should look like <span class = "urlCode">'
																			+ data
																			+ '.txt</span> .Also please add <span class = "urlCode">'
																			+ data
																			+ '</span> to file! After You have done that, please press a button below called \'Verify\' account. If You successfully created text file with right name, Your account should be verified in a seconds, but if verification fails, then we will tell You.</p><input type = "button" value = "Verify"id = "testURL">')
																	.appendTo(
																			'#step2info');
														}
													});
										}
									});
					$('#verification')
							.on(
									'click',
									'#testURL',
									function() {
										console.log('Test');
										$
												.ajax({
													url : '/php/verify.php',
													type : 'GET',
													cache : false,
													error : function() {
														Apprise('Error occurred! Please try again.');
													},
													success : function(data) {
														if (data === "Your account has been verifyed successfully.") {
															window.location.href = 'http://www.bitelite.org/account/';
														} else {
															Apprise(data);
														}
													}
												});
									});
					/* Seaded */
					$('.settingsButton')
							.on(
									'click',
									function(e) {
										e.preventDefault();
										var settingsButton = $(this);
										var inputValue = settingsButton.prev(
												'input').val();
										console.log(inputValue);
										if (settingsButton.prev('input').attr(
												'type') === 'email') {
											if (!validateEmail(inputValue)) {
												Apprise('Please provide valid email address.');
											} else if (inputValue.length < 1) {
												Apprise('Please provide at least something.');
											} else {
												var newEmail = inputValue;
												console.log('Uus email on '
														+ newEmail);
												$
														.ajax({
															type : 'POST',
															cache : false,
															url : '/php/change.php',
															data : 'email='
																	+ newEmail,
															error : function() {
																Apprise('Error occurred! Please try again.');
															},
															success : function() {
																Apprise('Verification link has been sent to '
																		+ newEmail
																		+ '.');
																settingsButton
																		.prev(
																				'input')
																		.val('');
															}
														});
											}
										} else {
											if (inputValue.length < 8) {
												Apprise('Please provide at least 8 characters.');
											} else {
												var passwd = inputValue;
												console.log('Uus parool on '
														+ passwd);
												$
														.ajax({
															type : 'POST',
															cache : false,
															error : function() {
																Apprise('Error occurred! Please try again.');
															},
															url : '/php/change.php',
															data : "passwd="
																	+ passwd,
															success : function() {
																Apprise('Success.');
																settingsButton
																		.prev(
																				'input')
																		.val('');
															}
														});
											}
										}
									});
					$('#showPassword').on('click', function(e) {
						e.preventDefault();
						$('#passwordUpdate').get(0).type = 'text';
					});
					$('input, textarea, select').on('focusin', function() {
						$(this).data('holder', $(this).attr('placeholder'));
						$(this).attr('placeholder', '');
					});
					$('input, textarea, select').on('focusout', function() {
						$(this).attr('placeholder', $(this).data('holder'));
					});
					$('.descInput').on('focusin', function() {
						$(this).data('holder', $(this).attr('placeholder'));
						$(this).attr('placeholder', '');
						$(this).css('background-color', 'white');
					});
					$('.descInput').on('focusout', function() {
						$(this).attr('placeholder', $(this).data('holder'));
						$(this).css('background-color', '#FAFAFA');
					});
					if ($('#premiumExpired').val() === 'true') {
						Apprise('Your Premium account has expired!');
					}
					/*
					 * $('#listOfFoods .mealToEdit:nth-child(10)').after('<input
					 * readonly type = "text" id = "maxFoods" value = "Food(s)
					 * below will not be displayed on your BiteLite page,
					 * because regular account.">');
					 */
				});