<?xml version="1.0"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" 
"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Registratie-test</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
    <link rel="stylesheet" href="header-style.css" type="text/css" />
    <link rel="stylesheet" href="register-style.css" type="text/css" />
</head>
</head>

<body>
    <div class="wrapper">

        <div class="header_wrapper wrapped">
            <div class="header_title body_centered">
                <h1>WebDBOverflow</h1>
                <div class="header_service">
                    <a href="login.php">Login</a>
                    <a href="register.php">Registreer</a>
                </div>
            </div>
            <div class="content_open body_centered">
                <ul class="menu">
                    <li><a href="index.php?forum=1" class="menulink">Forum 1</a></li>
                    <li><a href="index.php?forum=2" class="menulink">Forum 2</a></li>
                    <li><a href="index.php?forum=3" class="menulink">Forum 3</a></li>
                </ul>
            </div>
        </div>

        <div class="content_wrapper wrapped"> 
            <div class="content body_centered">
                <div class="element">
                    <h2 class="formheader">Registratie:</h2>
                    <form name="register" class="registerform" method="post" action="register.php">
                        <fieldset>
                            <legend>Je gegevens:</legend>
                            <label for="firstname">Voornaam</label><input type="text" class="input_name" id="firstname" name="firstname" value="" /> <label for="lastname">Achternaam</label><input type="text" class="input_text" id="lastname" name="lastname" value="" /> <br />
                            <label for="email">E-mail adres</label><input type="text" class="input_else" id="email" name="email" value="" /> <br />
                        </fieldset>
                        <fieldset>
                            <legend>Je account:</legend>
                            <label for="nickname">Nickname</label><input type="text" class="input_else" id="nickname" name="nickname" value="" /> <br />
                            <label for="password">Wachtwoord</label><input type="password" class="input_else" id="password" name="password" value="" /><br />
                            <label for="password2">Nogmaals</label><input type="password" class="input_else" id="password2" name="password2" value="" />
                        </fieldset>
                        <input type="submit" class="submitbutton" value="Registreer!" />
                    </form>
                </div>
            </div>
        </div>

        
    </div>
</body>

</html>

