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
        var $tabs = $('.wcpait-tab');
        var $panels = $('.wcpait-tab-panel');
        var $activeTab = $('.wcpait-tab[data-tab="' + tab + '"]');
        var $activePanel = $('.wcpait-tab-panel[data-panel="' + tab + '"]');

        $tabs
            .removeClass('is-active')
            .attr('aria-selected', 'false')
            .attr('tabindex', '-1');

        $panels
            .removeClass('is-active')
            .attr('hidden', true);

        $activeTab
            .addClass('is-active')
            .attr('aria-selected', 'true')
            .attr('tabindex', '0')
            .trigger('focus');

        $activePanel
            .addClass('is-active')
            .attr('hidden', false);
    }

    function moveTabFocus(currentTab, direction) {
        var $tabs = $('.wcpait-tab');
        var currentIndex = $tabs.index(currentTab);
        var nextIndex = currentIndex + direction;

        if (nextIndex < 0) {
            nextIndex = $tabs.length - 1;
        }

        if (nextIndex >= $tabs.length) {
            nextIndex = 0;
        }

        $tabs.eq(nextIndex).trigger('click');
    }

    function sanitizeCssValue(value, fallback) {
        return value && String(value).trim() ? String(value).trim() : fallback;
    }

    function updateStyleOptionGroups(style) {
        $('.wcpait-style-options').each(function () {
            var styles = String($(this).data('styles') || '').split(/\s*,\s*/);
            var shouldShow = styles.indexOf(style) !== -1;
            $(this).toggle(shouldShow);
        });
    }

    function updatePreviewTemplate(style) {
        $('#wcpait-live-preview').find('.wcpait-preview-template').each(function () {
            var supports = String($(this).data('preview') || '').split(/\s+/);
            var visible = supports.indexOf(style) !== -1;
            $(this).toggle(visible);
        });
    }

    function updateLivePreview() {
        var $preview = $('#wcpait-live-preview');
        if (!$preview.length) {
            return;
        }

        var style = $('select[name="wcpait_settings[table_style]"]').val() || 'clean_table';
        var showHeader = $('input[name="wcpait_settings[show_table_header]"]').is(':checked');
        var labelHeading = $('input[name="wcpait_settings[header_label_text]"]').val() || 'Label';
        var valueHeading = $('input[name="wcpait_settings[header_value_text]"]').val() || 'Value';

        $preview
            .removeClass('wcpait-style-clean_table wcpait-style-cards_timeline wcpait-style-badge_grid wcpait-style-ribbon_list wcpait-style-minimal_lines wcpait-style-glass_panel wcpait-style-progress_steps wcpait-style-chip_tiles')
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
        $preview.css('--wcpait-cards-accent', sanitizeCssValue($('input[name="wcpait_settings[cards_accent_color]"]').val(), '#2563eb'));
        $preview.css('--wcpait-cards-badge-text', sanitizeCssValue($('input[name="wcpait_settings[cards_badge_text_color]"]').val(), '#ffffff'));
        $preview.css('--wcpait-cards-shadow', (parseInt($('input[name="wcpait_settings[cards_shadow_intensity]"]').val(), 10) || 12) + 'px');
        $preview.css('--wcpait-grid-badge-bg', sanitizeCssValue($('input[name="wcpait_settings[grid_badge_bg]"]').val(), '#7c3aed'));
        $preview.css('--wcpait-grid-badge-text', sanitizeCssValue($('input[name="wcpait_settings[grid_badge_text]"]').val(), '#ffffff'));
        $preview.css('--wcpait-grid-card-bg', sanitizeCssValue($('input[name="wcpait_settings[grid_card_bg]"]').val(), '#ffffff'));
        $preview.css('--wcpait-grid-card-border', sanitizeCssValue($('input[name="wcpait_settings[grid_card_border]"]').val(), '#e5e7eb'));
        $preview.css('--wcpait-ribbon-bg', sanitizeCssValue($('input[name="wcpait_settings[ribbon_bg_color]"]').val(), '#0ea5e9'));
        $preview.css('--wcpait-ribbon-text', sanitizeCssValue($('input[name="wcpait_settings[ribbon_text_color]"]').val(), '#ffffff'));
        $preview.css('--wcpait-ribbon-badge-bg', sanitizeCssValue($('input[name="wcpait_settings[ribbon_badge_bg]"]').val(), '#0369a1'));
        $preview.css('--wcpait-ribbon-badge-text', sanitizeCssValue($('input[name="wcpait_settings[ribbon_badge_text]"]').val(), '#ffffff'));
        $preview.css('--wcpait-minimal-line', sanitizeCssValue($('input[name="wcpait_settings[minimal_line_color]"]').val(), '#cbd5e1'));
        $preview.css('--wcpait-glass-tint', sanitizeCssValue($('input[name="wcpait_settings[glass_tint_color]"]').val(), '#dbeafe'));
        $preview.css('--wcpait-glass-border', sanitizeCssValue($('input[name="wcpait_settings[glass_border_color]"]').val(), '#93c5fd'));
        $preview.css('--wcpait-glass-glow', (parseInt($('input[name="wcpait_settings[glass_glow_intensity]"]').val(), 10) || 18) + 'px');
        $preview.css('--wcpait-steps-bar', sanitizeCssValue($('input[name="wcpait_settings[steps_bar_color]"]').val(), '#f59e0b'));
        $preview.css('--wcpait-steps-badge-bg', sanitizeCssValue($('input[name="wcpait_settings[steps_badge_bg]"]').val(), '#1d4ed8'));
        $preview.css('--wcpait-steps-badge-text', sanitizeCssValue($('input[name="wcpait_settings[steps_badge_text]"]').val(), '#ffffff'));
        $preview.css('--wcpait-chips-bg', sanitizeCssValue($('input[name="wcpait_settings[chips_bg_color]"]').val(), '#eff6ff'));
        $preview.css('--wcpait-chips-text', sanitizeCssValue($('input[name="wcpait_settings[chips_text_color]"]').val(), '#1e3a8a'));
        $preview.css('--wcpait-chips-border', sanitizeCssValue($('input[name="wcpait_settings[chips_border_color]"]').val(), '#93c5fd'));

        $preview.find('.wcpait-preview-label').text(labelHeading);
        $preview.find('.wcpait-preview-value').text(valueHeading);
        $preview.find('thead').toggle(showHeader);

        updateStyleOptionGroups(style);
        updatePreviewTemplate(style);
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

    $(document).on('keydown', '.wcpait-tab', function (e) {
        if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
            e.preventDefault();
            moveTabFocus(this, 1);
        }

        if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
            e.preventDefault();
            moveTabFocus(this, -1);
        }

        if (e.key === 'Home') {
            e.preventDefault();
            $('.wcpait-tab').first().trigger('click');
        }

        if (e.key === 'End') {
            e.preventDefault();
            $('.wcpait-tab').last().trigger('click');
        }
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
