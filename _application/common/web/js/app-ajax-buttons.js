var appAjaxButtons = appAjaxButtons || {};

// @see http://stackoverflow.com/questions/10896749/what-does-function-function-window-jquery-do
!(function ($) {
    // @see http://ejohn.org/blog/ecmascript-5-strict-mode-json-and-more/
    "use strict";

    /**
     * @see http://api.jquery.com/ready/
     */
    $(document).ready(function () {
        /***********************************************************************
         *                              METHODS
         **********************************************************************/
        /**
         * @param $element (obj)
         */
        appAjaxButtons.ajaxButtonSubmit = function ( $element ) {
            var url         = $element.attr('href'),
                $icon       = $element.find('i'),
                iconClass   = $icon.attr('class'),
                timeout     = 1200,
                delay       = 2500
            ;

            if ( !url || $element.data('locked') === true ) {
                return false;
            }

            $.ajax({
                type: 'POST',
                url: url,
                beforeSend: function ( xhr, settings ) {
                    $element.data('locked', true);
                    if ( iconClass ) {
                        $icon.attr('class', 'fa fa-spinner fa-pulse');
                    }
                    if ( $element.attr('before-send-igrowl-message') ) {
                        // show iGrowl popup message
                        // @see http://catc.github.io/iGrowl/
                        $.iGrowl.prototype.dismissAll('all');
                        $.iGrowl({
                            placement:  {
                                x: $element.attr('placement-x') || 'center',
                                y: $element.attr('placement-y') || 'top'
                            },
                            type:       'notice',
                            delay:      delay * 60,
                            animation:  true,
                            animShow:   'fadeIn',
                            animHide:   'fadeOut',
                            title:      ':: ' + ($element.attr('before-send-igrowl-title') || 'REQUEST SENT') + ' .:',
                            message:    $element.attr('before-send-igrowl-message') || 'Please wait...'
                        });
                    }
                },
                success: function ( data ) {
                    window.setTimeout(function () {
                        $.iGrowl.prototype.dismissAll('all');
                        window.setTimeout(function () {
                            $.iGrowl({
                                placement:  {
                                    x: $element.attr('placement-x') || 'center',
                                    y: $element.attr('placement-y') || 'top'
                                },
                                type:       data.status || 'success',
                                delay:      (data.status !== 'success') ? delay * 60 : delay,
                                animation:  true,
                                animShow:   'fadeIn',
                                animHide:   'fadeOut',
                                title:      ':: ' + ($element.attr('success-igrowl-title') || 'SERVER RESPONSE') + ' .:',
                                message:    data.message || $element.attr('success-igrowl-message') || '...'
                            });
                            if ( iconClass ) {
                                $icon.attr('class', iconClass);
                            }
                            window.setTimeout(function () {$element.data('locked', false);}, delay * 2);
                            // triger event: "ajaxButtonSubmit" ----------------
                            $(document).trigger('ajaxButtonSubmit', {
                                $element: $element,
                                status: data.status,
                                url: url,
                                action: $element.attr('action'),
                                data: data
                            });
                            // -------------------------------------------------
                        }, timeout);
                    }, timeout);
                },
            }).then(function () {                                               // doneCallbacks (@see http://api.jquery.com/deferred.then/)
                // dummy
            }, function ( xhr, errorType, exception ) {                         // failCallacks
                window.setTimeout(function () {
                    $.iGrowl.prototype.dismissAll('all');
                    window.setTimeout(function () {
                        $.iGrowl({
                            placement:  {
                                x: $element.attr('placement-x') || 'center',
                                y: $element.attr('placement-y') || 'top'
                            },
                            type:       'error',
                            delay:      delay * 60,
                            animation:  true,
                            animShow:   'fadeIn',
                            animHide:   'fadeOut',
                            title:      ':: SERVER ERROR .:',
                            message:    xhr.responseText || 'Error'
                        });
                        if ( iconClass ) {
                            $icon.attr('class', iconClass);
                        }
                        window.setTimeout(function () {$element.data('locked', false);}, delay * 2);
                    }, timeout);
                }, timeout);
            });
        };

        /***********************************************************************
         *                          ACTIONS HANDLER
         **********************************************************************/
        appAjaxButtons.actionsHandler = function () {
            $('body').delegate('.btn-ajax', 'click', function () {
                appAjaxButtons.ajaxButtonSubmit($(this));
                return false;
            });
        };

        /***********************************************************************
         *                               INIT
         **********************************************************************/
        appAjaxButtons.init = function () {
            appAjaxButtons.actionsHandler();
        };
        appAjaxButtons.init();
    });
})(window.jQuery);