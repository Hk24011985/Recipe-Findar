$(function() {
    $('#items_message').hide();
    $('#gradiant_message').hide();

    $('#items').change(function() {
        var filename = $(this).val();
        // $('#txtFileName').val(filename);
        var fileExtension = ['csv'];
        if ($.inArray(filename.split('.').pop().toLowerCase(), fileExtension) == -1) {
            $('#items_message').show();
            $('#items_message').text("Only 'csv' file allowed.");
            $('#items').val('');
            return false;
        } else {
            $('#items_message').hide();
        }
    });
    //Gradiant file validation
    $('#gradiants').change(function() {
        var filename = $(this).val();
        var fileExtension = ['json'];
        if ($.inArray(filename.split('.').pop().toLowerCase(), fileExtension) == -1) {
            $('#gradiant_message').show();
            $('#gradiant_message').text("Only 'Json' file allowed.");
            $('#gradiants').val('');
            return false;
        } else {
            $('#gradiant_message').hide();
        }
    });
});
