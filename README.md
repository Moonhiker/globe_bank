# Content Management System (CMS)

## Tech steck
* XAMPP (PHP 8.2, MySQL) 
* HTML & CSS
* Composer
* PHPUnit
* Visual Studio Code
* GitHub Desktop

## Architecture of CMS

![CMS Architecture](/images_for_dokumentation/Overview.PNG "CMS Architecture")

## Short overview over pages

### Admin Area

#### Login Form

This is the login form for admins
![Login](/images_for_dokumentation/Admins_Area/Log_In.png "Login")

#### Main menu with a logged in user

Admin is logged in. Now the Admin can change subjects, pages or admins. With the superglobal variable $_SESSION it is possible to see on each page, who is logged in
![Logged in](/images_for_dokumentation/Admins_Area/Logged_In.png "Logged in")

#### Manage Admins

Under Admins it is possible to manage the admins. Add new admins, edit or delete them. All is saved in a MySQL database in the “admins” table 
![Manage Admins](/images_for_dokumentation/Admins_Area/Manage_Admins.png "Manage Admins")

#### Manage Subjects

In the Subjects overview it is possible to see how many pages are nested in each subject. 
You can turn on or off the subjects including all related pages. 
If position is changed, while deleting or moving forward/backward a subject, all rows will be adjusted automatically (decreased or increased according to the change)
![Manage Subjects](/images_for_dokumentation/Subjects/Subjects_overview.png "Manage Subjects")

Overview on public webpage
![Subjects overview](/images_for_dokumentation/Subjects/Subjects.png "Subjects overview")

#### Nested pages inside a subject

There are 5 nested pages inside the subject “About Globe”. It is also possible to use CRUD (Create, Read, Update, Delete) for all pages
![Nested pages](/images_for_dokumentation/Subjects/Pages_nested_in_Subject.png "Nested pages")

#### Updating a page

![Updating a page](/images_for_dokumentation/Subjects/Edit_Page_Contact_Us.png "Updating a page")

#### View area after updating a page

Under View from each page it is possible to preview the contenct without showing it to the public (Visiblility flag is set to false). On clicking the “Preview of Content” link a new tab will open and show, that this is a preview page

![View area page](/images_for_dokumentation/Subjects/View_Page_Contact_Us.png "View area page")

Preview of Content
![Preview of Content](/images_for_dokumentation/Subjects/Preview_of_Content.png "Preview of Content")

### Public Area

#### Public website

![Credit Cards](/images_for_dokumentation/Main_Site/Consumer_Credit_Cards.png "Credit Cards")

![About Globe Bank History](/images_for_dokumentation/Main_Site/About_Globe_History.png "About Globe Bank History")


