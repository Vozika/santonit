<?



// set variables

$errorpage = "error.html";



$combine = $username . $password; 



// In the form, if surfer puts in User1 for the username and One for the password:

if(strstr($combine,"User1One")) { 

// they get directed to this page:

include ("dogovor.html");



// In the form, if surfer puts in User1 for the username and One for the password:

} else if(strstr($combine,"User2Two")) { 

// they get directed to this page:

include ("directory2/index.htm");



// In the form, if surfer puts in User1 for the username and One for the password:

} else if(strstr($combine,"User3Three")) { 

// they get directed to this page:

include ("directory3/index.htm");



// In the form, if surfer puts in User1 for the username and One for the password:

} else if(strstr($combine,"DogovorDogovor")) { 

// they get directed to this page:

include ("dogovor1.html");



// Wrong usernmae/password combo, they get directed to a custom error page:

} else { Header("Location: $errorpage");

      exit; } 

?>

