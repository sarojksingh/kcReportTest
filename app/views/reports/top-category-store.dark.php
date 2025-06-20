<extends:layout.base title="[[Top Categories by Store]]" />

<stack:push name="styles">
    <link rel="stylesheet" href="/styles/welcome.css" />
</stack:push>

<define:body>
    <h1 class="main-title">Top Categories by Store</h1>

    <div class="box">
        <div class="items">

            <p><strong>Report Generated In:</strong> <?= $execution_time_ms ?> ms</p>
            <p><strong>Date Range:</strong> <?= date('m/d/Y', strtotime($start))  ?> - <?= date('m/d/Y', strtotime($end))  ?> </p>

            <!--<form method="GET" action="/export-monthly-sales">
                <input type="hidden" name="start" value="<?= $start ?>">
                <input type="hidden" name="end" value="<?= $end ?>">
                <button type="submit">Export to Excel</button>
            </form>-->

            <div style="width: 100%">
                <form method="GET" action="/top-categories-by-store">
                    <label>Start Date:</label>
                    <input type="date" name="start" value="<?= htmlspecialchars($start) ?>">
                    <label>End Date:</label>
                    <input type="date" name="end" value="<?= htmlspecialchars($end) ?>">
                    <button type="submit">Filter</button>
                </form>
            </div>

            <?php if (!empty($error)): ?>
                <p style="color: red; margin: 2px 0px;"><strong>Error:</strong> <?= $error ?></p>
            <?php endif; ?>

            <table border="1" cellpadding="8" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Store Name</th>
                        <th>Category ID</th>
                        <th>Total Sales</th>
                        <th>Store Rank</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $row): ?>
                        <tr>
                            <td><?= $row['store_name'] ?></td>
                            <td><?= $row['category_id'] ?></td>
                            <td><?= $row['total_sales'] ?></td>
                            <td><?= $row['rank_within_store'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </div>

    </div>

</define:body>