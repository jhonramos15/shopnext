 Local Deployment Instructions

Scope: This document outlines the required steps for the correct execution and setup of the ShopNext project within a local (localhost) development environment.


1.0 Prerequisites
Before proceeding with the installation, ensure the following requirements are met:

1.XAMPP Installation: The XAMPP (Apache, MySQL, PHP) software stack must be installed on the local machine.

2.Database File: The database dump file (.sql) must be available (located in the project's /database folder).

2.0 Application Setup Procedure
Follow these steps to configure the web server and project files:

1.Download Project: Download the corresponding project repository/folder.

2.Relocate Project Directory: Move the entire project folder (e.g., "main") into the XAMPP webroot directory (typically C:/xampp/htdocs).

3.Launch XAMPP: Open the XAMPP Control Panel.

4.Start Services: Start both the Apache and MySQL services.

5.Access Dashboard (Optional): Click the "Admin" button for the Apache service to open the XAMPP dashboard (http://localhost/dashboard/).

6.Access Project: In your browser, navigate directly to the project folder. (e.g., http://localhost/main).

3.0 Database Configuration
CRITICAL NOTE: For the application to execute correctly, the ShopNext database must be imported into MySQL.

1.Access phpMyAdmin: From the XAMPP Control Panel, click the "Admin" button for the MySQL service.

2.Create New Database: Create a new, empty database. The database must be named exactly: shopnext.

3.Import Database:

Select the newly created shopnext database.

Navigate to the "Import" tab.

Click "Choose File" and select the .sql file located in the project's /database directory.

Click the "Import" (or "Go") button to execute the import.

Upon successful completion of the database import, the project will be fully operational at the URL established in step 2.6.








