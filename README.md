# GameGo E-commerce Website
We wanted to create a simple and minimalistic e-commerce website for games from various consoles. Customers can create an account and look through our selection of games.
Once they find something that they like, they can add it to their cart and place their order. This website has been tested on Chrome for Windows 10 as well as Vivaldi, a Chromium based browser.

## Built with
* Frontend - HTML, CSS, JavaScript
* Backend - PHP, SQL
* [JQuery](https://jquery.com) - Used for header implementation 
* Icons from - [Font Awesome](https://fontawesome.com) and [Boxicons](https://boxicons.com)

## Contributors
Clara Zhang \
Elaine Zhao \
Anna Lee 

## Structure
- webdev-repo folder:
- `/css/` Contains stylesheet that is used throughout the website
- `/db/`  This is where the connection is to the database from localhost
- `/images/` All the images used in the website
- `/pages/` Website pages
- `/README.md/`

## How to set up the website
1) Download “webdev-repo.zip”
2) Unzip into htdocs folder. The htdocs folder can likely be found in the xampp folder.\
![Picture1](https://user-images.githubusercontent.com/71237361/146461735-a39c6df0-624f-4d5e-a005-a597ce35d259.png)
3) Open the XAMPP Control Panel by clicking on the following icon.\
![Picture2](https://user-images.githubusercontent.com/71237361/146461753-ec78905a-a6e5-452e-9a8c-06a4461e1408.png)
4) Start the Apache and MySQL server. The control panel should look like this: \
![Picture3](https://user-images.githubusercontent.com/71237361/146461767-fb1a916b-c43d-4c1f-94d1-79f35465d6ec.png)
5) Go to the following link to access the SQL database for GameGo.
http://localhost/phpmyadmin/index.php?route=/
The page should look similar to this:\
![Picture4](https://user-images.githubusercontent.com/71237361/146461805-fa537f66-dda7-429b-bf64-7ed5d4358cdd.png)
6) 
6) On the left, click on “webdev_db” to start editing the database for GameGo. Select the second tab on the top labeled “SQL”.
The page should look like this.\
![Picture5](https://user-images.githubusercontent.com/71237361/146461827-00cd2c3a-8fb1-45be-8fd8-964f8b654289.png)
7) Copy and paste the content from the file “webdev_db.sql”  (located at htdocs > webdev-repo > db) in the textbox and click “Go”.
9) Congratulations! You just set up the database for GameGo!
The page should look like this:\
![Picture6](https://user-images.githubusercontent.com/71237361/146461852-c6baaed1-ca70-4f46-a475-1d162fc3a46b.png)
9) Open your browser of choice and connect to the localhost or follow this link to access the homepage of the GameGo website.
http://localhost/webdev-repo/pages/homepage.php
10) If the webdev-repo is in the correct location, the page should look like this: \
![Picture7](https://user-images.githubusercontent.com/71237361/146461868-90f2fdb3-9a54-48c5-9837-e02ab3549e4e.png)

## Where to start?
Once everything set up and connected to the http://localhost/webdev-repo/pages/homepage.php browser through our selection of games with the filter or the search bar. You must be logged in to use any cart functionalities! You can click on login to login or create an account if you don't have one. Once you add the items you'd like proceed to checkout and make any changes you would like in quantity and shipping handling. Once you're happy with everything proceed to checkout and fill in your credit card details and shipping address. Finally, confirm payment and your order has been complete!
