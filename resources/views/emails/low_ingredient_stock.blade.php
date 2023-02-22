<h1>Low Stock Update</h1>

<p>
    {{ $ingredient->name }} is low on stock. Current quantity is {{ number_format($ingredient->quantity) }}g, meanwhile the configured threshold is {{ number_format($ingredient->threshold_quantity) }}g
</p>
