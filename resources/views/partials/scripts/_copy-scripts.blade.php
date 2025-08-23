<script>
    // Add click event listener to the button with class 'click_btn'
    $('.copy_btn').on('click', function(e) {
        e.preventDefault();
        // Get the value of the 'copy_value' attribute
        var valueToCopy = $(this).attr('copy_value');

        // Create a temporary input element
        var $tempInput = $('<input>');
        $('body').append($tempInput);

        // Set the value of the temporary input element to the value you want to copy
        $tempInput.val(valueToCopy);

        // Select the contents of the temporary input element
        $tempInput.select();

        // Copy the selected text to the clipboard
        document.execCommand('copy');

        // Remove the temporary input element
        $tempInput.remove();

        // Optionally, you can provide some visual feedback to the user that the copy was successful
        // For example, you can display a tooltip or change the button text temporarily
        $(this).text('Copied!');
        setTimeout(function() {
            $('.copy_btn').text('Copy');
        }, 1500); // Reset the button text to 'Copy' after 1.5 seconds
    });
</script>
