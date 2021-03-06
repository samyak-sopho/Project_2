<?php

// configuration
require("../includes/helpers.php");

// if user reached page via GET (as by clicking a link or via redirect)
if ($_SERVER["REQUEST_METHOD"] == "GET")
{
    // else render form
    render("signup_form.php", ["title" => "SignUp"]);
}

// else if user reached page via POST (as by submitting a form via POST)
else if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    //trim input
    $_POST["name"] = trim($_POST["name"]);
    $_POST["clg"] = trim($_POST["clg"]);
    $_POST["city"] = strtolower(trim($_POST["city"]));
    $_POST["email"] = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);

    // validate submission
    if (empty($_POST["name"]))
    {
        exit("You must provide your name.");
    }
    else if (empty($_POST["clg"]))
    {
        exit("You must provide your college.");
    }
    else if (empty($_POST["city"]))
    {
        exit("You must provide your city.");
    }
    else if (empty($_POST["tel"]) || !ctype_digit($_POST["tel"]))
    {
        exit("You must provide your correct contact info.");
    }
    else if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL))
    {
        exit("You must provide correct email address.");
    }
    else if (!preg_match('/^(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$/', $_POST["pwd"]))
    {
        exit("You must fulfill password requirements.");
    }
    else if (empty($_POST["gender"]))
    {
        exit("You must provide your gender.");
    }
    else if ($_POST["pwd"] !== $_POST["cpwd"])
    {
        exit("Password Mismatch");
    }

    //connecting to database
    $conn = connect();
    
    // insert user into database
    $sql = "INSERT INTO users (name, college, city, contact, email, password, gender)
    VALUES ('".$_POST["name"]."', '".$_POST["clg"]."', '".$_POST["city"]."', '".$_POST["tel"]."', 
    '".$_POST["email"]."', '".password_hash($_POST["pwd"], PASSWORD_DEFAULT)."',
    '".$_POST["gender"]."')";

    if (!mysqli_query($conn, $sql)) {
        exit("Error: E-mail already registered " . mysqli_error($conn));
    }
    else
    {
        // redirect to login page through javascript
        if ($_POST["OneSignaluserID"] != "") {
            $response = sendNotification($_POST["OneSignaluserID"], "172.31.100.28:3128", "edcguest:edcguest");
            // $return["allresponses"] = $response;
            // $return = json_encode($return);
            exit($response);
        }
        else
            exit(null);
    }

}
?>