/* global jQuery, yii, Swiper, formBuilderBaseUrl, JSONEditor: false */
window.sa = {};
window.sa.formBuilder = (function ($, JSONEditor) {
    var attributes = null;
    var validators = null;
    var actions = null;
    var editors = {};

    var pub = {
        isActive: true,

        /**
         * Initialize module
         */
        init: function () {
            initForm();
            initSortable();
            initDataActions();
            initFields();
            initValidators();
            initActions();
            bindPjaxEvents();
        },
        /**
         * Set attributes
         * @param {Object} attrs
         */
        setAttributes: function (attrs) {
            attributes = attrs;
        },
        /**
         * Set validators
         * @param {Object} validates
         */
        setValidators: function (validates) {
            validators = validates;
        },

        /**
         * Set actions
         * @param {Object} acts
         */
        setActions: function (acts) {
            actions = acts;
        },

        /**
         * Initialize an action form
         * @param initValue
         */
        initAction: function (initValue) {
            var $this = $(this), $form = $($this.data('container')), schema = actions[$this.val()],
                id = $this.prop('id'), formName = $this.attr('name').replace('action_id', 'configuration');
            var options = {
                disable_edit_json: true,
                disable_properties: true,
                form_name_root: formName,
                schema: schema,
                theme: 'bootstrap4',
                use_default_values: false
            };

            if (editors[id] && editors[id].destroy) {
                editors[id].destroy();
            }

            if (typeof initValue === 'object' && !$.isArray(initValue)) {
                $.each(schema.properties, function (key) {
                    if (!initValue.hasOwnProperty(key)) {
                        initValue[key] = '';
                    }
                });
                options.startval = initValue;
            }

            editors[id] = new JSONEditor($form.get(0), options);
        },

        /**
         * Initializes a validator form
         * @param {Object} initValue
         */
        initValidator: function (initValue) {
            var $this = $(this), $form = $($this.data('container')), schema = validators[$this.val()],
                id = $this.prop('id'), formName = $this.attr('name').replace('class', 'configuration');
            var options = {
                disable_edit_json: true,
                disable_properties: true,
                form_name_root: formName,
                schema: schema,
                theme: 'bootstrap4',
                use_default_values: false
            };

            if (editors[id] && editors[id].destroy) {
                editors[id].destroy();
            }

            if (typeof initValue === 'object' && !$.isArray(initValue)) {
                $.each(schema.properties, function (key) {
                    if (!initValue.hasOwnProperty(key)) {
                        initValue[key] = '';
                    }
                });
                options.startval = initValue;
            }

            editors[id] = new JSONEditor($form.get(0), options);
        }
    };

    function initForm()
    {
        $('#buildFormForm').on('submit.sa.formBuilder', function () {
            $(this).find('.collapse').each(function () {
                $(this).collapse('show');
            });

            return true;
        });
    }

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
        var $element = ui.item, $sortable = $element.closest('.ui-sortable'), $items = $sortable.find('> .card');

        $items.each(function (index) {
            $(this).find('.sortable-field').val(index);
        });
    }

    function initDataActions()
    {
        $(document).on('click.sa.formBuilder', '[data-remove]', function () {
            var $this = $(this), $remove = $this.closest($this.data('remove')), $form = $remove.closest('form'),
                $link = $remove.closest('.accordion').find('.add-btn').first();

            $remove.find('.form-control').each(function () {
                $form.yiiActiveForm('remove', $(this).prop('id'));
            });
            $remove.remove();

            var url = new URL($link.get(0).href), counter = Math.max(0, url.searchParams.get('counter') - 1);
            url.searchParams.set('counter', counter);
            $link.attr('href', url.href);
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
        $(document).on('change.sa.formBuilder', '.sa-formbuilder-field-relation_model .form-control', function () {
            var $this = $(this),
                $field = $(this).closest('.sa-formbuilder-field').find('.sa-formbuilder-field-relation_field .form-control');
            $field.find('option:not(:empty)').remove();
            $.each(attributes[$this.val()], function (key, val) {
                $field.append('<option value="' + key + '">' + val + '</option>');
            });
            $field.trigger('change');
        });
        $(document).on('change.sa.formBuilder', '.sa-formbuilder-field-type .form-control', function () {
            var $this = $(this),
                $options = $(this).closest('.sa-formbuilder-field').find('.sa-formbuilder-field-options .form-control'),
                options = {}, editor = window[$options.data('jsonEditorName')];
            switch ($this.val()) {
                case 'select':
                    options.data = {
                        key1: 'val1',
                        key2: 'val2'
                    };
                    options.theme = 'krajee-bs4';
                    options.bsVersion = 4;
                    options.options = {
                        placeholder: '',
                        multiple: false
                    };
                    options.pluginOptions = {allowClear: true};
                    break;
                case 'date':
                    options.clientOptions = {
                        format: 'd.m.Y',
                        large: true,
                        largeDefault: false,
                        largeOnly: false
                    };
                    break;
                case 'textarea':
                    options.rows = 5;
                    break;
                case 'int':
                    options.type = 'number';
                    options.step = 1;
                    break;
                case 'double':
                    options.type = 'number';
                    options.step = .1;
                    break;
                case 'time':
                    options.autoSwitch = true;
                    options.setCurrentTime = false;
                    options.clientOptions = {
                        format: 'HH:mm'
                    };
                    break;
            }

            editor.set(options);
        });
    }

    function initValidators()
    {
        $(document).on('change.sa.formBuilder', '.sa-formbuilder-validator-class .form-control', function () {
            pub.initValidator.apply(this);
        });
    }

    function initActions()
    {
        $(document).on('change.sa.formBuilder', '.sa-formbuilder-action-id .form-control', function () {
            pub.initAction.apply(this);
        });
    }

    function bindPjaxEvents()
    {
        $(document).on('pjax:end', function (evt, xhr, options) {
            var $container = $(options.container);
            if (options.container === '#sa-formbuilder-section-pjax' || options.container.match(/^#sa-formbuilder-section-\d+-.+-pjax$/)) {
                $container.find('.card').appendTo($container.parent());
            }
        });
        $(document).ajaxComplete(function () {
            $('[data-show]').trigger('change.sa.formBuilder');
        });
    }

    return pub;
})(jQuery, JSONEditor/*, formBuilderBaseUrl */);

jQuery(function () {
    yii.initModule(window.sa.formBuilder);
});
