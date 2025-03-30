<?php
/**
 * View principal do dashboard administrativo
 * Paws&Patterns - Pet Boutique Ireland
 */

// Obtém os dados do controller, caso existam
$dashboard_stats = $view_data['dashboard_stats'] ?? [];
$recent_orders = $view_data['recent_orders'] ?? [];
$low_stock_products = $view_data['low_stock_products'] ?? [];

// Define valores padrão caso não existam dados
if (empty($dashboard_stats)) {
    $dashboard_stats = [
        'total_products' => 0,
        'total_orders' => 0,
        'pending_orders' => 0,
        'total_customers' => 0,
        'monthly_revenue' => 0,
        'out_of_stock' => 0
    ];
}
?>

<!-- Dashboard Overview -->
<div class="dashboard-overview">
    <!-- Stats Row -->
    <div class="row">
        <!-- Products Stat -->
        <div class="col-md-4 col-lg-4 mb-4">
            <div class="stats-card">
                <div class="stats-icon bg-primary">
                    <i class="fas fa-box"></i>
                </div>
                <h3 class="stats-value"><?php echo number_format($dashboard_stats['total_products']); ?></h3>
                <p class="stats-label">Total Products</p>
                <div class="stats-trend up">
                    <i class="fas fa-arrow-up"></i> <?php echo $dashboard_stats['out_of_stock']; ?> items out of stock
                </div>
            </div>
        </div>
        
        <!-- Orders Stat -->
        <div class="col-md-4 col-lg-4 mb-4">
            <div class="stats-card">
                <div class="stats-icon bg-success">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h3 class="stats-value"><?php echo number_format($dashboard_stats['total_orders']); ?></h3>
                <p class="stats-label">Total Orders</p>
                <div class="stats-trend up">
                    <i class="fas fa-arrow-up"></i> <?php echo $dashboard_stats['pending_orders']; ?> pending orders
                </div>
            </div>
        </div>
        
        <!-- Revenue Stat -->
        <div class="col-md-4 col-lg-4 mb-4">
            <div class="stats-card">
                <div class="stats-icon bg-warning">
                    <i class="fas fa-euro-sign"></i>
                </div>
                <h3 class="stats-value">€<?php echo number_format($dashboard_stats['monthly_revenue'], 2); ?></h3>
                <p class="stats-label">Monthly Revenue</p>
                <div class="stats-trend up">
                    <i class="fas fa-arrow-up"></i> 12% from last month
                </div>
            </div>
        </div>
    </div>
    
    <!-- Charts Row -->
    <div class="row">
        <!-- Sales Chart -->
        <div class="col-lg-8 mb-4">
            <div class="dashboard-card">
                <div class="card-header">
                    <h5 class="card-title">Sales Overview</h5>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="salesRangeDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            Last 6 Months
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="salesRangeDropdown">
                            <li><a class="dropdown-item" href="#">Last 30 Days</a></li>
                            <li><a class="dropdown-item" href="#">Last 6 Months</a></li>
                            <li><a class="dropdown-item" href="#">Last Year</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Orders Chart -->
        <div class="col-lg-4 mb-4">
            <div class="dashboard-card">
                <div class="card-header">
                    <h5 class="card-title">Order Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="ordersChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Latest Orders and Low Stock -->
    <div class="row">
        <!-- Latest Orders -->
        <div class="col-lg-8 mb-4">
            <div class="dashboard-card">
                <div class="card-header">
                    <h5 class="card-title">Recent Orders</h5>
                    <a href="/index.php/orders" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                // Se não houver pedidos recentes, exibe mensagem
                                if (empty($recent_orders)) : 
                                ?>
                                <tr>
                                    <td colspan="6" class="text-center">No recent orders</td>
                                </tr>
                                <?php 
                                // Caso contrário, lista os pedidos
                                else : 
                                    // Exemplo de dados de pedidos para demonstração
                                    $demo_orders = [
                                        [
                                            'id' => '10248',
                                            'customer' => 'John Smith',
                                            'date' => '2025-03-25 14:30',
                                            'amount' => 149.99,
                                            'status' => 'Pending'
                                        ],
                                        [
                                            'id' => '10247',
                                            'customer' => 'Emma Wilson',
                                            'date' => '2025-03-24 09:15',
                                            'amount' => 78.50,
                                            'status' => 'Processing'
                                        ],
                                        [
                                            'id' => '10246',
                                            'customer' => 'Michael Brown',
                                            'date' => '2025-03-23 16:45',
                                            'amount' => 215.75,
                                            'status' => 'Completed'
                                        ],
                                        [
                                            'id' => '10245',
                                            'customer' => 'Sarah Johnson',
                                            'date' => '2025-03-22 11:20',
                                            'amount' => 64.25,
                                            'status' => 'Completed'
                                        ]
                                    ];
                                    
                                    // Usa dados de demonstração até que dados reais estejam disponíveis
                                    $orders_to_display = empty($recent_orders) ? $demo_orders : $recent_orders;
                                    
                                    foreach ($orders_to_display as $order) :
                                        // Define a classe do status
                                        $status_class = 'status-pending';
                                        if ($order['status'] == 'Completed') {
                                            $status_class = 'status-active';
                                        } elseif ($order['status'] == 'Cancelled') {
                                            $status_class = 'status-inactive';
                                        }
                                ?>
                                <tr>
                                    <td>#<?php echo htmlspecialchars($order['id']); ?></td>
                                    <td><?php echo htmlspecialchars($order['customer']); ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($order['date'])); ?></td>
                                    <td>€<?php echo number_format($order['amount'], 2); ?></td>
                                    <td><span class="status-badge <?php echo $status_class; ?>"><?php echo htmlspecialchars($order['status']); ?></span></td>
                                    <td>
                                        <a href="/index.php/order_view/<?php echo $order['id']; ?>" class="table-action" title="View"><i class="fas fa-eye"></i></a>
                                        <a href="/index.php/order_edit/<?php echo $order['id']; ?>" class="table-action" title="Edit"><i class="fas fa-edit"></i></a>
                                    </td>
                                </tr>
                                <?php 
                                    endforeach;
                                endif; 
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Low Stock Products -->
        <div class="col-lg-4 mb-4">
            <div class="dashboard-card">
                <div class="card-header">
                    <h5 class="card-title">Low Stock Alert</h5>
                    <a href="/index.php/products" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body">
                    <?php 
                    // Se não houver produtos com estoque baixo, exibe mensagem
                    if (empty($low_stock_products)) : 
                        // Exemplo de dados para demonstração
                        $demo_products = [
                            [
                                'id' => '101',
                                'name' => 'Premium Dog Collar - Medium',
                                'stock' => '2',
                                'threshold' => '5'
                            ],
                            [
                                'id' => '205',
                                'name' => 'Cat Luxury Bed',
                                'stock' => '3',
                                'threshold' => '10'
                            ],
                            [
                                'id' => '312',
                                'name' => 'Winter Pet Sweater - Small',
                                'stock' => '4',
                                'threshold' => '8'
                            ]
                        ];
                        
                        // Usa dados de demonstração
                        $products_to_display = $demo_products;
                    else :
                        $products_to_display = $low_stock_products;
                    endif;
                    
                    foreach ($products_to_display as $product) :
                    ?>
                    <div class="low-stock-item">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="m-0"><?php echo htmlspecialchars($product['name']); ?></h6>
                            <a href="/index.php/product_edit/<?php echo $product['id']; ?>" class="table-action" title="Edit"><i class="fas fa-edit"></i></a>
                        </div>
                        <div class="progress mb-3" style="height: 6px;">
                            <?php 
                            // Calcula a porcentagem do estoque em relação ao limite
                            $percentage = ($product['stock'] / $product['threshold']) * 100;
                            $bar_class = 'bg-danger';
                            if ($percentage > 50) {
                                $bar_class = 'bg-warning';
                            }
                            ?>
                            <div class="progress-bar <?php echo $bar_class; ?>" role="progressbar" style="width: <?php echo $percentage; ?>%" aria-valuenow="<?php echo $product['stock']; ?>" aria-valuemin="0" aria-valuemax="<?php echo $product['threshold']; ?>"></div>
                        </div>
                        <div class="d-flex justify-content-between text-muted">
                            <small><?php echo $product['stock']; ?> in stock</small>
                            <small>Threshold: <?php echo $product['threshold']; ?></small>
                        </div>
                    </div>
                    <hr>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>