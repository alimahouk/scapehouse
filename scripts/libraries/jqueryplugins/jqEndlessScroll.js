/**
 * Endless Scroll plugin for jQuery
 *
 * v1.3
 *
 * Copyright (c) 2008 Fred Wu
 *
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 */

/**
 * Usage:
 *
 * // using default options
 * $(document).endlessScroll();
 *
 * // using some custom options
 * $(document).endlessScroll({
 *   fireOnce: false,
 *   fireDelay: false,
 *   loader: "<div class=\"loading\"><div>",
 *   callback: function(){
 *     alert("test");
 *   }
 * });
 *
 * Configuration options:
 *
 * bottomPixels  integer          the number of pixels from the bottom of the page that triggers the event
 * fireOnce      boolean          only fire once until the execution of the current event is completed
 * fireDelay     integer          delay the subsequent firing, in milliseconds. 0 or false to disable delay.
 * loader        string           the HTML to be displayed during loading
 * data          string|function  plain HTML data, can be either a string or a function that returns a string
 * insertAfter   string           jQuery selector syntax: where to put the loader as well as the plain HTML data
 * callback      function         callback function, accepets one argument: fire sequence (the number of times
 *                                the event triggered during the current page session)
 * resetCounter  function         resets the fire sequence counter if the function returns true, this function
 *                                could also perform hook actions since it is applied at the start of the event
 * ceaseFire     function         stops the event (no more endless scrolling) if the function returns true
 *
 * Usage tips:
 *
 * The plugin is more useful when used with the callback function, which can then make AJAX calls to retrieve content.
 * The fire sequence argument (for the callback function) is useful for 'pagination'-like features.
 */

(function($){

	$.fn.endlessScroll = function(options){

		var defaults = {
			bottomPixels: 50,
			fireOnce: true,
			fireDelay: 150,
			loader: "<br />Loading...<br />",
			data: "",
			insertAfter: "div:last",
			resetCounter: function(){ return false; },
			callback: function(){ return true; },
			ceaseFire: function(){ return false; }
		};

		var options = $.extend(defaults, options);
		var firing       = true;
		var fired        = false;
		var fireSequence = 0;

		if(options.ceaseFire.apply(this) === true)
		{
			firing = false;
		}

		if (firing === true)
		{
			$(window).scroll(function(){
				if ($(document).height() - $(window).height() <= $(window).scrollTop() + options.bottomPixels)
				{
					if ((options.fireOnce == false || (options.fireOnce == true && fired != true)))
					{
						if(options.resetCounter.apply(this) === true)
						{
							fireSequence = 0;
						}

						fired = true;
						fireSequence++;

						$(options.insertAfter).after("<div style='display:block;' id='endless_scroll_loader'>" + options.loader + "</div>");

						if (typeof options.data == 'function')
						{
							data = options.data.apply(this);
						}
						else
						{
							data = options.data;
						}

						if (data !== false)
						{
							$("div#endless_scroll_loader").remove();
							$(options.insertAfter).after("<div id=\"endless_scroll_data\">" + data + "</div>");
							$("div#endless_scroll_data").hide().fadeIn();
							$("div#endless_scroll_data").removeAttr("id");

							var args = new Array();
							args[0] = fireSequence;
							options.callback.apply(this, args);

							if (options.fireDelay !== false || options.fireDelay !== 0)
							{
								// slight delay for preventing event firing twice
								$("body").after("<div id=\"endless_scroll_marker\"></div>");
								$("div#endless_scroll_marker").fadeTo(options.fireDelay, 1, function(){
									$(this).remove();
									fired = false;
								});
							}
							else
							{
								fired = false;
							}
						}
					}
				}
			});
		}
	};

})(jQuery);