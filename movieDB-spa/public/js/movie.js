/***********************************************************************************************************
 ******                            Show Movies                                                  ******
 **********************************************************************************************************/
//This function shows all movies. It gets  call when a user clicks on the Movie link in the nav bar.
function showMovies() {
    //console.log('show all the movies');
    //Constant of the url
    const url = baseUrl_API + '/api/v1/movies';

    $.ajax({
        url: url,
        headers: {"Authorization": "Bearer " + jwt},

    }).done(function (data) {
        //display all the movies
        displayMovies(data);

    }).fail(function (xhr, textStatus) {
        let err = {"Code": xhr.status, "Status": xhr.responseJSON.status};
        showMessage('Error', JSON.stringify(err, null, 4));

    });
}


//Callback function: display all movies and search button; The parameter is an array of movie objects.
function displayMovies(movies, subheading) {
    let _html;
    _html = `
<div style='text-align: right; margin-bottom: 3px'>
            <input id='search-term' placeholder='Enter search terms'> 
            <button id='btn-movie-search' onclick='searchMovies()'>Search</button></div>
<div class='content-row content-row-header'>
        <div class='movie-title'>Title</div>
        <!-- <div class='movie-budget'>Budget</div> -->
       <!-- <div class='movie-homepage'>Homepage</div> -->
<!--        <div class='movie-overview'>Overview</div>-->
        <!-- <div class='movie-popularity'>Popularity</div> -->
        <div class='movie-releaseDate'>Release Date</div>
        <div class='movie-revenue'>Revenue</div>
        <!-- <div class='movie-runtime'>Runtime (minutes)</div> -->
        <!-- <div class='movie-status'>Status</div> -->
        <div class='movie-tagline'>Tag Line</div>
        </div>`;
    for (let x in movies) {
        let movie = movies[x];
        let cssClass = (x % 2 == 0) ? 'content-row' : 'content-row content-row-odd';
        _html += `<div id='content-row-${movie.movie_id}' class='${cssClass}'>
            <div class='movie-title'>
                <span class='list-key' data-movie='${movie.movie_id}' 
                     onclick=showMovieGenres('${movie.movie_id}') 
                     title='Get information about a movie'>${movie.title}
                </span>
            </div>
            <!-- <div class='movie-budget'>${movie.budget}</div> Shows up in modal when user clicks title -->
            <!-- <div class='movie-homepage'>${movie.homepage}</div> Unnecessary info -->
            <!-- <div class='movie-popularity'>${movie.popularity}</div> Metric that has no base, unnecessary-->
            <div class='movie-releaseDate'>${movie.release_date}</div>
            <div class='movie-revenue'>${movie.revenue}</div>
            <!-- <div class='movie-runtime'>${movie.runtime}</div> -->
            <!-- <div class='movie-status'>${movie.movie_status}</div> -->
            <div class='movie-tagline'>${movie.tagline}</div>
            </div>`;
    }
    //Finally, update the page
    subheading = (subheading == null) ? 'All Movies' : subheading;
    updateMain('Movies', subheading, _html);
}


/***********************************************************************************************************
 ******                            Show Genres associated with a Movie                                 ******
 **********************************************************************************************************/
/* Display genres of a movie. It get called when a user clicks on a movie's name in
 * the movie list. The parameter is the movie's id.
*/

//Display classes taught by a professor in a modal
function showMovieGenres(id) {
    //console.log('show a movie\'s genres' + id);
    const title = $("span[data-movie='" + id + "']").html();
    const url = baseUrl_API + '/api/v1/movies/' + id + '/genres';

    $.ajax({
        url: url,
        headers: {"Authorization": "Bearer " + jwt}
    }).done(function (genres) {
        displayMovieGenres(title, genres);
    }).fail(function (xhr) {
        let err = {"Code": xhr.status, "Status": xhr.responseJSON.status};
        showMessage('Error', JSON.stringify(err, null, 4));
    });
}


// Callback function that displays all Genres associated to a movie.
// Parameters: movie's name, an array of Genre objects
function displayMovieGenres(title, genres) {
    console.log(genres);
    console.log(genres.genre.genre_name);
    let _html = "<div class='class'>No genres were found.</div>";

    Object.size = function (obj) {
        var size = 0, key;
        for (key in obj) {
            if (obj.hasOwnProperty(key)) size++;
        }
        return size;
    };

    // Get the size of an object
    let size = Object.size(genres);
    console.log(size);


    if (size > 0) {
        _html = "<table>" +
            "<tr>" +
            "<th class='genre-name'>Genre</th>" +
            "<th class='genre-budget'>Budget</th>" +
            "<th class='genre-runtime'>Runtime</th>" +
            "<th class='genre-voteScr'>Score</th>" +
            "<th class='genre-voteCnt'>Votes</th>" +
            "</tr>";

        // for (let x in genres) {
        _html += "<tr>" +
            "<td class='genre-name'>" + genres.genre.genre_name + "</td>" +
            "<td class='genre-budget'>$" + genres.movie.budget + "</td>" +
            "<td class='genre-runtime'>" + genres.movie.runtime + " mins</td>" +
            "<td class='genre-voteScr'>" + genres.movie.vote_average + "</td>" +
            "<td class='genre-voteCnt'>" + genres.movie.vote_count + "</td>" +
            "</tr>"
        //}
        _html += "</table>"
    }

    // set modal title and content
    $('#modal-title').html(title);
    $('#modal-button-ok').hide();
    $('#modal-button-close').html('Close').off('click');
    $('#modal-content').html(_html);

    // Display the modal
    $('#modal-center').modal();
}

function searchMovies() {
    //console.log('searching for movies');
    let term = $("#search-term").val();
    const url = baseUrl_API + "/api/v1/movies?q=" + term;

    //Update the subheading according to the term
    let subheading = '';
    if (term == '') { // search term is empty
        subheading = "All Movies";
    } else if (isNaN(term)) { // search term is non number
        subheading = 'Movies containing "' + term + '"';

    } else { // search term is a number
        subheading = 'Movies whose revenue is >= ' + term;

    }

    fetch(url, {
        method: "GET",
        headers: {"Authorization": "Bearer " + jwt}
    })
        .then(checkFetch) //check for errors
        .then(response => response.json()) //extract the json from the response.
        .then(movies => displayMovies(movies, subheading)) // display movies
        .catch(err => showMessage("Errors", err)) // display errors
}