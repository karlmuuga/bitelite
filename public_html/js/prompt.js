var $Apprise = null, $overlay = null, $body = null, $window = null, $cA = null, AppriseQueue = [];
$(function() {
	$Apprise = $('<div class="apprise">');
	$overlay = $('<div class="apprise-overlay">');
	$body = $("body");
	$window = $(window);
	$body.append($overlay.css("opacity", ".94")).append($Apprise)
});
function Apprise(text, options) {
	if (text === undefined || !text) {
		return false
	}
	var $me = this, $_inner = $('<div class="apprise-inner">'), $_buttons = $('<div class="apprise-buttons">'), $_input = $('<input type="text">');
	var settings = {
		animation : 700,
		buttons : {
			confirm : {
				action : function() {
					$me.dissapear()
				},
				className : null,
				id : "confirm",
				text : "I understand"
			}
		},
		input : false,
		override : true
	};
	$.extend(settings, options);
	if (text == "close") {
		$cA.dissapear();
		return
	}
	if ($Apprise.is(":visible")) {
		AppriseQueue.push({
			text : text,
			options : settings
		});
		return
	}
	this.adjustWidth = function() {
		var window_width = $window.width(), w = "20%", l = "40%";
		if (window_width <= 800) {
			w = "90%", l = "5%"
		} else {
			if (window_width <= 1400 && window_width > 800) {
				w = "70%", l = "15%"
			} else {
				if (window_width <= 1800 && window_width > 1400) {
					w = "50%", l = "25%"
				} else {
					if (window_width <= 2200 && window_width > 1800) {
						w = "30%", l = "35%"
					}
				}
			}
		}
		$Apprise.css("width", w).css("left", l)
	};
	this.dissapear = function() {
		$Apprise.animate({
			top : "-100%"
		}, settings.animation, function() {
			$overlay.fadeOut(300);
			$Apprise.hide();
			$window.unbind("beforeunload");
			$window.unbind("keydown");
			if (AppriseQueue[0]) {
				Apprise(AppriseQueue[0].text, AppriseQueue[0].options);
				AppriseQueue.splice(0, 1)
			}
		});
		return
	};
	this.keyPress = function() {
		$window.bind("keydown", function(e) {
			if (e.keyCode === 27) {
				if (settings.buttons.cancel) {
					$("#apprise-btn-" + settings.buttons.cancel.id).trigger(
							"click")
				} else {
					$me.dissapear()
				}
			} else {
				if (e.keyCode === 13) {
					if (settings.buttons.confirm) {
						$("#apprise-btn-" + settings.buttons.confirm.id)
								.trigger("click")
					} else {
						$me.dissapear()
					}
				}
			}
		})
	};
	$.each(settings.buttons, function(i, button) {
		if (button) {
			var $_button = $('<button id="apprise-btn-' + button.id + '">')
					.append(button.text);
			if (button.className) {
				$_button.addClass(button.className)
			}
			$_buttons.append($_button);
			$_button.on("click", function() {
				var response = {
					clicked : button,
					input : ($_input.val() ? $_input.val() : null)
				};
				button.action(response)
			})
		}
	});
	if (settings.override) {
		$window.bind("beforeunload", function(e) {
			return "An alert requires attention"
		})
	}
	$me.adjustWidth();
	$window.resize(function() {
		$me.adjustWidth()
	});
	$Apprise.html("").append(
			$_inner.append('<div class="apprise-content">' + text + "</div>"))
			.append($_buttons);
	$cA = this;
	if (settings.input) {
		$_inner.find(".apprise-content").append(
				$('<div class="apprise-input">').append($_input))
	}
	$overlay.fadeIn(300);
	$Apprise.show().animate({
		top : "20%"
	}, settings.animation, function() {
		$me.keyPress()
	});
	if (settings.input) {
		$_input.focus()
	}
};