## Foodics App


### HOW TO USE

- Clone the project and install composer dependencies
- Create .env file from .env.example using `cp .env.example .env`
- Migrate database tables and seed products and ingredients data using `php artisan migrate --seed`
- Now, you can create a new order using the endpoint: `/api/orders`. Something like:

```http request
POST http://127.0.0.1:8000/api/orders
Content-Type: application/json

{
  "products": [
    {
      "product_id": 1,
      "quantity": 2
    }
  ]
}
```

### HOW TO TEST

- Run test using artisan command: `php artisan test`


