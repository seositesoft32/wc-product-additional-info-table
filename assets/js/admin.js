(function ($) {
    'use strict';

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

    $(document).on('submit', '#wcpait-settings-form', function () {
        var hasError = false;

        $('#wcpait-field-rows').find('input[name*="[label]"]').each(function () {
            if (!$(this).val().trim()) {
                hasError = true;
                $(this).focus();
                return false;
            }
        });

        if (hasError && window.wcpaitAdmin && wcpaitAdmin.requiredMessage) {
            alert(wcpaitAdmin.requiredMessage);
            return false;
        }

        refreshIndexes();
        return true;
    });

    $(function () {
        initSortable();
    });
}(jQuery));
