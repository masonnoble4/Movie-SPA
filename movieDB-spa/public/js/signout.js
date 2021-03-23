//This function signs a user out. It gets called when the Sign out link is clicked.
function signout() {
    $('#modal-title').html("Sign out");
    $('#modal-content').html('Are you sure you want to sign out from the site?');
    $("li#li-movie > a, li#li-person > a, li#li-genre > a").addClass('disabled');
    $('#modal-button-ok').html("Sign out").show().off('click').click(function() {
        // empty the jwt variable
        jwt = '';

        //Update the main section content
        updateMain('Signing out', 'Confirmation', 'You are now signed out. Thank you for visiting.');

        // Disable the movie, cast, and genre links in the nav bar
        $("li#li-movie > a, li#li-cast > a, li#li-genre > a").addClass('disabled');

        // Show the sign-in link and hide the sign-out link
        $("li#li-signin").show();
        $("li#li-signout").hide();
    });
    $('#modal-button-close').html('Cancel').off('click').click(function () {
        //change the hash back to the old hash if the cancel button is clicked.
        location.hash = oldHash;
    });

    // Display the modal
    $('#modal-center').modal();
}