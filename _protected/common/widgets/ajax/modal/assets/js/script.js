/**
 * ModalAjaxWidgetClicker plugin
 * @param options
 */
var modalAjaxWidgetClicker = function (options) {

    var settings = $.extend({
        clickBtnClass: 'modal-click-btn',
        modalText: 'Loading...',
        targetId: null
    }, options);

    checkOptions(settings);
    run();

    function checkOptions(settings) {
        if (!settings.targetId) {
            throw new Error('Options "targetId" is required in "modalAjaxWidgetClicker"');
        }
    }

    function run() {
        $('.' + settings.clickBtnClass).click(function (e) {
            e.preventDefault();

            var modalBody = $('#' + settings.targetId).find('.modal-body');
            modalBody.html(settings.modalText);

            var link = $(this);
            var url = link.attr('href');

            $.get(url, function (response) {
                modalBody.html(response);
            });
        });
    }

};
