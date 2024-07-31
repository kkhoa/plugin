jQuery(document).ready(function($) {
    function displayMessage(type, message) {
        var html = '<div class="notice notice-' + type + ' is-dismissible"><p>' + message + '</p></div>';
        $('#fetch-data-result').html(html);
    }

    function setButtonState(isFetching) {
        $('#fetch-data-button').prop('disabled', isFetching);
        $('#sync-now-button').prop('disabled', isFetching);
    }

    $('#fetch-data-button').on('click', function() {
        setButtonState(true);
        fetchDataFromParent();
    });

    $('#sync-now-button').on('click', function() {
        setButtonState(true);
        syncNow();
    });

    function fetchDataFromParent() {
        $.ajax({
            url: childPlugin.ajax_url,
            type: 'POST',
            data: {
                action: 'fetch_parent_data',
                nonce: childPlugin.nonce
            },
            success: function(response) {
                setButtonState(false);
                if (response.success) {
                    displayMessage('success', response.data);
                } else {
                    displayMessage('error', 'Error fetching data: ' + response.data);
                }
            },
            error: function() {
                setButtonState(false);
                displayMessage('error', 'AJAX error occurred.');
            }
        });
    }

    function syncNow() {
        $.ajax({
            url: childPlugin.ajax_url,
            type: 'POST',
            data: {
                action: 'sync_now',
                nonce: childPlugin.nonce
            },
            success: function(response) {
                setButtonState(false);
                if (response.success) {
                    displayMessage('success', 'Sync completed.');
                } else {
                    displayMessage('error', 'Error during sync.');
                }
            },
            error: function() {
                setButtonState(false);
                displayMessage('error', 'AJAX error occurred.');
            }
        });
    }
});
