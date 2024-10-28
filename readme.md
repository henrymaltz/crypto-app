## Crypto Price App
The Crypto Price App is a web application that fetches cryptocurrency prices from the CoinGecko API based on specific cryptocurrency symbols. This application is built using the Laravel framework for the backend and utilizes Docker for containerization.

## Installation
To get started with the Crypto Price App, follow these steps:

1. Clone the repository:
git clone https://github.com/henrymaltz/crypto-app.git
cd crypto-app

2. Create a .env file by copying .env.example:
cp .env.example .env

3. Create Docker containers:
docker-compose up -d

4. Run database migrations:
docker-compose exec php_fpm php artisan migrate

## Usage
After the application is up and running, you can access it via http://localhost:8080 (or the port you configured). You can enter cryptocurrency symbols to fetch and display their current prices.

## API Endpoints
GET /api/price/recent?coin={symbol}: Fetches the price of the specified cryptocurrency symbol.
Example: http://127.0.0.1:8080/api/price/recent?coin=ETH

GET /api/price/price/at-date?coin={symbol}&date={yyyy-mm-dd}: Fetches the price of the specified cryptocurrency symbol.
Example: http://127.0.0.1:8080/api/price/at-date?coin=ETH&date=2024-10-28

## Architecture
This application follows a modular architecture. The core components include:

Controllers: Handle incoming requests and return responses.
Models: Represent the data structure and manage data interactions.

## Testing
To run the test suite, execute the following command:
docker-compose exec php_fpm ./vendor/bin/phpunit

## External Libraries
Laravel: The PHP framework used to build the application.
Guzzle: A PHP HTTP client for making requests to the CoinGecko API.
