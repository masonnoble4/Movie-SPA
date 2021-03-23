<?php
/**
 * Author: Mason Noble
 * Date: 8/1/2020
 * File: signin.php
 * Description: the signup form
 */
?>
<!--------- signup form ----------------------------------------------------------->
<form class="form-signup" style="">
    <!--<input type="hidden" name="form-name" value="signup">-->
    <h1 class="h3 mb-3 font-weight-normal"
        style="padding: 20px; color: #FFFFFF; background-color: black; font-size:22px;text-align: center">Create
        an account at Film Finder.com</h1>
    <div style="width: 250px; margin: auto">
        <label for="name" class="sr-only">Name</label>
        <input type="text" id="signup-name" class="form-control" placeholder="Name" required autofocus>
        <label for="email" class="sr-only">Email</label>
        <input type="email" id="signup-email" class="form-control" placeholder="Email" required>
        <label for="username" class="sr-only">Username</label>
        <input type="text" id="signup-username" class="form-control" placeholder="Username" required>
        <label for="password" class="sr-only">Password</label>
        <input type="password" id="signup-password" class="form-control" placeholder="Password" required>
        <button class="btn btn-lg btn-primary btn-block" style="background-color: black;" type="submit">Sign up
        </button>
        <div class="img-loading-container">
            <div class="img-loading">
                <img src="img/loading.gif">
            </div>
        </div>
        <p style="padding-top: 10px;">Already have an account? <a id="movieDB-signin" href="#signin">Sign in</a>
        </p>
    </div>
</form>

<!-- End section content -->

