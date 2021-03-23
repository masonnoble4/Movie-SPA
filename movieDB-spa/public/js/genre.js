

//This function gets called when the Genre link in the nav bar is clicked. It shows all the records of genres
function showGenres() {
    const url = baseUrl_API + "/api/v1/genres";

    fetch(url, {
        method: "GET",
        headers: {"Authorization": "Bearer " + jwt}
    })
        .then(checkFetch)  //check for errors
        .then(response => response.json()) //extract the json from the response
        .then (genres => displayGenres(genres))  //display genres
        .catch(err => showMessage("Errors", err))  //display errors
}


//Callback function that shows all the genres. The parameter is an array of genres.
// The first parameter is an array of genres and second parameter is the subheading, defaults to null.
function displayGenres(genres, subheading=null) {
    // Row of headings
    let _html = `<div style='text-align: right; margin-bottom: 3px'>
            <div class='content-row content-row-header'>
            <div class='genre-id'>Genre ID</div>
            <div class='genre-name'>Genre Name</div>
            </div>`;  //end the row

    // content rows
    for (let x in genres) {
        let genre = genres[x];
        _html += `<div class='content-row'>
            <div class='genre-id'>${genre.genre_id}</div>
            <div class='genre-name' id='genre-edit-name-${genre.genre_id}'>${genre.genre_name}</div>`;
        if (role == 1) {
            _html += `<div class='list-edit'><button id='btn-genre-edit-${genre.genre_id}' onclick=editGenre('${genre.genre_id}') class='btn-light'> Edit </button></div>
            <div class='list-update'><button id='btn-genre-update-${genre.genre_id}' onclick=updateGenre('${genre.genre_id}') class='btn-light btn-update' style='display:none'> Update </button></div>
            <div class='list-delete'><button id='btn-genre-delete-${genre.genre_id}' onclick=deleteGenre('${genre.genre_id}') class='btn-light'>Delete</button></div>
            <div class='list-cancel'><button id='btn-genre-cancel-${genre.genre_id}' onclick=cancelUpdateGenre('${genre.genre_id}') class='btn-light btn-cancel' style='display:none'>Cancel</button></div>`
        }
        _html += '</div>';  //end the row
    }

    //the row of element for adding a new genre
    if (role == 1) {
        _html += `<div class='content-row' id='genre-add-row' style='display: none'> 
            <div class='genre-id genre-editable' id='genre-new-id' contenteditable='true'></div>
            <div class='genre-name genre-editable' id='genre-new-name' contenteditable='true'></div>
            <div class='list-update'><button id='btn-add-genre-insert' onclick='addGenre()' class='btn-light btn-update'> Insert </button></div>
            <div class='list-cancel'><button id='btn-add-genre-cancel' onclick='cancelAddGenre()' class='btn-light btn-cancel'>Cancel</button></div>
            </div>`;  //end the row

        // add new genre button
        _html += `<div class='content-row genre-add-button-row'><div class='genre-add-button' onclick='showAddRow()'>+ ADD NEW GENRE</div></div>`;
    }
    //Finally, update the page
    subheading = (subheading == null) ? 'All Genres' : subheading;
    updateMain('Genres', subheading, _html);
}

/***********************************************************************************************************
 ******                            Edit Genre                                                       ******
 **********************************************************************************************************/

// This function gets called when a user clicks on the Edit button to make items editable
function editGenre(id) {
    //Reset all items
    resetGenre();

    //select all divs whose ids begin with 'genre' and end with the current id and make them editable
    $("div[id^='genre-edit'][id$='" + id + "']").each(function () {
        $(this).attr('contenteditable', true).addClass('genre-editable');
    });

    $("button#btn-genre-edit-" + id + ", button#btn-genre-delete-" + id).hide();
    $("button#btn-genre-update-" + id + ", button#btn-genre-cancel-" + id).show();
    $("div#genre-add-row").hide();
}

//This function gets called when the user clicks on the Update button to update a genre record
function updateGenre(id) {
    let data = {};

    //Select all divs whose ids begin with 'genre-edit-' and end with the current id.
    //Extract genre details from the divs and create a JSON object
    $("#genre-edit-name-" + id).each(function(){
        let fieldEnd = $(this).attr('id').split('-')[2];  //The second part of an ID is the field name
        let fieldStart = "genre_";
        let field = fieldStart.concat(fieldEnd);
        let value = $(this).html();  //content of the div
        data[field] = value;
        data["genre_id"] = id;
    })

    //Make a fetch request to update the genre
    const url = baseUrl_API + '/api/v1/genres/' + id;
    fetch(url, {
        method: 'PUT',
        headers: {
            "Authorization": "Bearer " + jwt,
            "Content-Type": "application/json"
        },
        body: JSON.stringify(data)
    })
        .then(checkFetch)
        .then(() => resetGenre())  //reset genres
        .catch(err => showMessage("Errors", err))
}


//This function gets called when the user clicks on the Cancel button to cancel updating a genre
function cancelUpdateGenre(id) {
    showGenres();
}

/***********************************************************************************************************
 ******                            Delete Genre                                                    ******
 **********************************************************************************************************/

// This function confirms deletion of a genre. It gets called when a user clicks on the Delete button.
function deleteGenre(id) {
    $('#modal-button-ok').html("Delete").show().off('click').click(function () {
        removeGenre(id);
    });
    $('#modal-button-close').html('Cancel').show().off('click');
    $('#modal-content').html('Are you sure you want to delete the Genre?');

    // Display the modal
    $('#modal-center').modal();
}

// Callback function that removes a genre from the system. It gets called by the deleteGenre function.
function removeGenre(id) {
    let url = baseUrl_API + '/api/v1/genres/' + id;
    fetch(url, {
        method: 'DELETE',
        headers: {"Authorization": "Bearer " + jwt}
    })
        .then(checkFetch)
        .then(()=>showGenres())  //reload the genres
        .catch(err => showMessage("Errors",  err))
}


/***********************************************************************************************************
 ******                            Add Genre                                                       ******
 **********************************************************************************************************/
//This function shows the row containing editable fields to accept user inputs.
// It gets called when a user clicks on the Add New Genre link
function showAddRow() {
    resetGenre(); //Reset all items
    $('div#genre-add-row').show();
}

//This function inserts a new genre. It gets called when a user clicks on the Insert button.
function addGenre() {
    let data = {};

    let newId = $("#genre-new-id").html();
    let newName = $("#genre-new-name").html();
    data = {
        "genre_id": newId,
        "genre_name": newName
    };

    //send the request via fetch
    const url = baseUrl_API + '/api/v1/genres';

    fetch(url, {
        method: 'POST',
        headers: {
            "Authorization": "Bearer " + jwt,
            "Accept": 'application/json',
            "Content-Type": 'application/json'
        },
        body: JSON.stringify(data)
    })
        .then(checkFetch)
        .then(() => showGenres())  //reload the genre list
        .catch(err => showMessage("Errors", err))
}

// This function cancels adding a new genre. It gets called when a user clicks on the Cancel button.
function cancelAddGenre() {
    $('#genre-add-row').hide();
}

/***********************************************************************************************************
 ******                            Check Fetch for Errors                                             ******
 **********************************************************************************************************/
/* This function checks fetch request for error. When an error is detected, throws an Error to be caught
 * and handled by the catch block. If there is no error detected, returns the promise.
 * Need to use async and await to retrieve JSON object when an error has occurred.
 */
let checkFetch = async function (response) {
    if (!response.ok) {
        await response.json()  //need to use await so Javascript will until promise settles and returns its result
            .then(result => {
                throw Error(JSON.stringify(result, null, 4));
            });
    }
    return response;
}


/***********************************************************************************************************
 ******                            Reset Genre section                                             ******
 **********************************************************************************************************/
//Reset genre section: remove editable features, hide update and cancel buttons, and display edit and delete buttons
function resetGenre() {
    // Remove the editable feature from all divs
    $("div[id^='genre-edit-']").each(function () {
        $(this).removeAttr('contenteditable').removeClass('genre-editable');
    });

    // Hide all the update and cancel buttons and display all the edit and delete buttons
    $("button[id^='btn-genre-']").each(function () {
        const id = $(this).attr('id');
        if (id.indexOf('update') >= 0 || id.indexOf('cancel') >= 0) {
            $(this).hide();
        } else if (id.indexOf('edit') >= 0 || id.indexOf('delete') >= 0) {
            $(this).show();
        }
    });
}