
# Laravel Project

This repository contains a Laravel project set up to run using Docker. Below are the steps to clone the repository, set up the environment, and run the Laravel application and phpMyAdmin using Docker.

## Project Setup

### Prerequisites

- **Docker**: Make sure you have Docker installed on your system. You can download and install Docker from [here](https://docs.docker.com/get-docker/).
- **Docker Compose**: Ensure Docker Compose is also installed (it comes with Docker Desktop).

### Steps to Set Up and Run the Project

1. **Clone the Repository**:

   Clone the project repository from GitHub:

   ```bash
   git clone https://github.com/srinivasgundale/innoscripta-laravel.git
   ```

2. **Navigate to the Project Directory**:

   ```bash
   cd innoscripta-laravel
   ```

3. **Set up Environment Variables**:

   Make sure you add a `.env` file to the root folder of the project. You can copy the `.env.example` file and modify it as per your requirements:

   ```bash
   cp .env.example .env
   ```

4. **Start the Docker Containers**:

   To build and run the application with Docker, use the following command:

   ```bash
   docker-compose up --build
   ```

   This command will:
   - Build the Docker images.
   - Start the necessary containers for the Laravel application, MySQL, Nginx, and phpMyAdmin.

5. **Run Laravel Artisan Commands**:

   After the containers are up and running, run the following commands to set up the Laravel configuration, database, and other necessary steps:

   - **Clear Configuration Cache**:

     ```bash
     docker-compose exec app php artisan config:clear
     ```

   - **Cache the Configuration**:

     ```bash
     docker-compose exec app php artisan config:cache
     ```

   - **Run Database Migrations**:

     ```bash
     docker-compose exec app php artisan migrate
     ```

   - **Install Laravel Passport**:

     ```bash
     docker-compose exec app php artisan passport:install
     ```

   - **Set Permissions for Laravel Directories**:

     Ensure proper permissions for the `storage` and `bootstrap/cache` directories:

     ```bash
     docker-compose exec app chmod -R 777 storage bootstrap/cache
     ```

6. **Access the Application**:

   Once everything is set up, you can access the Laravel application at:

   - **Laravel Application**: [http://localhost:8000](http://localhost:8000) (You can change the port number in the `docker-compose.yml` file if needed).
   
   - **phpMyAdmin**: [http://localhost:8001](http://localhost:8001) (You can change the port number in the `docker-compose.yml` file if needed).

### Changing Port Numbers

If the default ports (`8000` for the Laravel application and `8001` for phpMyAdmin) are already in use or you want to use different ports, you can modify the `docker-compose.yml` file.

- **For Laravel Application**: Modify the `ports` section under the `webserver` service.
  
  ```yaml
  webserver:
    ports:
      - "8000:80" # Change the port number on the left side (e.g., "8080:80")
  ```

- **For phpMyAdmin**: Modify the `ports` section under the `phpmyadmin` service.
  
  ```yaml
  phpmyadmin:
    ports:
      - "8001:80" # Change the port number on the left side (e.g., "8081:80")
  ```

After making changes, restart the containers:

```bash
docker-compose down
docker-compose up --build
```

### Notes

- Ensure that your `.env` file contains correct database credentials, matching those in your `docker-compose.yml` file.
- All Docker services, including Laravel, MySQL, Nginx, and phpMyAdmin, are managed using Docker Compose.
- The database is set up using MySQL, and phpMyAdmin is provided for database management via the browser.

### Troubleshooting

If you encounter any issues, here are some potential solutions:

- **Permission Issues**: Ensure you run the `chmod` command to set the appropriate permissions for the `storage` and `bootstrap/cache` directories.
  
- **Database Issues**: If the database doesn't work as expected, ensure your `.env` file has the correct MySQL settings (e.g., `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD`).

### Conclusion

By following the steps above, you should be able to set up and run this Laravel application inside a Docker environment. If you encounter any problems or need additional help, feel free to create an issue in the repository.
