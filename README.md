## Yii2 Project Setup

This section outlines the setup process for the Yii2 project.

### Why Yii2 Advanced Application Template?

The Yii2 Advanced Application Template was chosen for this project due to its robust structure, which is well-suited for developing complex web applications. Key advantages include:

*   **Separation of Concerns:** It provides a clear separation between the frontend and backend applications, allowing for independent development and deployment.
*   **Scalability:** The template is designed with scalability in mind, making it easier to grow the application as needed.
*   **Built-in Features:** It includes essential features like user management and a console application for background tasks.
*   **Environment Configuration:** It offers a straightforward way to manage different application environments (e.g., development, testing, production).

### Installation via Composer

To install Yii2 and the necessary dependencies, Composer is used. Follow these steps:

1.  **Install Composer:** If you don't have Composer installed, download and install it from [getcomposer.org](https://getcomposer.org/).
2.  **Install Yii2 Advanced Template:** Navigate to your web server's document root directory in the terminal and run the following command to install the Yii2 Advanced Application Template:

    ```bash
    composer create-project --prefer-dist yiisoft/yii2-app-advanced my-project
    ```
    Replace `my-project` with your desired project name.

### Application Initialization

After the installation is complete, you need to initialize the application. This involves setting up the necessary configurations and directory structures.

1.  **Navigate to the project directory:**
    ```bash
    cd my-project
    ```
2.  **Run the initialization script:**
    ```bash
    php init
    ```
    You will be prompted to choose an environment (e.g., `dev` or `prod`). Select `dev` for a development environment. This command will create the necessary entry scripts (e.g., `index.php`, `yii`) and configure your application.

3.  **Database Setup:**
    *   **Create the database:** You need to create a MySQL database for the application. You can do this using a MySQL client (like phpMyAdmin or the command line). The default database name expected by the configuration is `yii2_blog_db`.
        ```sql
        CREATE DATABASE yii2_blog_db CHARACTER SET utf8 COLLATE utf8_general_ci;
        ```
    *   **Configure database connection:** The database connection details are stored in `common/config/main-local.php`. This file has been pre-configured with the following default credentials:
        *   Hostname: `localhost`
        *   Database name: `yii2_blog_db`
        *   Username: `root`
        *   Password: (empty)
        If your MySQL setup uses different credentials (e.g., a different username, password, or you chose a different database name), you **must** update this file. Open `common/config/main-local.php` and modify the `dsn` (if you changed the database name or host), `username`, and `password` values accordingly. For example:
        ```php
        // common/config/main-local.php
        'components' => [
            'db' => [
                'class' => 'yii\db\Connection',
                'dsn' => 'mysql:host=localhost;dbname=your_database_name', // Update if needed
                'username' => 'your_username', // Update if needed
                'password' => 'your_password', // Update if needed
                'charset' => 'utf8',
            ],
            // ... other components
        ],
        ```

4.  **Apply migrations:**
    ```bash
    php yii migrate
    ```
    This will create the necessary database tables, including the user table for the default user management system.

5.  **Initialize RBAC Roles and Permissions:**
    Run the migrations again to apply the RBAC initialization, which sets up roles like "user" and "admin":
    ```bash
    php yii migrate
    ```
    This command will apply any pending migrations, including the one for RBAC.

6.  **Assign Admin Role (Example):**
    To assign the 'admin' role to a user (e.g., the first user with ID 1), you can run the following SQL command directly in your database after the user has registered:
    ```sql
    INSERT INTO auth_assignment (item_name, user_id, created_at) VALUES ('admin', '1', UNIX_TIMESTAMP());
    ```
    Replace `'1'` with the actual ID of the user you want to make an admin. Alternatively, you can create a console command to manage role assignments more robustly.

After these steps, your Yii2 application should be set up with basic RBAC and accessible through your web browser.
