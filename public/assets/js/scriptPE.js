$(document).ready(function() {
    $('#search-form').submit(function(event) {
        event.preventDefault(); // Prevent the form from submitting normally

        // Get the search criteria from the form
        var criteria1 = $('#criteria1').val();
        var criteria2 = $('#criteria2').val();
        // Add more criteria as needed

        // Send an Ajax request to the search endpoint
        $.ajax({
            url: '{{ path('app_produit_troc_search') }}',
            method: 'POST',
            data: {
                nom: criteria1,
                nom_produit_recherche: criteria2
                // Add more criteria as needed
            },
            success: function(response) {
                // Handle the search results
                displaySearchResults(response);
            }
        });
    });

    function displaySearchResults(results) {
        // Clear previous search results
        $('#search-results-container').empty();

        // Iterate over the results and display them
        $.each(results, function(index, result) {
            var resultHtml = '<div>';
            // Customize the HTML structure based on your result properties
            resultHtml += '<p>ID: ' + result.id + '</p>';
            resultHtml += '<p>Name: ' + result.name + '</p>';
            // Add more properties as needed
            resultHtml += '</div>';

            $('#search-results-container').append(resultHtml);
        });
    }
});
