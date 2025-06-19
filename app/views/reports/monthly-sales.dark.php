<extends:layout.base title="[[The PHP Framework for future Innovators]]" />

<stack:push name="styles">
    <link rel="stylesheet" href="/styles/welcome.css" />
</stack:push>

<define:body>
    <h1 class="main-title">Monthly Sales Report</h1>

    <div class="box">
        <div class="items">


            <p><strong>Report Generated In:</strong> <?= $execution_time_ms ?> ms</p>
            <p><strong>Date Range:</strong> <?= date('m/d/Y', strtotime($start))  ?> - <?= date('m/d/Y', strtotime($end))  ?> </p>

            <!--<form method="GET" action="/export-monthly-sales">
                <input type="hidden" name="start" value="<?= $start ?>">
                <input type="hidden" name="end" value="<?= $end ?>">
                <button type="submit">Export to Excel</button>
            </form>-->

            <table border="1" cellpadding="8" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Year</th>
                        <th>Month</th>
                        <th>Region ID</th>
                        <th>Total Sales</th>
                        <th>Number of Orders</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $row): ?>
                        <tr>
                            <td><?= $row['order_year'] ?></td>
                            <td><?= $row['orderMonth'] ?></td>
                            <td><?= $row['region_id'] ?></td>
                            <td><?= $row['total_sales'] ?></td>
                            <td><?= $row['number_of_orders'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </div>


    </div>

</define:body>