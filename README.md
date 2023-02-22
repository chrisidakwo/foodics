## Foodics App


### HOW TO USE

- Clone the project and install composer dependencies
- Create .env file from .env.example using `cp .env.example .env`
- Migrate database tables and seed products and ingredients data using `php artisan migrate --seed`
- Now, you can create a new order using the endpoint: `/api/orders`. Something like:

```http request
Content-Type: application/json
Accept: application/json

{
  "products": [
    {
      "product_id": 1,
      "quantity": 2
    }
  ]
}
```

**NB:** Only two products are seeded (with product ids as 1 and 2). Hence, you can only use those ids to place an order

### HOW TO TEST

- Run test using artisan command: `php artisan test`


