#Spacemail.tk#
Out-of-game mail client for EVE Online.
Copyright 2017 Snitch Ashor of BRGF.

#Requirements#
+ php 5.5+
+ php-curl
+ MySQL
+ php-mysqli
+ For certain features (cookies), site should be running via ssl

#Installation#

1. Create a Database for the app.
2. Import schema.sql from the SQL subfolder
3. Go to https://developers.eveonline.com/ and register an app with the following scopes:
	+ esi-calendar.respond_calendar_events.v1
	+ esi-calendar.read_calendar_events.v1
	+ esi-mail.organize_mail.v1
	+ esi-mail.read_mail.v1
	+ esi-mail.send_mail.v1
	+ esi-characters.read_contacts.v1
	+ esi-characters.read_notifications.v1

	The callback url should be http(s)://<domain>/<app path>/login.php

4. Rename config.php.sample to config.php and edit it. Fill in the database and developer app credentials and put a random string for the salt. This one is used to add some security to authentication cookies. Add at least one admin by his or her characterID. If you want to keep track of what you added you can use associative arrays like array("Snitch" => 90976676,)

Done.

#Version history#

+ 0.1b First public release
