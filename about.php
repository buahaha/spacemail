<?php
require_once('loadclasses.php');
$page = new Page('About Spacemail');
$html = '<p>Spacemail.tk &copy;2017 Snitch Ashor of BRGF.<br/><br/>
Version 0.1b<br/><br/>
This app is built using php and the <a href="http://getbootstrap.com/">bootstrap</a> framework.<br/>
All interactions with EVE Online are done using the <a href=" https://esi.tech.ccp.is/">EVE Swagger Interface</a><br/>
<br/>
Additional Software used:<br/>
<ul>
<li>ESI php client generated with <a href="http://swagger.io/swagger-codegen/">swagger-codegen</a></li>
<li>Auth was adopted from Fuzzy Steve\'s <a href="https://github.com/fuzzysteve/eve-sso-auth">EVE SSO Auth</a></li>
<li><a href="https://jquery.com/">jQuery</a></li>
<li>jQuery <a href="https://datatables.net/">datatables</a></li>
<li>Twitter <a href="https://twitter.github.io/typeahead.js/">typeahead.js</a></li>
<li>Nakupanda\'s <a href="https://nakupanda.github.io/bootstrap3-dialog/">Bootstrap Dialog</a></li>
<li><a href="http://searchturbine.com/php/phpwee">PHPWee</a> Minifier</li>
<li>Sydcanem\'s <a href="https://github.com/sydcanem/bootstrap-contextmenu">Bootstrap Contextmenu</a></li>
</ul>
<br/>
Special Thanks to a lot of very helpful people in #devfleet and #esi on the tweetfleet slack.<br/>
<br/>
So long,<br/>
o7, Snitch.
</p>
';
$page->addBody($html);
$page->display();
exit;
?>
