### Laravel VueJS FullStack Polling System 

## Requirements
You need to have PHP version **8.1** or above.

## Installation
This project has Vue JS for  the front end and Laravel for the backend.

# Backend
1. Clone the project [git clone <repo-url>]
2. Go to the project root directory 
3. Run `composer install` to install dependencies
4. Create database
5. Copy `.env.example` into `.env` file and adjust parameters
6. Generate a new application key [ php artisan key:generate]
7. Run `php artisan serve` to start the project at http://localhost:8000
8. Run `php artisan migrate` to run the migration files

## Frontend
1. Navigate to `vue` folder using terminal 
2. Run `npm install` to install vue.js project dependencies
3. Start frontend by running `npm run dev`
4. Open the link you'll be given after the above command and begin using the application
