# Symfony Command Project

This Symfony project contains a custom command designed to 
consume an API, create a raw data, tranform data and create a summary, plus uploading the files generated to a SFPT server. 

Follow the instructions below to install, configure, and use the command.

## Prerequisites

Before you begin, ensure you have the following installed on your system:

- PHP 8.2 or higher
- Symfony 7.1
- Composer
- Symfony CLI (optional, but recommended)

## Installation

1. **Clone the Repository**

   ```bash
   git clone https://github.com/jahm89/telus-test-2024
   cd your-project
   ```

2. **Install Dependencies**
    ```bash
    composer install
   ```

3. **Set Up Environment Variables, Create .env file and add:**
    
    - API_URL=https://dummyjson.com/users
    - DATABASE_URL=mysql://USER:PASSWORD@HOST:PORT/telus_test?serverVersion=5.7.44&charset=utf8mb4
    - SFTP_HOST=
    - SFTP_PORT=
    - SFTP_USER=
    - SFTP_PASSWORD=
    - SFTP_PATH=[PATH-TO-UPLOAD]

4. **Create the database**  
    Run the db.sql script

5. **Run the command**  
    You can run the command manually: 

    ```
        php bin/console app:etl-process-data
    ```
6. **Set Up A Cron Job(Optional):**
    ```bash
        crontab -e
        0 0 * * * /usr/bin/php /path/to/your/project/bin/console app:etl-process-data >> /path/to/your/project/var/log/cron.log 2>&1
    ```

