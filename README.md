"# Lab-Reservation-System" 

**The first page was not written by me, to get to the app, simply click on the "lab reservation request" link, which is
the 3rd bullet under 'Quick Links'.

**This app was run on a local apache server using XAMPP.

**The SQL table is available in a separate file and was managed and created using phpmyadmin.

This is a web app written primarily in PHP 7.2, HTML5, CSS3, and Javascript that reserves lab rooms. 
The system takes in a few bits of information from users through a form and 
submits them into a mysql database that runs on localhost rather than a separate server. 
There is the ability to add multiple reservation dates dynamically, and determine whether the 
reservation is for a course or an event, also dynamically. There are also multiple validation checks and
some basic security checks for things like SQL injection. There are a few notifications and messages that 
determine success and failure, as well as giving some needed information.