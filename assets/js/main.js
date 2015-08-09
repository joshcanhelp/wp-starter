require=(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
/* globals jQuery, alert, console, require, pccLocalVars */

var pccHelpers = require('./pccHelpers');
var remodal = require('remodal');

jQuery(document).ready(function ($) {

	// Easier to type, right?

	var PCC = pccLocalVars;

	//
	// Main menu - "Big Nav"
	//

	var prodNavItem = $('#menu-item-products');
	var prodNav = $('#products-nav-main');
	var menuTimeout;

	$('#menu-item-products, #products-nav-main').hover(function (e) {

		// End the timed hover

		clearTimeout(menuTimeout);

		// Move img URLs from data-src to src to prevent loading every time

		if (! prodNav.attr('data-loaded')) {
			prodNav.find('img').each(function () {
				var thisImg = $(this);
				thisImg.attr('src', thisImg.attr('data-src'));
				prodNav.attr('data-loaded', 1)
			});
		}

		prodNavItem.addClass('sub-nav-active');
		prodNav.show();
		$('#popular-categories').show();

	}, function () {

		// Prevent menu flashing in and out

		menuTimeout = window.setTimeout(function () {
			prodNavItem.removeClass('sub-nav-active');
			prodNav.hide();
		}, 500);

	});

	// Children hover effects

	prodNav.find('> li a, .children').hover(function () {
		var parent = $(this).parent();

		$('#popular-categories').hide();
		parent.find('.children').show();
		parent.find('> a').addClass('child-active');
	}, function () {
		var parent = $(this).parent();

		parent.find('.children').hide();
		parent.find('> a').removeClass('child-active');
	});

	// Kill the product nav if another nav item is hovered over

	$('#site-main-menu').find('.menu-item').hover(function () {
		clearTimeout(menuTimeout);
		prodNavItem.removeClass('sub-nav-active');
		prodNav.hide();
	});




	//
	// Product listing
	//

	// Open/close product category nav

	$('#sidebar-products').find('.product-cat-listing .children').parent()
		.prepend($('<i>').addClass('icon-up-dir'))
		.on( 'click', '.icon-up-dir', function (e) {
			var thisIcon = $(this);
			thisIcon.parent().find('.children').slideDown('fast');
			thisIcon.removeClass('icon-up-dir').addClass('icon-down-dir')
		})
		.on( 'click', '.icon-down-dir', function (e) {
			var thisIcon = $(this);
			thisIcon.parent().find('.children').slideUp('fast');
			thisIcon.removeClass('icon-down-dir').addClass('icon-up-dir')
		});

	//
	// Single products
	//

	// Hide recommended box if no products

	var recommendProds = $('#recommended-products');
	if (!recommendProds.length || !recommendProds.find('.product-wrap').length) {
		recommendProds.hide();
	}

	// Show product notes

	$('.product-show-notes').click(function (e) {
		e.preventDefault();
		e.stopPropagation();

		var target = $(this);

		target.addClass('hidden');
		if (target.attr('data-pid')) {
			$('#notes-row-' + target.attr('data-pid')).removeClass('hidden');
		} else {
			$('#product-notes').slideDown('fast');
		}

	});

	//
	// Request pricing page
	//

	var requestList = $('#request-pricing-list');
	if (typeof requestList !== 'undefined') {

		// Hide notes field and show button if there are no notes

		requestList.find('textarea').each(function () {

			var thisTextarea = $(this);
			var prodId = thisTextarea.attr('id').replace('item-notes-', '');

			if (!thisTextarea.text()) {
				$('#notes-row-' + prodId).addClass('hidden');
			} else {
				$('[data-pid=' + prodId + ']').addClass('hidden');
			}
		})
	}

	//
	// Account page
	//

	var acctRequestList = $('#account-pricing-req');
	if (typeof acctRequestList !== 'undefined') {

		// Hide request items, show button

		acctRequestList.find('.items-row').each(function () {

			var thisRow = $(this);
			var prodId = thisRow.attr('id').replace('request-items-', '');

			thisRow.addClass('hidden');
			$('#request-order-' + prodId).find('td.col-req-count').append(
				$('<a>').attr('href', '#').attr('data-pid', prodId).text('Show').addClass('request-show-items')
			);
		});

		// Show order items

		$('.request-show-items').click(function (e) {
			e.preventDefault();
			e.stopPropagation();

			var target = $(this);

			target.addClass('hidden');
			$('#request-items-' + target.attr('data-pid')).removeClass('hidden');

		});

		// Populate remodal contact form

		$('a[data-remodal-target="request-contact"]').click(function (e) {

			console.log('got it');

			var reqId = $(e.target).attr('data-req-id');

			$('#contact-request-number').text(reqId);
			$('#pcc-contact-request-number').val(reqId);
		});
	}


	//
	// AJAX search
	//

	var ajaxSearch = $('#ajax-search');
	if (typeof ajaxSearch !== 'undefined') {

		ajaxSearch.keyup(function (e) {

			var results = $('#search-results');
			var searchKeys = [8,32,46];

			if (
				searchKeys.indexOf(e.which) < 0 &&
				( e.which < 48 || e.which > 90 ) &&
				( e.which < 96 || e.which > 111 ) &&
				( e.which < 186 || e.which > 222 )
			) {
				results.html('');
				return false;
			}

			var query = ajaxSearch.val();

			if (query.length > 1) {

				results.html('').append('<li>searching...</li>');
				var searchUrl = PCC.jsonSearchUrl + query;

				$.get(searchUrl, function (data) {
					if(data.length) {

						results.html('');
						for (var i = 0; i < (data.length > 10 ? 10 : data.length); i++) {
							results.append('<li><a href="' + data[i].link + '">' + data[i].title + '</a></li>');
						}
					} else {
						results.html('<li>No results!</li>');
					}
				});

			} else {
				results.html('');
			}
		});

		var query = ajaxSearch.val();

	}

});


},{"./pccHelpers":2,"remodal":"remodal"}],2:[function(require,module,exports){
/* globals jQuery, alert, console, module, pccLocalVars */

var $ = jQuery;

module.exports = {

	/**
	 * Sets column height to be equal for children nodes
	 *
	 * @param wrapper
	 */
	equalColHeight:  function (wrapper) {
		var colHeight = 0;
		wrapper.children().each(function (index, el) {
			var thisHeight = $(el).height();
			if (thisHeight > colHeight) {
				colHeight = thisHeight;
			}
		});
		wrapper.children().height(colHeight);
	}
};

},{}],3:[function(require,module,exports){

},{}],"pikaday":[function(require,module,exports){
/*!
 * Pikaday
 *
 * Copyright © 2014 David Bushell | BSD & MIT license | https://github.com/dbushell/Pikaday
 */

(function (root, factory)
{
    'use strict';

    var moment;
    if (typeof exports === 'object') {
        // CommonJS module
        // Load moment.js as an optional dependency
        try { moment = require('moment'); } catch (e) {}
        module.exports = factory(moment);
    } else if (typeof define === 'function' && define.amd) {
        // AMD. Register as an anonymous module.
        define(function (req)
        {
            // Load moment.js as an optional dependency
            var id = 'moment';
            try { moment = req(id); } catch (e) {}
            return factory(moment);
        });
    } else {
        root.Pikaday = factory(root.moment);
    }
}(this, function (moment)
{
    'use strict';

    /**
     * feature detection and helper functions
     */
    var hasMoment = typeof moment === 'function',

    hasEventListeners = !!window.addEventListener,

    document = window.document,

    sto = window.setTimeout,

    addEvent = function(el, e, callback, capture)
    {
        if (hasEventListeners) {
            el.addEventListener(e, callback, !!capture);
        } else {
            el.attachEvent('on' + e, callback);
        }
    },

    removeEvent = function(el, e, callback, capture)
    {
        if (hasEventListeners) {
            el.removeEventListener(e, callback, !!capture);
        } else {
            el.detachEvent('on' + e, callback);
        }
    },

    fireEvent = function(el, eventName, data)
    {
        var ev;

        if (document.createEvent) {
            ev = document.createEvent('HTMLEvents');
            ev.initEvent(eventName, true, false);
            ev = extend(ev, data);
            el.dispatchEvent(ev);
        } else if (document.createEventObject) {
            ev = document.createEventObject();
            ev = extend(ev, data);
            el.fireEvent('on' + eventName, ev);
        }
    },

    trim = function(str)
    {
        return str.trim ? str.trim() : str.replace(/^\s+|\s+$/g,'');
    },

    hasClass = function(el, cn)
    {
        return (' ' + el.className + ' ').indexOf(' ' + cn + ' ') !== -1;
    },

    addClass = function(el, cn)
    {
        if (!hasClass(el, cn)) {
            el.className = (el.className === '') ? cn : el.className + ' ' + cn;
        }
    },

    removeClass = function(el, cn)
    {
        el.className = trim((' ' + el.className + ' ').replace(' ' + cn + ' ', ' '));
    },

    isArray = function(obj)
    {
        return (/Array/).test(Object.prototype.toString.call(obj));
    },

    isDate = function(obj)
    {
        return (/Date/).test(Object.prototype.toString.call(obj)) && !isNaN(obj.getTime());
    },

    isWeekend = function(date)
    {
        var day = date.getDay();
        return day === 0 || day === 6;
    },

    isLeapYear = function(year)
    {
        // solution by Matti Virkkunen: http://stackoverflow.com/a/4881951
        return year % 4 === 0 && year % 100 !== 0 || year % 400 === 0;
    },

    getDaysInMonth = function(year, month)
    {
        return [31, isLeapYear(year) ? 29 : 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31][month];
    },

    setToStartOfDay = function(date)
    {
        if (isDate(date)) date.setHours(0,0,0,0);
    },

    compareDates = function(a,b)
    {
        // weak date comparison (use setToStartOfDay(date) to ensure correct result)
        return a.getTime() === b.getTime();
    },

    extend = function(to, from, overwrite)
    {
        var prop, hasProp;
        for (prop in from) {
            hasProp = to[prop] !== undefined;
            if (hasProp && typeof from[prop] === 'object' && from[prop] !== null && from[prop].nodeName === undefined) {
                if (isDate(from[prop])) {
                    if (overwrite) {
                        to[prop] = new Date(from[prop].getTime());
                    }
                }
                else if (isArray(from[prop])) {
                    if (overwrite) {
                        to[prop] = from[prop].slice(0);
                    }
                } else {
                    to[prop] = extend({}, from[prop], overwrite);
                }
            } else if (overwrite || !hasProp) {
                to[prop] = from[prop];
            }
        }
        return to;
    },

    adjustCalendar = function(calendar) {
        if (calendar.month < 0) {
            calendar.year -= Math.ceil(Math.abs(calendar.month)/12);
            calendar.month += 12;
        }
        if (calendar.month > 11) {
            calendar.year += Math.floor(Math.abs(calendar.month)/12);
            calendar.month -= 12;
        }
        return calendar;
    },

    /**
     * defaults and localisation
     */
    defaults = {

        // bind the picker to a form field
        field: null,

        // automatically show/hide the picker on `field` focus (default `true` if `field` is set)
        bound: undefined,

        // position of the datepicker, relative to the field (default to bottom & left)
        // ('bottom' & 'left' keywords are not used, 'top' & 'right' are modifier on the bottom/left position)
        position: 'bottom left',

        // automatically fit in the viewport even if it means repositioning from the position option
        reposition: true,

        // the default output format for `.toString()` and `field` value
        format: 'YYYY-MM-DD',

        // the initial date to view when first opened
        defaultDate: null,

        // make the `defaultDate` the initial selected value
        setDefaultDate: false,

        // first day of week (0: Sunday, 1: Monday etc)
        firstDay: 0,

        // the minimum/earliest date that can be selected
        minDate: null,
        // the maximum/latest date that can be selected
        maxDate: null,

        // number of years either side, or array of upper/lower range
        yearRange: 10,

        // show week numbers at head of row
        showWeekNumber: false,

        // used internally (don't config outside)
        minYear: 0,
        maxYear: 9999,
        minMonth: undefined,
        maxMonth: undefined,

        isRTL: false,

        // Additional text to append to the year in the calendar title
        yearSuffix: '',

        // Render the month after year in the calendar title
        showMonthAfterYear: false,

        // how many months are visible
        numberOfMonths: 1,

        // when numberOfMonths is used, this will help you to choose where the main calendar will be (default `left`, can be set to `right`)
        // only used for the first display or when a selected date is not visible
        mainCalendar: 'left',

        // Specify a DOM element to render the calendar in
        container: undefined,

        // internationalization
        i18n: {
            previousMonth : 'Previous Month',
            nextMonth     : 'Next Month',
            months        : ['January','February','March','April','May','June','July','August','September','October','November','December'],
            weekdays      : ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'],
            weekdaysShort : ['Sun','Mon','Tue','Wed','Thu','Fri','Sat']
        },

        // Theme Classname
        theme: null,

        // callback function
        onSelect: null,
        onOpen: null,
        onClose: null,
        onDraw: null
    },


    /**
     * templating functions to abstract HTML rendering
     */
    renderDayName = function(opts, day, abbr)
    {
        day += opts.firstDay;
        while (day >= 7) {
            day -= 7;
        }
        return abbr ? opts.i18n.weekdaysShort[day] : opts.i18n.weekdays[day];
    },

    renderDay = function(d, m, y, isSelected, isToday, isDisabled, isEmpty)
    {
        if (isEmpty) {
            return '<td class="is-empty"></td>';
        }
        var arr = [];
        if (isDisabled) {
            arr.push('is-disabled');
        }
        if (isToday) {
            arr.push('is-today');
        }
        if (isSelected) {
            arr.push('is-selected');
        }
        return '<td data-day="' + d + '" class="' + arr.join(' ') + '">' +
                 '<button class="pika-button pika-day" type="button" ' +
                    'data-pika-year="' + y + '" data-pika-month="' + m + '" data-pika-day="' + d + '">' +
                        d +
                 '</button>' +
               '</td>';
    },

    renderWeek = function (d, m, y) {
        // Lifted from http://javascript.about.com/library/blweekyear.htm, lightly modified.
        var onejan = new Date(y, 0, 1),
            weekNum = Math.ceil((((new Date(y, m, d) - onejan) / 86400000) + onejan.getDay()+1)/7);
        return '<td class="pika-week">' + weekNum + '</td>';
    },

    renderRow = function(days, isRTL)
    {
        return '<tr>' + (isRTL ? days.reverse() : days).join('') + '</tr>';
    },

    renderBody = function(rows)
    {
        return '<tbody>' + rows.join('') + '</tbody>';
    },

    renderHead = function(opts)
    {
        var i, arr = [];
        if (opts.showWeekNumber) {
            arr.push('<th></th>');
        }
        for (i = 0; i < 7; i++) {
            arr.push('<th scope="col"><abbr title="' + renderDayName(opts, i) + '">' + renderDayName(opts, i, true) + '</abbr></th>');
        }
        return '<thead>' + (opts.isRTL ? arr.reverse() : arr).join('') + '</thead>';
    },

    renderTitle = function(instance, c, year, month, refYear)
    {
        var i, j, arr,
            opts = instance._o,
            isMinYear = year === opts.minYear,
            isMaxYear = year === opts.maxYear,
            html = '<div class="pika-title">',
            monthHtml,
            yearHtml,
            prev = true,
            next = true;

        for (arr = [], i = 0; i < 12; i++) {
            arr.push('<option value="' + (year === refYear ? i - c : 12 + i - c) + '"' +
                (i === month ? ' selected': '') +
                ((isMinYear && i < opts.minMonth) || (isMaxYear && i > opts.maxMonth) ? 'disabled' : '') + '>' +
                opts.i18n.months[i] + '</option>');
        }
        monthHtml = '<div class="pika-label">' + opts.i18n.months[month] + '<select class="pika-select pika-select-month" tabindex="-1">' + arr.join('') + '</select></div>';

        if (isArray(opts.yearRange)) {
            i = opts.yearRange[0];
            j = opts.yearRange[1] + 1;
        } else {
            i = year - opts.yearRange;
            j = 1 + year + opts.yearRange;
        }

        for (arr = []; i < j && i <= opts.maxYear; i++) {
            if (i >= opts.minYear) {
                arr.push('<option value="' + i + '"' + (i === year ? ' selected': '') + '>' + (i) + '</option>');
            }
        }
        yearHtml = '<div class="pika-label">' + year + opts.yearSuffix + '<select class="pika-select pika-select-year" tabindex="-1">' + arr.join('') + '</select></div>';

        if (opts.showMonthAfterYear) {
            html += yearHtml + monthHtml;
        } else {
            html += monthHtml + yearHtml;
        }

        if (isMinYear && (month === 0 || opts.minMonth >= month)) {
            prev = false;
        }

        if (isMaxYear && (month === 11 || opts.maxMonth <= month)) {
            next = false;
        }

        if (c === 0) {
            html += '<button class="pika-prev' + (prev ? '' : ' is-disabled') + '" type="button">' + opts.i18n.previousMonth + '</button>';
        }
        if (c === (instance._o.numberOfMonths - 1) ) {
            html += '<button class="pika-next' + (next ? '' : ' is-disabled') + '" type="button">' + opts.i18n.nextMonth + '</button>';
        }

        return html += '</div>';
    },

    renderTable = function(opts, data)
    {
        return '<table cellpadding="0" cellspacing="0" class="pika-table">' + renderHead(opts) + renderBody(data) + '</table>';
    },


    /**
     * Pikaday constructor
     */
    Pikaday = function(options)
    {
        var self = this,
            opts = self.config(options);

        self._onMouseDown = function(e)
        {
            if (!self._v) {
                return;
            }
            e = e || window.event;
            var target = e.target || e.srcElement;
            if (!target) {
                return;
            }

            if (!hasClass(target.parentNode, 'is-disabled')) {
                if (hasClass(target, 'pika-button') && !hasClass(target, 'is-empty')) {
                    self.setDate(new Date(target.getAttribute('data-pika-year'), target.getAttribute('data-pika-month'), target.getAttribute('data-pika-day')));
                    if (opts.bound) {
                        sto(function() {
                            self.hide();
                            if (opts.field) {
                                opts.field.blur();
                            }
                        }, 100);
                    }
                    return;
                }
                else if (hasClass(target, 'pika-prev')) {
                    self.prevMonth();
                }
                else if (hasClass(target, 'pika-next')) {
                    self.nextMonth();
                }
            }
            if (!hasClass(target, 'pika-select')) {
                if (e.preventDefault) {
                    e.preventDefault();
                } else {
                    e.returnValue = false;
                    return false;
                }
            } else {
                self._c = true;
            }
        };

        self._onChange = function(e)
        {
            e = e || window.event;
            var target = e.target || e.srcElement;
            if (!target) {
                return;
            }
            if (hasClass(target, 'pika-select-month')) {
                self.gotoMonth(target.value);
            }
            else if (hasClass(target, 'pika-select-year')) {
                self.gotoYear(target.value);
            }
        };

        self._onInputChange = function(e)
        {
            var date;

            if (e.firedBy === self) {
                return;
            }
            if (hasMoment) {
                date = moment(opts.field.value, opts.format);
                date = (date && date.isValid()) ? date.toDate() : null;
            }
            else {
                date = new Date(Date.parse(opts.field.value));
            }
            if (isDate(date)) {
              self.setDate(date)
            }
            if (!self._v) {
                self.show();
            }
        };

        self._onInputFocus = function()
        {
            self.show();
        };

        self._onInputClick = function()
        {
            self.show();
        };

        self._onInputBlur = function()
        {
            // IE allows pika div to gain focus; catch blur the input field
            var pEl = document.activeElement;
            do {
                if (hasClass(pEl, 'pika-single')) {
                    return;
                }
            }
            while ((pEl = pEl.parentNode));

            if (!self._c) {
                self._b = sto(function() {
                    self.hide();
                }, 50);
            }
            self._c = false;
        };

        self._onClick = function(e)
        {
            e = e || window.event;
            var target = e.target || e.srcElement,
                pEl = target;
            if (!target) {
                return;
            }
            if (!hasEventListeners && hasClass(target, 'pika-select')) {
                if (!target.onchange) {
                    target.setAttribute('onchange', 'return;');
                    addEvent(target, 'change', self._onChange);
                }
            }
            do {
                if (hasClass(pEl, 'pika-single') || pEl === opts.trigger) {
                    return;
                }
            }
            while ((pEl = pEl.parentNode));
            if (self._v && target !== opts.trigger && pEl !== opts.trigger) {
                self.hide();
            }
        };

        self.el = document.createElement('div');
        self.el.className = 'pika-single' + (opts.isRTL ? ' is-rtl' : '') + (opts.theme ? ' ' + opts.theme : '');

        addEvent(self.el, 'ontouchend' in document ? 'touchend' : 'mousedown', self._onMouseDown, true);
        addEvent(self.el, 'change', self._onChange);

        if (opts.field) {
            if (opts.container) {
                opts.container.appendChild(self.el);
            } else if (opts.bound) {
                document.body.appendChild(self.el);
            } else {
                opts.field.parentNode.insertBefore(self.el, opts.field.nextSibling);
            }
            addEvent(opts.field, 'change', self._onInputChange);

            if (!opts.defaultDate) {
                if (hasMoment && opts.field.value) {
                    opts.defaultDate = moment(opts.field.value, opts.format).toDate();
                } else {
                    opts.defaultDate = new Date(Date.parse(opts.field.value));
                }
                opts.setDefaultDate = true;
            }
        }

        var defDate = opts.defaultDate;

        if (isDate(defDate)) {
            if (opts.setDefaultDate) {
                self.setDate(defDate, true);
            } else {
                self.gotoDate(defDate);
            }
        } else {
            self.gotoDate(new Date());
        }

        if (opts.bound) {
            this.hide();
            self.el.className += ' is-bound';
            addEvent(opts.trigger, 'click', self._onInputClick);
            addEvent(opts.trigger, 'focus', self._onInputFocus);
            addEvent(opts.trigger, 'blur', self._onInputBlur);
        } else {
            this.show();
        }
    };


    /**
     * public Pikaday API
     */
    Pikaday.prototype = {


        /**
         * configure functionality
         */
        config: function(options)
        {
            if (!this._o) {
                this._o = extend({}, defaults, true);
            }

            var opts = extend(this._o, options, true);

            opts.isRTL = !!opts.isRTL;

            opts.field = (opts.field && opts.field.nodeName) ? opts.field : null;

            opts.theme = (typeof opts.theme) == 'string' && opts.theme ? opts.theme : null;

            opts.bound = !!(opts.bound !== undefined ? opts.field && opts.bound : opts.field);

            opts.trigger = (opts.trigger && opts.trigger.nodeName) ? opts.trigger : opts.field;

            opts.disableWeekends = !!opts.disableWeekends;

            opts.disableDayFn = (typeof opts.disableDayFn) == "function" ? opts.disableDayFn : null;

            var nom = parseInt(opts.numberOfMonths, 10) || 1;
            opts.numberOfMonths = nom > 4 ? 4 : nom;

            if (!isDate(opts.minDate)) {
                opts.minDate = false;
            }
            if (!isDate(opts.maxDate)) {
                opts.maxDate = false;
            }
            if ((opts.minDate && opts.maxDate) && opts.maxDate < opts.minDate) {
                opts.maxDate = opts.minDate = false;
            }
            if (opts.minDate) {
                this.setMinDate(opts.minDate)
            }
            if (opts.maxDate) {
                setToStartOfDay(opts.maxDate);
                opts.maxYear  = opts.maxDate.getFullYear();
                opts.maxMonth = opts.maxDate.getMonth();
            }

            if (isArray(opts.yearRange)) {
                var fallback = new Date().getFullYear() - 10;
                opts.yearRange[0] = parseInt(opts.yearRange[0], 10) || fallback;
                opts.yearRange[1] = parseInt(opts.yearRange[1], 10) || fallback;
            } else {
                opts.yearRange = Math.abs(parseInt(opts.yearRange, 10)) || defaults.yearRange;
                if (opts.yearRange > 100) {
                    opts.yearRange = 100;
                }
            }

            return opts;
        },

        /**
         * return a formatted string of the current selection (using Moment.js if available)
         */
        toString: function(format)
        {
            return !isDate(this._d) ? '' : hasMoment ? moment(this._d).format(format || this._o.format) : this._d.toDateString();
        },

        /**
         * return a Moment.js object of the current selection (if available)
         */
        getMoment: function()
        {
            return hasMoment ? moment(this._d) : null;
        },

        /**
         * set the current selection from a Moment.js object (if available)
         */
        setMoment: function(date, preventOnSelect)
        {
            if (hasMoment && moment.isMoment(date)) {
                this.setDate(date.toDate(), preventOnSelect);
            }
        },

        /**
         * return a Date object of the current selection
         */
        getDate: function()
        {
            return isDate(this._d) ? new Date(this._d.getTime()) : null;
        },

        /**
         * set the current selection
         */
        setDate: function(date, preventOnSelect)
        {
            if (!date) {
                this._d = null;

                if (this._o.field) {
                    this._o.field.value = '';
                    fireEvent(this._o.field, 'change', { firedBy: this });
                }

                return this.draw();
            }
            if (typeof date === 'string') {
                date = new Date(Date.parse(date));
            }
            if (!isDate(date)) {
                return;
            }

            var min = this._o.minDate,
                max = this._o.maxDate;

            if (isDate(min) && date < min) {
                date = min;
            } else if (isDate(max) && date > max) {
                date = max;
            }

            this._d = new Date(date.getTime());
            setToStartOfDay(this._d);
            this.gotoDate(this._d);

            if (this._o.field) {
                this._o.field.value = this.toString();
                fireEvent(this._o.field, 'change', { firedBy: this });
            }
            if (!preventOnSelect && typeof this._o.onSelect === 'function') {
                this._o.onSelect.call(this, this.getDate());
            }
        },

        /**
         * change view to a specific date
         */
        gotoDate: function(date)
        {
            var newCalendar = true;

            if (!isDate(date)) {
                return;
            }

            if (this.calendars) {
                var firstVisibleDate = new Date(this.calendars[0].year, this.calendars[0].month, 1),
                    lastVisibleDate = new Date(this.calendars[this.calendars.length-1].year, this.calendars[this.calendars.length-1].month, 1),
                    visibleDate = date.getTime();
                // get the end of the month
                lastVisibleDate.setMonth(lastVisibleDate.getMonth()+1);
                lastVisibleDate.setDate(lastVisibleDate.getDate()-1);
                newCalendar = (visibleDate < firstVisibleDate.getTime() || lastVisibleDate.getTime() < visibleDate);
            }

            if (newCalendar) {
                this.calendars = [{
                    month: date.getMonth(),
                    year: date.getFullYear()
                }];
                if (this._o.mainCalendar === 'right') {
                    this.calendars[0].month += 1 - this._o.numberOfMonths;
                }
            }

            this.adjustCalendars();
        },

        adjustCalendars: function() {
            this.calendars[0] = adjustCalendar(this.calendars[0]);
            for (var c = 1; c < this._o.numberOfMonths; c++) {
                this.calendars[c] = adjustCalendar({
                    month: this.calendars[0].month + c,
                    year: this.calendars[0].year
                });
            }
            this.draw();
        },

        gotoToday: function()
        {
            this.gotoDate(new Date());
        },

        /**
         * change view to a specific month (zero-index, e.g. 0: January)
         */
        gotoMonth: function(month)
        {
            if (!isNaN(month)) {
                this.calendars[0].month = parseInt(month, 10);
                this.adjustCalendars();
            }
        },

        nextMonth: function()
        {
            this.calendars[0].month++;
            this.adjustCalendars();
        },

        prevMonth: function()
        {
            this.calendars[0].month--;
            this.adjustCalendars();
        },

        /**
         * change view to a specific full year (e.g. "2012")
         */
        gotoYear: function(year)
        {
            if (!isNaN(year)) {
                this.calendars[0].year = parseInt(year, 10);
                this.adjustCalendars();
            }
        },

        /**
         * change the minDate
         */
        setMinDate: function(value)
        {
            setToStartOfDay(value);
            this._o.minDate = value;
            this._o.minYear  = value.getFullYear();
            this._o.minMonth = value.getMonth();
        },

        /**
         * change the maxDate
         */
        setMaxDate: function(value)
        {
            this._o.maxDate = value;
        },

        /**
         * refresh the HTML
         */
        draw: function(force)
        {
            if (!this._v && !force) {
                return;
            }
            var opts = this._o,
                minYear = opts.minYear,
                maxYear = opts.maxYear,
                minMonth = opts.minMonth,
                maxMonth = opts.maxMonth,
                html = '';

            if (this._y <= minYear) {
                this._y = minYear;
                if (!isNaN(minMonth) && this._m < minMonth) {
                    this._m = minMonth;
                }
            }
            if (this._y >= maxYear) {
                this._y = maxYear;
                if (!isNaN(maxMonth) && this._m > maxMonth) {
                    this._m = maxMonth;
                }
            }

            for (var c = 0; c < opts.numberOfMonths; c++) {
                html += '<div class="pika-lendar">' + renderTitle(this, c, this.calendars[c].year, this.calendars[c].month, this.calendars[0].year) + this.render(this.calendars[c].year, this.calendars[c].month) + '</div>';
            }

            this.el.innerHTML = html;

            if (opts.bound) {
                if(opts.field.type !== 'hidden') {
                    sto(function() {
                        opts.trigger.focus();
                    }, 1);
                }
            }

            if (typeof this._o.onDraw === 'function') {
                var self = this;
                sto(function() {
                    self._o.onDraw.call(self);
                }, 0);
            }
        },

        adjustPosition: function()
        {
            var field, pEl, width, height, viewportWidth, viewportHeight, scrollTop, left, top, clientRect;
            
            if (this._o.container) return;
            
            this.el.style.position = 'absolute';
            
            field = this._o.trigger;
            pEl = field;
            width = this.el.offsetWidth;
            height = this.el.offsetHeight;
            viewportWidth = window.innerWidth || document.documentElement.clientWidth;
            viewportHeight = window.innerHeight || document.documentElement.clientHeight;
            scrollTop = window.pageYOffset || document.body.scrollTop || document.documentElement.scrollTop;

            if (typeof field.getBoundingClientRect === 'function') {
                clientRect = field.getBoundingClientRect();
                left = clientRect.left + window.pageXOffset;
                top = clientRect.bottom + window.pageYOffset;
            } else {
                left = pEl.offsetLeft;
                top  = pEl.offsetTop + pEl.offsetHeight;
                while((pEl = pEl.offsetParent)) {
                    left += pEl.offsetLeft;
                    top  += pEl.offsetTop;
                }
            }

            // default position is bottom & left
            if ((this._o.reposition && left + width > viewportWidth) ||
                (
                    this._o.position.indexOf('right') > -1 &&
                    left - width + field.offsetWidth > 0
                )
            ) {
                left = left - width + field.offsetWidth;
            }
            if ((this._o.reposition && top + height > viewportHeight + scrollTop) ||
                (
                    this._o.position.indexOf('top') > -1 &&
                    top - height - field.offsetHeight > 0
                )
            ) {
                top = top - height - field.offsetHeight;
            }

            this.el.style.left = left + 'px';
            this.el.style.top = top + 'px';
        },

        /**
         * render HTML for a particular month
         */
        render: function(year, month)
        {
            var opts   = this._o,
                now    = new Date(),
                days   = getDaysInMonth(year, month),
                before = new Date(year, month, 1).getDay(),
                data   = [],
                row    = [];
            setToStartOfDay(now);
            if (opts.firstDay > 0) {
                before -= opts.firstDay;
                if (before < 0) {
                    before += 7;
                }
            }
            var cells = days + before,
                after = cells;
            while(after > 7) {
                after -= 7;
            }
            cells += 7 - after;
            for (var i = 0, r = 0; i < cells; i++)
            {
                var day = new Date(year, month, 1 + (i - before)),
                    isSelected = isDate(this._d) ? compareDates(day, this._d) : false,
                    isToday = compareDates(day, now),
                    isEmpty = i < before || i >= (days + before),
                    isDisabled = (opts.minDate && day < opts.minDate) ||
                                 (opts.maxDate && day > opts.maxDate) ||
                                 (opts.disableWeekends && isWeekend(day)) ||
                                 (opts.disableDayFn && opts.disableDayFn(day));

                row.push(renderDay(1 + (i - before), month, year, isSelected, isToday, isDisabled, isEmpty));

                if (++r === 7) {
                    if (opts.showWeekNumber) {
                        row.unshift(renderWeek(i - before, month, year));
                    }
                    data.push(renderRow(row, opts.isRTL));
                    row = [];
                    r = 0;
                }
            }
            return renderTable(opts, data);
        },

        isVisible: function()
        {
            return this._v;
        },

        show: function()
        {
            if (!this._v) {
                removeClass(this.el, 'is-hidden');
                this._v = true;
                this.draw();
                if (this._o.bound) {
                    addEvent(document, 'click', this._onClick);
                    this.adjustPosition();
                }
                if (typeof this._o.onOpen === 'function') {
                    this._o.onOpen.call(this);
                }
            }
        },

        hide: function()
        {
            var v = this._v;
            if (v !== false) {
                if (this._o.bound) {
                    removeEvent(document, 'click', this._onClick);
                }
                this.el.style.position = 'static'; // reset
                this.el.style.left = 'auto';
                this.el.style.top = 'auto';
                addClass(this.el, 'is-hidden');
                this._v = false;
                if (v !== undefined && typeof this._o.onClose === 'function') {
                    this._o.onClose.call(this);
                }
            }
        },

        /**
         * GAME OVER
         */
        destroy: function()
        {
            this.hide();
            removeEvent(this.el, 'mousedown', this._onMouseDown, true);
            removeEvent(this.el, 'change', this._onChange);
            if (this._o.field) {
                removeEvent(this._o.field, 'change', this._onInputChange);
                if (this._o.bound) {
                    removeEvent(this._o.trigger, 'click', this._onInputClick);
                    removeEvent(this._o.trigger, 'focus', this._onInputFocus);
                    removeEvent(this._o.trigger, 'blur', this._onInputBlur);
                }
            }
            if (this.el.parentNode) {
                this.el.parentNode.removeChild(this.el);
            }
        }

    };

    return Pikaday;

}));

},{"moment":3}],"remodal":[function(require,module,exports){
(function (global){
/*
 *  Remodal - v0.6.4
 *  Flat, responsive, lightweight, easy customizable modal window plugin with declarative state notation and hash tracking.
 *  http://vodkabears.github.io/remodal/
 *
 *  Made by Ilya Makarov
 *  Under MIT License
 */
(function(root, factory) {
  if (typeof define === 'function' && define.amd) {
    define(['jquery'], function($) {
      return factory(root, $);
    });
  } else if (typeof exports === 'object') {
    factory(root, (typeof window !== "undefined" ? window.jQuery : typeof global !== "undefined" ? global.jQuery : null));
  } else {
    factory(root, root.jQuery || root.Zepto);
  }
})(this, function(global, $) {

  'use strict';

  /**
   * Name of the plugin
   * @private
   * @type {String}
   */
  var pluginName = 'remodal';

  /**
   * Namespace for CSS and events
   * @private
   * @type {String}
   */
  var namespace = global.remodalGlobals && global.remodalGlobals.namespace || pluginName;

  /**
   * Default settings
   * @private
   * @type {Object}
   */
  var defaults = $.extend({
    hashTracking: true,
    closeOnConfirm: true,
    closeOnCancel: true,
    closeOnEscape: true,
    closeOnAnyClick: true
  }, global.remodalGlobals && global.remodalGlobals.defaults);

  /**
   * Current modal
   * @private
   * @type {Remodal}
   */
  var current;

  /**
   * Scrollbar position
   * @private
   * @type {Number}
   */
  var scrollTop;

  /**
   * Get a transition duration in ms
   * @private
   * @param {jQuery} $elem
   * @return {Number}
   */
  function getTransitionDuration($elem) {
    var duration = $elem.css('transition-duration') ||
        $elem.css('-webkit-transition-duration') ||
        $elem.css('-moz-transition-duration') ||
        $elem.css('-o-transition-duration') ||
        $elem.css('-ms-transition-duration') ||
        '0s';

    var delay = $elem.css('transition-delay') ||
        $elem.css('-webkit-transition-delay') ||
        $elem.css('-moz-transition-delay') ||
        $elem.css('-o-transition-delay') ||
        $elem.css('-ms-transition-delay') ||
        '0s';

    var max;
    var len;
    var num;
    var i;

    duration = duration.split(', ');
    delay = delay.split(', ');

    // The duration length is the same as the delay length
    for (i = 0, len = duration.length, max = Number.NEGATIVE_INFINITY; i < len; i++) {
      num = parseFloat(duration[i]) + parseFloat(delay[i]);

      if (num > max) {
        max = num;
      }
    }

    return num * 1000;
  }

  /**
   * Get a scrollbar width
   * @private
   * @return {Number}
   */
  function getScrollbarWidth() {
    if ($(document.body).height() <= $(window).height()) {
      return 0;
    }

    var outer = document.createElement('div');
    var inner = document.createElement('div');
    var widthNoScroll;
    var widthWithScroll;

    outer.style.visibility = 'hidden';
    outer.style.width = '100px';
    document.body.appendChild(outer);

    widthNoScroll = outer.offsetWidth;

    // Force scrollbars
    outer.style.overflow = 'scroll';

    // Add inner div
    inner.style.width = '100%';
    outer.appendChild(inner);

    widthWithScroll = inner.offsetWidth;

    // Remove divs
    outer.parentNode.removeChild(outer);

    return widthNoScroll - widthWithScroll;
  }

  /**
   * Lock the screen
   * @private
   */
  function lockScreen() {
    var $html = $('html');
    var lockedClass = namespace + '-is-locked';
    var paddingRight;
    var $body;

    if (!$html.hasClass(lockedClass)) {
      $body = $(document.body);

      // Zepto does not support '-=', '+=' in the `css` method
      paddingRight = parseInt($body.css('padding-right'), 10) + getScrollbarWidth();

      $body.css('padding-right', paddingRight + 'px');
      $html.addClass(lockedClass);
    }
  }

  /**
   * Unlock the screen
   * @private
   */
  function unlockScreen() {
    var $html = $('html');
    var lockedClass = namespace + '-is-locked';
    var paddingRight;
    var $body;

    if ($html.hasClass(lockedClass)) {
      $body = $(document.body);

      // Zepto does not support '-=', '+=' in the `css` method
      paddingRight = parseInt($body.css('padding-right'), 10) - getScrollbarWidth();

      $body.css('padding-right', paddingRight + 'px');
      $html.removeClass(lockedClass);
    }
  }

  /**
   * Parse a string with options
   * @private
   * @param str
   * @returns {Object}
   */
  function parseOptions(str) {
    var obj = {};
    var arr;
    var len;
    var val;
    var i;

    // Remove spaces before and after delimiters
    str = str.replace(/\s*:\s*/g, ':').replace(/\s*,\s*/g, ',');

    // Parse a string
    arr = str.split(',');
    for (i = 0, len = arr.length; i < len; i++) {
      arr[i] = arr[i].split(':');
      val = arr[i][1];

      // Convert a string value if it is like a boolean
      if (typeof val === 'string' || val instanceof String) {
        val = val === 'true' || (val === 'false' ? false : val);
      }

      // Convert a string value if it is like a number
      if (typeof val === 'string' || val instanceof String) {
        val = !isNaN(val) ? +val : val;
      }

      obj[arr[i][0]] = val;
    }

    return obj;
  }

  /**
   * Remodal constructor
   * @param {jQuery} $modal
   * @param {Object} options
   * @constructor
   */
  function Remodal($modal, options) {
    var remodal = this;
    var tdOverlay;
    var tdModal;
    var tdBg;

    remodal.settings = $.extend({}, defaults, options);

    // Build DOM
    remodal.$body = $(document.body);
    remodal.$overlay = $('.' + namespace + '-overlay');

    if (!remodal.$overlay.length) {
      remodal.$overlay = $('<div>').addClass(namespace + '-overlay');
      remodal.$body.append(remodal.$overlay);
    }

    remodal.$bg = $('.' + namespace + '-bg');
    remodal.$closeButton = $('<a href="#"></a>').addClass(namespace + '-close');
    remodal.$wrapper = $('<div>').addClass(namespace + '-wrapper');
    remodal.$modal = $modal;
    remodal.$modal.addClass(namespace);
    remodal.$modal.css('visibility', 'visible');

    remodal.$modal.append(remodal.$closeButton);
    remodal.$wrapper.append(remodal.$modal);
    remodal.$body.append(remodal.$wrapper);
    remodal.$confirmButton = remodal.$modal.find('.' + namespace + '-confirm');
    remodal.$cancelButton = remodal.$modal.find('.' + namespace + '-cancel');

    // Calculate timeouts
    tdOverlay = getTransitionDuration(remodal.$overlay);
    tdModal = getTransitionDuration(remodal.$modal);
    tdBg = getTransitionDuration(remodal.$bg);
    remodal.td = Math.max(tdOverlay, tdModal, tdBg);

    // Add the close button event listener
    remodal.$wrapper.on('click.' + namespace, '.' + namespace + '-close', function(e) {
      e.preventDefault();

      remodal.close();
    });

    // Add the cancel button event listener
    remodal.$wrapper.on('click.' + namespace, '.' + namespace + '-cancel', function(e) {
      e.preventDefault();

      remodal.$modal.trigger('cancel');

      if (remodal.settings.closeOnCancel) {
        remodal.close('cancellation');
      }
    });

    // Add the confirm button event listener
    remodal.$wrapper.on('click.' + namespace, '.' + namespace + '-confirm', function(e) {
      e.preventDefault();

      remodal.$modal.trigger('confirm');

      if (remodal.settings.closeOnConfirm) {
        remodal.close('confirmation');
      }
    });

    // Add the keyboard event listener
    $(document).on('keyup.' + namespace, function(e) {
      if (e.keyCode === 27 && remodal.settings.closeOnEscape) {
        remodal.close();
      }
    });

    // Add the overlay event listener
    remodal.$wrapper.on('click.' + namespace, function(e) {
      var $target = $(e.target);

      if (!$target.hasClass(namespace + '-wrapper')) {
        return;
      }

      if (remodal.settings.closeOnAnyClick) {
        remodal.close();
      }
    });

    remodal.index = $[pluginName].lookup.push(remodal) - 1;
    remodal.busy = false;
  }

  /**
   * Open a modal window
   * @public
   */
  Remodal.prototype.open = function() {

    // Check if the animation was completed
    if (this.busy) {
      return;
    }

    var remodal = this;
    var id;

    remodal.busy = true;
    remodal.$modal.trigger('open');

    id = remodal.$modal.attr('data-' + pluginName + '-id');

    if (id && remodal.settings.hashTracking) {
      scrollTop = $(window).scrollTop();
      location.hash = id;
    }

    if (current && current !== remodal) {
      current.$overlay.hide();
      current.$wrapper.hide();
      current.$body.removeClass(namespace + '-is-active');
    }

    current = remodal;

    lockScreen();
    remodal.$overlay.show();
    remodal.$wrapper.show();

    setTimeout(function() {
      remodal.$body.addClass(namespace + '-is-active');
      remodal.$wrapper.scrollTop(0);

      setTimeout(function() {
        remodal.busy = false;
        remodal.$modal.trigger('opened');
      }, remodal.td + 50);
    }, 25);
  };

  /**
   * Close a modal window
   * @public
   * @param {String|undefined} reason A reason to close
   */
  Remodal.prototype.close = function(reason) {

    // Check if the animation was completed
    if (this.busy) {
      return;
    }

    var remodal = this;

    remodal.busy = true;
    remodal.$modal.trigger({
      type: 'close',
      reason: reason
    });

    if (
      remodal.settings.hashTracking &&
      remodal.$modal.attr('data-' + pluginName + '-id') === location.hash.substr(1)
    ) {
      location.hash = '';
      $(window).scrollTop(scrollTop);
    }

    remodal.$body.removeClass(namespace + '-is-active');

    setTimeout(function() {
      remodal.$overlay.hide();
      remodal.$wrapper.hide();
      unlockScreen();

      remodal.busy = false;
      remodal.$modal.trigger({
        type: 'closed',
        reason: reason
      });
    }, remodal.td + 50);
  };

  /**
   * Special plugin object for instances.
   * @public
   * @type {Object}
   */
  $[pluginName] = {
    lookup: []
  };

  /**
   * Plugin constructor
   * @param {Object} options
   * @returns {JQuery}
   * @constructor
   */
  $.fn[pluginName] = function(opts) {
    var instance;
    var $elem;

    this.each(function(index, elem) {
      $elem = $(elem);

      if ($elem.data(pluginName) == null) {
        instance = new Remodal($elem, opts);
        $elem.data(pluginName, instance.index);

        if (
          instance.settings.hashTracking &&
          $elem.attr('data-' + pluginName + '-id') === location.hash.substr(1)
        ) {
          instance.open();
        }
      } else {
        instance = $[pluginName].lookup[$elem.data(pluginName)];
      }
    });

    return instance;
  };

  $(document).ready(function() {

    // data-remodal-target opens a modal window with the special Id.
    $(document).on('click', '[data-' + pluginName + '-target]', function(e) {
      e.preventDefault();

      var elem = e.currentTarget;
      var id = elem.getAttribute('data-' + pluginName + '-target');
      var $target = $('[data-' + pluginName + '-id=' + id + ']');

      $[pluginName].lookup[$target.data(pluginName)].open();
    });

    // Auto initialization of modal windows.
    // They should have the 'remodal' class attribute.
    // Also you can write `data-remodal-options` attribute to pass params into the modal.
    $(document).find('.' + namespace).each(function(i, container) {
      var $container = $(container);
      var options = $container.data(pluginName + '-options');

      if (!options) {
        options = {};
      } else if (typeof options === 'string' || options instanceof String) {
        options = parseOptions(options);
      }

      $container[pluginName](options);
    });
  });

  /**
   * Hashchange handler
   * @private
   * @param {Event} e
   * @param {Boolean} [closeOnEmptyHash=true]
   */
  function hashHandler(e, closeOnEmptyHash) {
    var id = location.hash.replace('#', '');
    var instance;
    var $elem;

    if (typeof closeOnEmptyHash === 'undefined') {
      closeOnEmptyHash = true;
    }

    if (!id) {
      if (closeOnEmptyHash) {

        // Check if we have currently opened modal and animation was completed
        if (current && !current.busy && current.settings.hashTracking) {
          current.close();
        }
      }
    } else {

      // Catch syntax error if your hash is bad
      try {
        $elem = $(
          '[data-' + pluginName + '-id=' +
          id.replace(new RegExp('/', 'g'), '\\/') + ']'
        );
      } catch (err) {}

      if ($elem && $elem.length) {
        instance = $[pluginName].lookup[$elem.data(pluginName)];

        if (instance && instance.settings.hashTracking) {
          instance.open();
        }
      }

    }
  }

  $(window).bind('hashchange.' + namespace, hashHandler);

});

}).call(this,typeof global !== "undefined" ? global : typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : {})
},{}]},{},[1]);
