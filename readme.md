
![photo_5](https://github.com/user-attachments/assets/f6fd7644-3b3e-4edc-9387-ba733d17cc07)
![photo_4](https://github.com/user-attachments/assets/7c4d07fc-4804-40b5-8449-4f42c34518f9)
![photo_3](https://github.com/user-attachments/assets/f254a2a6-7e92-4068-963d-d0cc5b46233b)
![photo_2](https://github.com/user-attachments/assets/68e6e66b-1dd6-42be-bb61-572567d7535d)
![photo_1](https://github.com/user-attachments/assets/a6cdda2b-b75e-4295-a55c-e2ac3a255a96)
![photo_8](https://github.com/user-attachments/assets/88c66d6c-ce1b-4c4e-8702-efc995d5ac53)
![photo_7](https://github.com/user-attachments/assets/c9960994-352f-438f-bbce-68f626ed02dc)
![photo_6](https://github.com/user-attachments/assets/ded73396-b8e0-4726-9c5e-6370d11d0f53)



# Local Deployment Instructions

Scope: This document outlines the required steps for the correct execution and setup of the ShopNext project within a local (localhost) development environment.


# 1.0 Prerequisites
Before proceeding with the installation, ensure the following requirements are met:

 1.XAMPP Installation: The XAMPP (Apache, MySQL, PHP) software stack must be installed on the local machine.

 2.Database File: The database dump file (.sql) must be available (located in the project's /database folder).

# 2.0 Application Setup Procedure
Follow these steps to configure the web server and project files:

1.Download Project: Download the corresponding project repository/folder.

2.Relocate Project Directory: Move the entire project folder (e.g., "main") into the XAMPP webroot directory (typically C:/xampp/htdocs).

3.Launch XAMPP: Open the XAMPP Control Panel.

4.Start Services: Start both the Apache and MySQL services.
 
5.Access Dashboard (Optional): Click the "Admin" button for the Apache service to open the XAMPP dashboard (http://localhost/dashboard/).

6.Access Project: In your browser, navigate directly to the project folder. (e.g., http://localhost/main).

# 3.0 Database Configuration
CRITICAL NOTE: For the application to execute correctly, the ShopNext database must be imported into MySQL.

1.Access phpMyAdmin: From the XAMPP Control Panel, click the "Admin" button for the MySQL service.

2.Create New Database: Create a new, empty database. The database must be named exactly: shopnext.

 3.Import Database:

Select the newly created shopnext database.

Navigate to the "Import" tab.

Click "Choose File" and select the .sql file located in the project's /database directory.

Click the "Import" (or "Go") button to execute the import.

Upon successful completion of the database import, the project will be fully operational at the URL established in step 2.6.







