## About Weather App

Welcome to Weather Forecast Nepal, your go-to destination for accurate weather predictions in cities across Nepal. This
app, crafted with precision using Laravel 10 and Vue 3, provides a convenient way to fetch weather forecasts for the
next 5 days using either the city name or the zip code. The seamless connection between Laravel and Vue is achieved
through the powerful ViteJS, ensuring a smooth and efficient user experience.
The weather data displayed on the app is fetched from [Tomorrow.io](https://www.tomorrow.io/).

## Steps to Follow

- **Clone the repository.**
  ```bash
  git clone [repository_url]
  ```
- run the following package installation commands
   ```bash
  composer install
  npm install
  ```
- copy the env.example file and make the required changes
- create a fresh database
- create the tables and feed data into it using the following command
  ```bash
  php artisan migrate:seed
  ```
- then run the application using following commands
  ```bash 
  php artisan serve and npm run dev commands
  ```
Now, you can access the application at [http://localhost:8000](http://localhost:8000).
