/***********************************************************************************************************
 ******                            Show Persons                                                      ******
 **********************************************************************************************************/
//This function shows all actors. It gets called when a user clicks on the Credits link in the nav bar.
//Enable pagination and sorting
function showPersons(offset = 0) {
    console.log('show all persons');
    let limit = ($('#person-limit-select').length) ? $('#person-limit-select option:checked').val() : 20;
    let sort = ($('#person-sort-select').length) ? $('#person-sort-select option:checked').val() : 'person_name' +
        ':asc';

    //construct the url with the limit, offset,sort variables
    let url = baseUrl_API + '/api/v1/persons?limit=' + limit + "&offset=" + offset + "&sort=" + sort;

    axios({
        method: 'get',
        url: url,
        cache: true,
        headers: {"Authorization": "Bearer " + jwt}
    }).then(function (response) {
        displayPersons(response.data);
    }).catch(function (error) {
        handleAxiosError(error);
    });
}


//Callback function: display all actors; The parameter is a promise returned by axios request.
function displayPersons(response) {
    let _html;
    _html =
        "<div class='content-row content-row-header'>" +
        "<div class='person-name'>Name</></div>" +
        "<div class='person-name'>Id Number</></div>" +
        "</div>";
    persons = response.data;
    persons.forEach(function (person, x) {
        let cssClass = (x % 2 == 0) ? 'content-row' : 'content-row content-row-odd';
        _html += "<div class='" + cssClass + "'>" +
            "<div class='person-name'>" +
            "<span title='Get People details'>" + person.person_name + "</span>" +
            "</div>" +
            "<div class='person-person-id'>" + person.person_id + "</div>" +
            "</div>" +
            "<div class='container person-detail' id='person-detail-" + person.person_name + "' style='display: none'></div>";
    });

    //update a div block for pagination links and selection lists for limiting and sorting persons
    _html += "<div class='content-row person-pagination'><div>";

    //pagination
    _html += paginatePersons(response);

    //limit persons
    _html += limitPersons(response);

    //sort persons
    _html += sortPersons(response);

    //close the div blocks
    _html += "</div></div>";

    //Finally, update the page
    updateMain('Movie Credits', 'All Cast and Crew', _html);
}


/***********************************************************************************************************
 ******                            Show Details of a Person                                          ******
 **********************************************************************************************************/

/* Display a person's details. It get called when a user clicks on a person's name in
 * the Credits list. The parameter is the person's name.
*/
function showPerson(person) {
    console.log('get actors info');
    let url = baseUrl_API + '/api/v1/persons/' + person;

    axios({
        method: 'get',
        url: url,
        cache: true,
        headers: {"Authorization": "Bearer " + jwt}
    }).then(function (response) {
        displayPerson(person_name, response);
    }).catch(function (error) {
        handleAxiosError(error);
    })
}


// Callback function that displays all details of a person.
// Parameters: person_name, a promise
function displayPerson(person_name, response) {
    let _html;
    person = response.data;
    _html =
        "<div class='person-detail-row'><div class='person-detail-label'>Actor's Name</div><div class='person-detail-field'>" + person.person_name + "</div></div>" +
        "<div class='person-detail-row'><div class='person-detail-label'>Gender</div><div class='person-detail-field'>" + person.cast[0].gender + "</div></div>" +
        "</div></div>";

    $('#person-detail-' + person_name).html(_html);
    $("[id^='person-detail-']").each(function () {   //hide the visible one
        $(this).not("[id*='" + person_name + "']").hide();
    });

    $('#person-detail-' + person_name).toggle();
}

//******************************************
//this function handles errors occurred by an AXIOS request
//******************************************

function handleAxiosError(error) {
    let errMessage;
    if (error.response) {
        //The request was made and the server responded with a status code of 4xx or 5xx
        errMessage = {"Code": error.response.status, "Status": error.response.data.status};
    } else if (error.request) {
        //the request was made but no response was received
        errMessage = {"Code": error.request.status, "Status": error.request.data.status}
    } else {
        //something happened in setting up the request that triggered an error
        errMessage = JSON.stringify(error.message, null, 4);
    }
    showMessage('Error', errMessage);
}

//******************************************
//Pagination, sorting and limiting persons
//******************************************

function paginatePersons(response) {
    //calculate total number of pages
    let limit = response.limit;
    let totalCount = response.totalCount;
    let totalPages = Math.ceil(totalCount / limit);

    //determine the current page showing
    let offset = response.offset;
    let currentPage = offset / limit + 1;

    //retrieve the array of links from response json
    let links = response.links;

    //convert an array of links to json document. Keys are "self", "prev", "next", "first", "last"; values are offsets
    let pages = {};

    //extract offset from each link and store it in pages
    links.forEach(function (link) {
        let href = link.href;
        let offset = href.substr(href.indexOf('offset') + 7);
        pages[link.rel] = offset;
    });

    if (!pages.hasOwnProperty('prev')) {
        pages.prev = pages.self;
    }

    if (!pages.hasOwnProperty('next')) {
        pages.next = pages.self;
    }

    //generate HTML code for links
    let _html = `Showing Page ${currentPage} of ${totalPages}&nbsp;&nbsp;&nbsp;&nbsp;
    <a href='#person' title="first page" onclick='showPersons(${pages.first})'> << </a>
    <a href='#person' title="previous page" onclick='showPersons(${pages.prev})'> < </a>
    <a href='#person' title="next page" onclick='showPersons(${pages.next})'> > </a>
    <a href='#person' title="last page" onclick='showPersons(${pages.last})'> >> </a>`

    return _html;
}

//limit persons

function limitPersons(response) {
    //define an array of persons per page options
    let personsPerPageOptions = [5, 10, 20, 30];

    //create a selection list for limiting persons
    let _html = `&nbsp;&nbsp;&nbsp;&nbsp; Items per page: <select id='person-limit-select' onChange='showPersons()'>`;
    personsPerPageOptions.forEach(function (option) {
        let selected = (response.limit == option) ? "selected" : "";
        _html += `<option ${selected} value="${option}">${option}</option>`;
    })
    _html += "</select>";
    return _html;
}


//sort Persons

function sortPersons(response) {
    //create the selection list for sorting
    let sort = response.sort;

    //sort field and direction: convert json to a string then remove {, } and ""
    let sortString = JSON.stringify(sort).replace(/["{}]+/g, "");

    //define a JSON containing sort options
    let sortOptions = {
        "person_name:asc": "Name: A - Z",
        "person_name:desc": "Name: Z - A",
    };

    //create the selection list
    let _html = `&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Sort by: <select id='person-sort-select' onChange='showPersons()'>`;
    for (let option in sortOptions) {
        let selected = (option == sortString) ? "selected" : "";
        _html += `<option ${selected} value="${option}">${sortOptions[option]}</option>`;
    }

    _html += "</select>";
    return _html;
}