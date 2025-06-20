# KC report test

Hello developer! Welcome to your new awesome `Web` application built with the `Spiral framework`.

To set up the project, run the following commands:

```bash
composer install
```

Now you can configure the `Database`.
To do this, open the .env file and set up the database access credentials.

```dotenv
# Database
DB_CONNECTION=mysql
DB_DATABASE=cycle_test
DB_USERNAME=****
DB_PASSWORD=****
```

And run migrations:

```bash
php app.php cycle:migrate
```

After migrating the database tables
Apply the table alters mentioned in file

```
"\app\migrations\dbAlters.txt"
```

After the installation is complete, you can start the application using the following command:

```bash
rr serve
```

## Access Reports -

Separate endpoint created for each report so it can be used as: http://localhost:8080/{end-point}

1. **Monthly Sales by Region** (end point: http://localhost:8080/monthly-sales-by-region)

    - Group by: year, month, region_id
    - Show: total sales amount, number of orders
    - Must work for any 12-month period
    - Date range can be passed as GET params (i.e. - http://localhost:8080/monthly-sales-by-region?start=2024-06-01&end=2025-06-20)

2. **Top Categories by Store** (end point: http://localhost:8080/top-categories-by-store)
    - Group by: store_id, category_id
    - Show: total sales amount, rank within store
    - Filtered by: date range (any 3 months)
    - Date range can be passed as GET params (i.e. - http://localhost:8080/top-categories-by-store?start=2025-03-01&end=2025-06-20)

### Task Requirements

-   Write the initial `SQL` queries that generate these reports (seeds)
-   Explain why they might be slow
-   Modify the database schema to make them fast
-   Write the new, optimized queries
-   Create PHP code to populate the database.
-   Provide reports on the performance of new queries compared to the old ones.
-   Document your approach

/** Document related to task can be found in /public/Project-document.docx **/
