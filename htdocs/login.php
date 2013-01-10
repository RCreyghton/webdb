<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" 
"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>WebDBOverflow - Login</title>
    <link rel="stylesheet" href="./assets/css/style.css" type="text/css" />
    <link rel="stylesheet" href="register-style.css" type="text/css" />
  </head>
  <body>
    <div class="wrapper">
      <div class="header_wrapper wrapped">
        <div class="header_title body_centered">
          <h1>WebDBOverflow</h1>
          <div class="header_service">
            <ul>
              <li>
                <a href="login.php">Login</a>
              </li>
              <li>
                <a href="register.php">Registreer</a>
              </li>
            </ul>
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

      <div class="content_wrapper wrapped light_grey_gradient"> 
        <div class="content body_centered">
          <div class="element">
            <h2 class="formheader">Inloggen:</h2>
            <form name="login" class="loginform" method="post" action="login.php">
              <fieldset>
                <legend>Inloggen in je account:</legend>
                <label for="nickname">Nickname</label><input type="text" class="input_else" id="nickname" name="nickname" value="" /> <br />
                <label for="password">Wachtwoord</label><input type="password" class="input_else" id="password" name="password" value="" /><br />
                <label for="password2">Nogmaals</label><input type="password" class="input_else" id="password2" name="password2" value="" />
              </fieldset>
              <input type="submit" class="submitbutton" value="Login!" />
            </form>
          </div>
        </div>
      </div>

      <div class="footer_wrapper wrapped light_grey_gradient">
        <div class="content_close body_centered">
          <span>
            &copy; WebDBOverflow - all rights reserved.
          </span>
        </div>
        
        <div class="statistics body_centered">
          <div class="statistics_container">
            <h3><span class="hero_number">325</span> vragen</h3>
            <h3><span class="hero_number">868</span> beantwoord</h3>
            <h3><span class="hero_number">754</span> onbeantwoord</h3>
            <h3><span class="hero_number">86.9%</span> verhouding</h3>
          </div>

          <div class="statistics_container">
            <h3><span class="hero_number">325</span> categorie&euml;n</h3>
            <h3><span class="hero_number">325</span> gebruikers</h3>
            <h3><span class="hero_number">868</span> reacties</h3>
          </div>

          <div class="statistics_container">
            <a href="http://validator.w3.org/check?uri=referer"><img src="http://www.w3.org/Icons/valid-xhtml11-blue" alt="Valid XHTML 1.1" height="31" width="88" /></a>
            <a href="http://jigsaw.w3.org/css-validator/check/referer"><img style="border:0;width:88px;height:31px" src="http://jigsaw.w3.org/css-validator/images/vcss-blue" alt="Valid CSS!" /></a>
          </div>
        </div>
      </div>

    </div>
  </body>
</html>
