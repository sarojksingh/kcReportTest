<?php

namespace App\Endpoint\Web;

use Spiral\Prototype\Traits\PrototypeTrait;
use Spiral\Router\Annotation\Route;
use Spiral\Router\Annotation\Group;
use Cycle\Database\DatabaseInterface;
use Spiral\Http\ResponseWrapper;
use Cycle\ORM\ORMInterface;
use Cycle\ORM\Select;
use Cycle\Database\DatabaseProviderInterface;
use Psr\Http\Message\ResponseInterface;
use Cycle\Database\Injection\Fragment;
use Spiral\Views\ViewInterface;
use DateTime;

#[Group(route: '/')]
class ReportController
{

    use PrototypeTrait;

    public function __construct(
        private DatabaseInterface $db,
        //private DatabaseProviderInterface $dbal,
        private ResponseWrapper $response
        //private ViewInterface $views
    ) {}

    #[Route(route: '/monthly-sales-by-region', methods: 'GET')]
    public function monthlySales(): String
    {
        $start = $_GET['start'] ?? date('Y-m-d', strtotime('-12 months'));
        $end   = $_GET['end'] ?? date('Y-m-d');

        $startTime = microtime(true);


        $query = "SELECT 
                o.order_year,
                o.order_month,
                MONTHNAME(o.order_date) AS `orderMonth`,
                s.region_id,
                SUM(o.unit_price * o.quantity) AS total_sales,
                COUNT(*) AS number_of_orders
            FROM orders o
            LEFT JOIN stores s ON o.store_storeId = s.store_id
            WHERE o.order_date >= ? AND o.order_date <= ?
            GROUP BY o.order_year, o.order_month, s.region_id
            ORDER BY o.order_year, o.order_month, s.region_id";

        $result = $this->db->query($query, [$start, $end])->fetchAll();


        /*$result = $this->dbal->database('default')->table('orders')
            ->select([
                'order_year',
                'order_month',
                'region_id' => 'stores.region_id',
                'total_sales' => new Fragment('SUM(unit_price * quantity)'),
                'number_of_orders' => new Fragment('COUNT(*)'),
            ])
            ->join('INNER', 'stores', 'stores.store_id = orders.store_storeId')
            ->where('order_date', '>=', $start)
            ->andWhere('order_date', '<=', $end)
            ->groupBy('order_year', 'order_month', 'stores.region_id')
            ->orderBy('order_year')
            ->orderBy('order_month')
            ->orderBy('stores.region_id')
            ->run()
            ->fetchAll();*/

        $executionTimeMs = round((microtime(true) - $startTime) * 1000, 2); // in milliseconds
        $executionTime = $this->formatDuration($executionTimeMs);

        /*return $this->response->json([
            'Start Date' => $start,
            'End Date' => $end,
            'exec_time' => $executionTime,
            'data' => $result,
        ]);*/

        return $this->views->render('reports/monthly-sales', [
            'data' => $result,
            'execution_time_ms' => $executionTime,
            'start' => $start,
            'end' => $end
        ]);
    }

    #[Route(route: '/top-categories-by-store', methods: 'GET')]
    public function topCategories()
    {
        //$date = new Date();
        $start = $_GET['start'] ?? date('Y-m-d', strtotime('-3 months'));
        $end   = $_GET['end'] ?? date('Y-m-d');

        $error = null;
        $result = [];
        $startDate = new DateTime($start);
        $endDate = new DateTime($end);
        $interval = $startDate->diff($endDate);

        if ($interval->y > 0 || $interval->m > 3) {
            $error = 'Date range must not exceed 3 months.';
        } else {

            $startTime = microtime(true);


            $query = "SELECT 
                    t.store_storeId,
                    t.store_name,
                    t.category_id,
                    t.total_sales,
                    RANK() OVER (PARTITION BY t.store_storeId ORDER BY t.total_sales DESC) AS rank_within_store
                FROM (
                    SELECT 
                        o.store_storeId,
                        s.store_name,
                        p.category_id,
                        SUM(o.unit_price * o.quantity) AS total_sales
                    FROM orders o
                    LEFT JOIN products p ON o.product_productId = p.product_id
                    LEFT JOIN stores s ON s.store_id = o.store_storeId
                    WHERE o.order_date >= ? AND o.order_date <= ?
                    GROUP BY o.store_id, p.category_id
                ) t
            ";

            $result = $this->db->query($query, [$start, $end])->fetchAll();

            $executionTimeMs = round((microtime(true) - $startTime) * 1000, 2); // in milliseconds
            $executionTime = $this->formatDuration($executionTimeMs);

            /*return $this->response->json([
                'Start Date' => $start,
                'End Date' => $end,
                'exec_time' => $executionTime,
                'data' => $result,
            ]);*/
        }

        return $this->views->render('reports/top-category-store', [
            'data' => $result,
            'execution_time_ms' => $executionTime,
            'start' => $start,
            'end' => $end,
            'error' => $error,
        ]);
    }

    function formatDuration(float $ms): string
    {
        $totalSeconds = $ms / 1000;
        $minutes = floor($totalSeconds / 60);
        $seconds = $totalSeconds - ($minutes * 60);
        return sprintf('%02d:%05.2f', $minutes, $seconds); // e.g., "00:01.23"
    }
}
