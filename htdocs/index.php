<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" 
  "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>WebDBOverflow - Index</title>
		<base href="<?php echo substr($_SERVER["PHP_SELF"], 0, strlen($_SERVER["PHP_SELF"]) - 9) ?>" target="_blank" /> 
		<link rel="stylesheet" href="assets/css/style.css" type="text/css" />
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
					<?php
					print "\t\t\t\t\t<div class=\"element\">\n \t\t\t\t\t\t<p>Get-variabele gevonden: q=" . $_GET['q'] . "</p>\n\t\t\t\t\t</div>\n";
					?>
					<div class="element">
						<p>Ruimte voor PHP-testoutput:</p>
						<p><?php
							//define access point
							define("WEBDB_EXEC", true);
							
							
							/*
							 * PHP will call this class whenever a class is called upon, but not found in the stack
							 * This function will take the classname of the missing class and try to load it.
							 * 
							 * This is a simple autoloader, only files one dir deep will be found!
							 * This is not a recursive function
							 * @todo: make recursive
							 *
							 * RCreyghton: mapnamen zonder hoofdletter, want zo lijken ze door Git te komen.
							 */
							spl_autoload_register(function ( $classpath ) {
								$parts = explode("_", $classpath);

								switch ($parts[0]) {
									case "Models":
										loadClass("models/" . $parts[1]);
										break;
									case "Controllers":
										loadClass("controllers/" . $parts[1]);
										break;
									case "Views":
										loadClass("views/" . $parts[1]);
										break;
									case "Helpers":
										loadClass("helpers/" . $parts[1]);
										break;
									default:
										throw UnexpectedValueException(
														"The system could not locate the " .
														"following class: " . $classpath .
														". Make sure the class either " .
														"resides in Models, Controllers, Views or Helpers " .
														" subdirectory and the classname start with Model_ for Models etc.."
										);
								}
							});

							/*
							 * use include, php's autoload will never try to load the same
							 * class twice, as such, include is preferred over include_once (or require_once)
							 * because of speed concerns
							 */

							function loadClass($classpath) {
								$classpath = "./" . $classpath . ".php";
								if (is_file($classpath)) {
									include $classpath;
								} else {
									echo "Could not locate classfile: " . $classpath;
									//RCreyghton: uitgecomment: Runtimeexception lijkt niet te bestaan...
									//throw RuntimeException( "Could not locate classfile: " . $classpath );
								}
							}
							/*
							 * Testen van de code
							 */
							$c = new Models_Category();
							$c->name = "nogeencategory";
							$c->description = "testdescript";
							$c->status = 3;
							try {
								$c->save();
							} catch (Exception $exc) {
								echo "<br />Exception bij save() 1:<br />";
								echo $exc->getTraceAsString();
							}

							
							$c->id = 2;
							$c->name = "veranderde_category";
							echo "<br />";
							try {
								$c->save();
							} catch (Exception $exc) {
								echo "<br />Exception bij save() 2:<br />";
								echo $exc->getTraceAsString();
							}
							/*
							 * Einde van testcode
							 */
							?></p>
					</div>
					<div class="element">
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam id sagittis nisl. Nulla elementum ligula ac orci commodo sed congue ipsum dignissim. Donec lacinia, eros a posuere mollis, neque nulla iaculis odio, vitae vulputate diam leo eget neque. Vivamus interdum arcu at velit euismod sit amet viverra augue rhoncus. Mauris vel enim eget lorem ultrices vestibulum. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Ut ut orci vulputate nulla malesuada fermentum sed eget lectus. Vivamus ut odio eu enim fermentum congue.</p>
						<p>Fusce enim nunc, laoreet a euismod eget, consectetur ac lorem. Maecenas eu augue et mauris porta vestibulum et fermentum quam. Nulla eget lorem purus. Cras at auctor elit. Duis sit amet eros enim. Integer gravida, sem non tempor hendrerit, eros arcu tincidunt sapien, in elementum tortor nibh et massa. Pellentesque vitae eros turpis, at blandit quam. Duis consectetur vulputate arcu eu convallis. Aliquam facilisis faucibus dolor, non aliquam libero ultricies a. Sed lacus risus, tempor quis tempus vitae, egestas at justo. Mauris consectetur leo quis dolor tristique id dapibus dolor tincidunt. Sed lacinia felis et ipsum imperdiet id congue lacus hendrerit. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam erat volutpat. Nulla eget quam a elit suscipit volutpat. Praesent et ante et sapien tempor sollicitudin eu sed quam.</p>
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
