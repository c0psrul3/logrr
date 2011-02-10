Logrr - A HTML5 web based viewer for rsyslog mysql logs
=======================================================

Tired of looking at the same old aged syslog web interfaces? Don't want to shell
out $100+ a month to a Logging provider with a nice interface? Look no further
than Logrr. Its Free, HTML5, mysql based and written to work with rsyslog.

We took [Codeigniter][6] and borrowed the css from [Resque][2], mixed in a little
[jQuery BBQ][3] hooked it up to the mysql [rsyslog][4] logs and tied it all
together with a little [PHP pChart][5]. Simple!

Screenshots
-----------

<img src="https://github.com/pyhub/logrr/raw/master/screenshot1.png" width="600" alt="Screenshot 1" />

<img src="https://github.com/pyhub/logrr/raw/master/screenshot2.png" width="600" alt="Screenshot 1" />

Want to contribute? Great! Fork me!

Installation
-----------

* Checkout Logrr and upload to your hosting provider (preferably on its own subdomain)
* Edit /application/config/database.php and point it to your rsyslog mysql database.
* Open up the web site in your web browser.

Contributing
------------

1. Fork it.
2. Create a branch (`git checkout -b my_markup`)
3. Commit your changes (`git commit -am "Added XYZ Feature"`)
4. Push to the branch (`git push origin my_markup`)
5. Create an [Issue][1] with a link to your branch


[1]: http://github.com/pyhub/logrr/issues
[2]: http://github.com/defunkt/resque
[3]: http://benalman.com/projects/jquery-bbq-plugin/
[4]: http://www.rsyslog.com/
[5]: http://pchart.sourceforge.net/
[6]: http://codeigniter.com/