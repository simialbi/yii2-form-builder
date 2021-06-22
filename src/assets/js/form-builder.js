/* global jQuery, yii, Swiper, kanbanBaseUrl: false */
window.sa = {};
window.sa.formBuilder = (function ($, baseUrl) {
    var pub = {
        isActive: true,

        init: function () {
            initSortable();
            initDataActions();
            initFields();
            bindPjaxEvents();
        }
    };

    function initSortable()
    {

        $('#sa-formbuilder-sections').sortable({
            items: '> .card',
            handle: '.sa-formbuilder-section-sortable-handler',
            distance: 5,
            stop: setOrder
        });

        $('.sa-formbuilder-section-fields').sortable({
            items: '> .card',
            handle: '.sa-formbuilder-field-sortable-handler',
            distance: 5,
            stop: setOrder
        });
    }

    function setOrder(event, ui)
    {
        var $element = ui.item,
            $sortable = $element.closest('.ui-sortable'),
            $items = $sortable.find('> .card');

        $items.each(function (index) {
            $(this).find('.sortable-field').val(index);
        });
    }

    function initDataActions()
    {
        $(document).on('click.sa.formBuilder', '[data-remove]', function () {
            var $this = $(this), $remove = $this.closest($this.data('remove')), $form = $remove.closest('form');

            $remove.find('.form-control').each(function () {
                $form.yiiActiveForm('remove', $(this).prop('id'));
            });
            $remove.remove();
        });
        $(document).on('change.sa.formBuilder', '[data-show]', function () {
            var $this = $(this), val = $this.val(), fields = $(this).data('show');
            $.each(fields, function (i, selector) {
                var $el = $(selector), conditions = $el.data('showCondition'), matched = false;
                if (!$.isArray(conditions)) {
                    conditions = [conditions];
                }
                $.each(conditions, function (i, condition) {
                    if (condition == val) {
                        matched = true;
                        return false;
                    }
                });
                if (matched) {
                    $el.show();
                } else {
                    $el.hide();
                }
            });
        });
        $('[data-show]').trigger('change.sa.formBuilder');
    }

    function initFields()
    {
        $(document).on('change.sa.formBuilder', '.sa-formbuilder-field-label .form-control', function () {
            var $this = $(this),
                $name = $this.closest('.sa-formbuilder-field').find('.sa-formbuilder-field-name .form-control');
            if ($name.val() === '') {
                $name.val($this.val().toLowerCase().replace(/\s+/, '-').replace(/[^a-z0-9_-]/, ''));
            }
        });
    }

    function bindPjaxEvents()
    {
        $(document).on('pjax:end', function (evt, xhr, options) {
            var $container = $(options.container);
            if (options.container === '#sa-formbuilder-section-pjax' || options.container.match(/^#sa-formbuilder-section-\d+-field-pjax$/)) {
                $container.find('.card').appendTo($container.parent());
            }
        });
        $(document).ajaxComplete(function () {
            $('[data-show]').trigger('change.sa.formBuilder');
        });
    }

    return pub;
})(window.jQuery, window.formBuilderBaseUrl);

window.jQuery(function () {
    window.yii.initModule(window.sa.formBuilder);
});
