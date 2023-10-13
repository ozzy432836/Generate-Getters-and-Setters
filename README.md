Generate-Getters-and-Setters
============================

Something I wrote a while ago because I missed the generate getters and setters that Eclipse gives you in for Java. You can look at POG (PHP Object Generator) but its overkill if you are not connecting to DB etc. There is also this: https://github.com/docteurklein/php-getter-setter.vim/blob/master/ftplugin/php_getset.vim but havent tried it yet.

Instructions:
1) all you need is the .php file
2) Drop it on your server in an executable directory so for windows that would probably be c:/inetpub/wwwroot and on links something like /var/www/sitename.com/htdocs
3) Navigate to the url: http://www.example.com/generateObject.php
4) Pretty simple, you get a form.
5) Start by selecting the language you wish to generate a class for (including constructor, transformers, and accessors)
6) VB and PHP work. I cant remember if C# works
7) Type in your variable names
8) Select encapsulation mode
9) If you need more variables, enter the number of fields you need and submit the form
10) Fill in anymore fields with variable names and click submit
11) Thats it. You now have a class in VB or PHP (or possibly C#) and you dont have to write all the getters and setters for all your variables.
12) Downside - in VB (and therefore C#) you cant defint a type for each variable. It just assigns type Object to all of them. Its more useful for PHP OOP
13) Need any more help, email code@digitalquarter.co.uk or code@digitalquarter.uk (buying second domain soon)
14) Test
Test2
Test3
