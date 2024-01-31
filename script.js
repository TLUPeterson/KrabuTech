function lookupFileLocation() {
    // Get the meeting ID from the input field
    var meetingID = $('#meetingID').val();
    
    // AJAX request to the server (lookup.php)
    $.ajax({
        type: 'POST',
        url: 'lookup.php',
        data: { meetingID: meetingID },
        success: function(response) {
          // If the server response is successful
            if (response.success) {
                parseMeetingData(response.fileContent);
            } else {
                // If server response indicates a failure
                $('#result').html("<p>Error: Unable to process the request.</p>");
            }
        },
        error: function(xhr, status, error) {
            console.error("AJAX request failed:", status, error);
            $('#result').html("<p>Error: Unable to process the request.</p>");
        }
    });
}

//Parse and display meeting data from xml content
function parseMeetingData(xmlContent) {
    var xmlDoc = $.parseXML(xmlContent);
    var $xml = $(xmlDoc);

    //Finds the relevant field from xml. In this case 'meeting'
    var fields = $xml.find('table[name="meeting"] > fields');

    var name = fields.find('field[name]').attr('name');
    var sysid = fields.find('field[sysid]').attr('sysid');
    var date = fields.find('field[date]').attr('date');
    
    //Creates html content from extracted data
    var htmlContent = '<div>' +
    '<div>' + name + '</div>' +
    '<div>' + sysid + '</div>' +
    '<div>' + date + '</div>' +
    '</div>';
     //Displays the html
    $('#result').html(htmlContent);
}