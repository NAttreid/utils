(function ($, window) {
    if (window.jQuery === undefined) {
        console.error('Plugin "jQuery" required by "utils/jQuery.js" is missing!');
        return;
    }

    /**
     * Metoda pro scrollovani objektu na strance
     * @param {object} opt
     * @returns {jQuery.fn}
     */
    $.fn.fixedPosition = function (opt) {

        function getTopOffset() {
            var topOffset = element.offset().top;
            if (options.from !== null) {
                topOffset = $(options.from).offset().top;
            }
            return topOffset;
        }

        function scroll() {
            if ((options.to !== null && $(options.to).length > 0) && ($(window).scrollTop() + element.height() >= $(options.to).offset().top)) {
                var top = $(options.to).offset().top - element.height() + options.bottom;
                if (topOffset <= top) {
                    element
                        .removeAttr('style')
                        .css('position', 'absolute')
                        .css('top', top + 'px')
                        .addClass('fixed')
                        .removeClass('moving');
                } else {
                    clear();
                }
            } else if (($(window).scrollTop() > topOffset - options.top)) {
                element
                    .removeAttr('style')
                    .css('position', 'fixed')
                    .css('top', 0)
                    .addClass('moving')
                    .removeClass('fixed');
                if (options.width !== null) {
                    element.css('width', getWidth());
                }
            } else {
                clear();
            }
        }

        function clear() {
            element
                .removeAttr('style')
                .removeClass('fixed')
                .removeClass('moving');
        }

        function getWidth() {
            return options.width instanceof jQuery ? options.width.width() : options.width;
        }

        var options = {
            top: 0,
            bottom: 0,
            from: null,
            to: null,
            width: null
        };

        if (options !== null) {
            for (var property in opt) {
                if (opt[property] !== null) {
                    options[property] = opt[property];
                }
            }
        }

        var element = $(this);

        if (element.length > 0) {

            var topOffset = getTopOffset();

            $(window).on('resize', function () {
                if (element.hasClass('moving') && options.width !== null) {
                    element.css('width', getWidth());
                }
                topOffset = getTopOffset();
            });

            $(document).on('scroll', scroll);
            scroll();
        }
        return this;
    };

    /**
     * Metoda pro vycentrovani na obrazovce (zustane na aktualni pozici)
     * @returns {jQuery.fn}
     */
    $.fn.center = function () {
        this.css('position', 'absolute');
        this.css({visibility: 'hidden', display: 'block'});
        this.css('top', Math.max(0, (($(window).height() - this.outerHeight()) / 2) + $(window).scrollTop()) + 'px');
        this.css('left', Math.max(0, (($(window).width() - this.outerWidth()) / 2) + $(window).scrollLeft()) + 'px');
        this.css({visibility: '', display: ''});
        return this;
    };

    /**
     * Metoda pro vycentrovani na obrazovce (posouva se pri scrollu)
     * @returns {jQuery.fn}
     */
    $.fn.centerFixed = function () {
        this.css('position', 'fixed');
        this.css({visibility: 'hidden', display: 'block'});
        this.css('top', Math.max(0, (($(window).height() - this.outerHeight()) / 2)) + 'px');
        this.css('left', Math.max(0, (($(window).width() - this.outerWidth()) / 2)) + 'px');
        this.css({visibility: '', display: ''});
        return this;
    };

    /**
     * Metoda spousti callback pri kliku mimo dany objekt
     * @param callback
     * @returns {jQuery.fn}
     */
    $.fn.clickOut = function (callback) {
        var container = this;
        var id = container.clickOff();
        $(document).on('click.clickOutEvent' + id, function (e) {
            var enableClick = true;

            if (!container.is(e.target) && container.has(e.target).length === 0) {
                enableClick = false;
                if (typeof (callback) === 'function') {
                    var disable = callback(container, e);
                } else {
                    window.console.error('Neni volan callback v funkci $.clickOut');
                }
            }

            if (disable === true) {
                container.clickOff();
            }

            if (enableClick) {
                return true;
            } else {
                e.stopPropagation();
                e.preventDefault();
                return false;
            }
        });
        return this;
    };

    /**
     * Vypnuti clickOut
     * @returns {String}
     */
    $.fn.clickOff = function () {
        var container = this;
        container.uniqueId();
        var id = container.attr('id');
        $(document).off('click.clickOutEvent' + id);
        return id;
    };

    /**
     * Metoda spousti callback pri najeti okna k danemu elementu
     * @param callback
     * @returns {jQuery.fn}
     */
    $.fn.onScrollTo = function (callback) {
        var element = $(this);

        if (element.length > 0) {

            element.uniqueId();
            var id = element.attr('id');

            function bindEvent() {
                $(window).bind('scroll.scrollToEvent' + id, function () {
                    if ($(window).scrollTop() + $(window).height() >= element.offset().top) {
                        $(window).unbind('scroll.scrollToEvent' + id);
                        var disable = callback(element);
                        if (!disable === true) {
                            bindEvent();
                        }
                    }
                });
            }

            bindEvent();
        }
        return this;
    };

    /**
     * Zkopiruje obsah objeku do schranky
     * @returns {Boolean}
     */
    $.fn.copyToClipboard = function () {
        var elem = this[0];

        // create hidden text element, if it doesn't already exist
        var targetId = "_hiddenCopyText_";
        var isInput = elem.tagName === "INPUT" || elem.tagName === "TEXTAREA";
        var origSelectionStart, origSelectionEnd;
        if (isInput) {
            // can just use the original source element for the selection and copy
            target = elem;
            origSelectionStart = elem.selectionStart;
            origSelectionEnd = elem.selectionEnd;
        } else {
            // must use a temporary form element for the selection and copy
            target = document.getElementById(targetId);
            if (!target) {
                var target = document.createElement("textarea");
                target.style.position = "absolute";
                target.style.left = "-9999px";
                target.style.top = "0";
                target.id = targetId;
                document.body.appendChild(target);
            }
            target.textContent = elem.textContent;
        }
        // select the content
        var currentFocus = document.activeElement;
        target.focus();
        target.setSelectionRange(0, target.value.length);

        // copy the selection
        var succeed;
        try {
            succeed = document.execCommand("copy");
        } catch (e) {
            succeed = false;
        }
        // restore original focus
        if (currentFocus && typeof currentFocus.focus === "function") {
            currentFocus.focus();
        }

        if (isInput) {
            // restore prior selection
            elem.setSelectionRange(origSelectionStart, origSelectionEnd);
        } else {
            // clear temporary content
            target.textContent = "";
        }
        return succeed;
    };

    /**
     * Umisti objekt podle pozice mysi. Vraci objekt s pozici pro zobrazeni objektu
     * @param {Event} event
     * @param {int} x
     * @param {int} y
     * @returns {jQuery.fn.onPosition.position}
     */
    $.fn.onPosition = function (event, x, y) {
        var obj = $(this);
        x = typeof (x) === 'undefined' ? 0 : x;
        y = typeof (y) === 'undefined' ? 0 : y;

        var position = {
            left: $(window).width() < event.pageX + obj.outerWidth(true) ? event.pageX - obj.outerWidth(true) - x : event.pageX + x,
            top: $(window).height() < event.pageY + obj.outerHeight(true) ? event.pageY - obj.outerHeight(true) - y : event.pageY + y
        };
        obj.css({
            position: 'absolute',
            left: position.left,
            top: position.top
        });
        return position;
    };

    /**
     * Nacteni skriptu
     * @param {string} url
     * @param {object} options
     * @returns {object}
     */
    $.cachedScript = function (url, options) {
        options = $.extend(options || {}, {
            dataType: "script",
            cache: true,
            url: url
        });
        return jQuery.ajax(options);
    };

    /**
     * Je object na obrazovce
     * @param test
     * @returns {*}
     */
    $.fn.isOnScreen = function (test) {

        var height = this.outerHeight();
        var width = this.outerWidth();

        if (!width || !height) {
            return false;
        }

        var win = $(window);

        var viewport = {
            top: win.scrollTop(),
            left: win.scrollLeft()
        };
        viewport.right = viewport.left + win.width();
        viewport.bottom = viewport.top + win.height();

        var bounds = this.offset();
        bounds.right = bounds.left + width;
        bounds.bottom = bounds.top + height;

        var showing = {
            top: viewport.bottom - bounds.top,
            left: viewport.right - bounds.left,
            bottom: bounds.bottom - viewport.top,
            right: bounds.right - viewport.left
        };

        if (typeof test === 'function') {
            return test(showing);
        }

        return showing.top > 0
            && showing.left > 0
            && showing.right > 0
            && showing.bottom > 0;
    };

})(jQuery, window);