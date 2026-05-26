(function ($) {
    'use strict';

    function showNotice(message, type) {
        var $notice = $('#wcpait-admin-notice');
        if (!$notice.length) {
            return;
        }

        $notice
            .removeClass('is-success is-error is-loading')
            .addClass(type ? 'is-' + type : '')
            .text(message)
            .show();
    }

    function setSaveButtonsState(isSaving) {
        var text = (window.wcpaitAdmin && isSaving)
            ? wcpaitAdmin.savingText
            : (window.wcpaitAdmin ? wcpaitAdmin.saveText : 'Save Settings');

        $('#wcpait-save-settings-btn, #wcpait-save-settings-btn-bottom')
            .prop('disabled', !!isSaving)
            .text(text);
    }

    function activateTab(tab) {
        $('.wcpait-tab').removeClass('is-active');
        $('.wcpait-tab-panel').removeClass('is-active');

        $('.wcpait-tab[data-tab="' + tab + '"]').addClass('is-active');
        $('.wcpait-tab-panel[data-panel="' + tab + '"]').addClass('is-active');
    }

    function sanitizeCssValue(value, fallback) {
        return value && String(value).trim() ? String(value).trim() : fallback;
    }

    function updateLivePreview() {
        var $preview = $('#wcpait-live-preview');
        if (!$preview.length) {
            return;
        }

        var style = $('select[name="wcpait_settings[table_style]"]').val() || 'minimal';
        var showHeader = $('input[name="wcpait_settings[show_table_header]"]').is(':checked');
        var labelHeading = $('input[name="wcpait_settings[header_label_text]"]').val() || 'Label';
        var valueHeading = $('input[name="wcpait_settings[header_value_text]"]').val() || 'Value';

        $preview
            .removeClass('wcpait-style-minimal wcpait-style-modern wcpait-style-classic wcpait-style-dark wcpait-style-compact')
            .addClass('wcpait-style-' + style);

        $preview.css('--wcpait-table-bg', sanitizeCssValue($('input[name="wcpait_settings[table_background]"]').val(), '#ffffff'));
        $preview.css('--wcpait-header-bg', sanitizeCssValue($('input[name="wcpait_settings[header_background]"]').val(), '#f3f4f6'));
        $preview.css('--wcpait-text', sanitizeCssValue($('input[name="wcpait_settings[text_color]"]').val(), '#1f2937'));
        $preview.css('--wcpait-border', sanitizeCssValue($('input[name="wcpait_settings[border_color]"]').val(), '#d1d5db'));
        $preview.css('--wcpait-alt-row', sanitizeCssValue($('input[name="wcpait_settings[alternate_row_color]"]').val(), '#f9fafb'));
        $preview.css('--wcpait-row-spacing', (parseInt($('input[name="wcpait_settings[row_spacing]"]').val(), 10) || 0) + 'px');
        $preview.css('--wcpait-cell-padding', (parseInt($('input[name="wcpait_settings[cell_padding]"]').val(), 10) || 12) + 'px');
        $preview.css('--wcpait-font-size', (parseInt($('input[name="wcpait_settings[font_size]"]').val(), 10) || 14) + 'px');
        $preview.css('--wcpait-radius', (parseInt($('input[name="wcpait_settings[border_radius]"]').val(), 10) || 8) + 'px');

        $preview.find('.wcpait-preview-label').text(labelHeading);
        $preview.find('.wcpait-preview-value').text(valueHeading);
        $preview.find('thead').toggle(showHeader);
    }

    function refreshIndexes() {
        $('#wcpait-field-rows').find('tr.wcpait-field-row').each(function (index) {
            $(this)
                .find('input, select')
                .each(function () {
                    var name = $(this).attr('name');
                    if (!name) {
                        return;
                    }

                    name = name.replace(/fields\]\[\d+\]/, 'fields][' + index + ']');
                    $(this).attr('name', name);
                });
        });
    }

    function addRow() {
        var $tbody = $('#wcpait-field-rows');
        if (!$tbody.length || !window.wcpaitAdmin || !wcpaitAdmin.rowTemplate) {
            return;
        }

        var index = $tbody.find('tr.wcpait-field-row').length;
        var rowHtml = wcpaitAdmin.rowTemplate.replace(/__index__/g, String(index));
        $tbody.append(rowHtml);
    }

    function initSortable() {
        var $tbody = $('#wcpait-field-rows');
        if (!$tbody.length || !$tbody.sortable) {
            return;
        }

        $tbody.sortable({
            handle: '.wcpait-sort-col',
            items: '> tr',
            update: function () {
                refreshIndexes();
            }
        });
    }

    $(document).on('click', '#wcpait-add-row', function (e) {
        e.preventDefault();
        addRow();
    });

    $(document).on('click', '.wcpait-remove-row', function (e) {
        e.preventDefault();

        if (window.wcpaitAdmin && wcpaitAdmin.removeMessage && !window.confirm(wcpaitAdmin.removeMessage)) {
            return;
        }

        $(this).closest('tr').remove();
        refreshIndexes();
    });

    $(document).on('click', '.wcpait-tab', function (e) {
        e.preventDefault();
        activateTab($(this).data('tab'));
    });

    $(document).on('submit', '#wcpait-settings-form', function (e) {
        var isSettingsPage = !!(window.wcpaitAdmin && wcpaitAdmin.isSettingsPage);
        var hasError = false;
        var $form = $(this);

        $('#wcpait-field-rows').find('input[name*="[label]"]').each(function () {
            if (!$(this).val().trim()) {
                hasError = true;
                $(this).focus();
                return false;
            }
        });

        if (hasError && window.wcpaitAdmin && wcpaitAdmin.requiredMessage) {
            showNotice(wcpaitAdmin.requiredMessage, 'error');
            return false;
        }

        refreshIndexes();

        if (!isSettingsPage) {
            return true;
        }

        e.preventDefault();

        setSaveButtonsState(true);
        showNotice(wcpaitAdmin.savingText || 'Saving...', 'loading');

        $.ajax({
            url: wcpaitAdmin.ajaxUrl,
            method: 'POST',
            dataType: 'json',
            data: {
                action: 'wcpait_save_settings',
                nonce: wcpaitAdmin.saveNonce,
                form_data: $form.serialize()
            }
        }).done(function (response) {
            if (response && response.success) {
                showNotice((response.data && response.data.message) ? response.data.message : wcpaitAdmin.savedText, 'success');
            } else {
                showNotice((response && response.data && response.data.message) ? response.data.message : wcpaitAdmin.errorText, 'error');
            }
        }).fail(function () {
            showNotice(wcpaitAdmin.errorText, 'error');
        }).always(function () {
            setSaveButtonsState(false);
        });

        return true;
    });

    $(document).on('change input', '#wcpait-settings-form input, #wcpait-settings-form select', function () {
        updateLivePreview();
    });

    $(function () {
        initSortable();
        updateLivePreview();
    });
}(jQuery));
