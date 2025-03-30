<?php
/**
 * View de configurações do sistema
 * Paws&Patterns - Pet Boutique Ireland
 */

// Obtém mensagens de sucesso/erro, se houver
$success_message = $view_data['success_message'] ?? '';
$error_message = $view_data['error_message'] ?? '';

// Obtém as configurações atuais ou valores padrão
$settings = $view_data['settings'] ?? [
    'store_name' => 'Paws&Patterns',
    'store_email' => 'info@pawsandpatterns.ie',
    'store_phone' => '+353 1 123 4567',
    'store_address' => '123 Pet Lane, Dublin, Ireland',
    'currency' => 'EUR',
    'tax_rate' => 23, // Taxa de IVA padrão na Irlanda
    'enable_stock_notifications' => true,
    'low_stock_threshold' => 5,
    'enable_customer_reviews' => true,
    'order_prefix' => 'PP-'
];

// Define se o formulário está sendo editado ou visualizado
$is_edit_mode = $view_data['is_edit_mode'] ?? false;
?>

<div class="settings-container">
    <?php if (!empty($success_message)): ?>
    <div class="alert alert-success" role="alert">
        <i class="fas fa-check-circle me-2"></i> <?php echo htmlspecialchars($success_message); ?>
    </div>
    <?php endif; ?>

    <?php if (!empty($error_message)): ?>
    <div class="alert alert-danger" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i> <?php echo htmlspecialchars($error_message); ?>
    </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-3">
            <!-- Navegação de configurações -->
            <div class="settings-nav">
                <div class="list-group">
                    <a href="#general" class="list-group-item list-group-item-action active" data-bs-toggle="list">General Settings</a>
                    <a href="#store" class="list-group-item list-group-item-action" data-bs-toggle="list">Store Information</a>
                    <a href="#orders" class="list-group-item list-group-item-action" data-bs-toggle="list">Orders & Shipping</a>
                    <a href="#notifications" class="list-group-item list-group-item-action" data-bs-toggle="list">Notifications</a>
                    <a href="#advanced" class="list-group-item list-group-item-action" data-bs-toggle="list">Advanced Settings</a>
                </div>
            </div>
        </div>
        
        <div class="col-lg-9">
            <div class="settings-content">
                <form action="/index.php/admin_settings/save" method="post" class="settings-form">
                    <div class="tab-content">
                        <!-- General Settings -->
                        <div class="tab-pane fade show active" id="general">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">General Settings</h5>
                                    <?php if (!$is_edit_mode): ?>
                                    <a href="/index.php/admin_settings/edit" class="btn btn-sm btn-primary">Edit Settings</a>
                                    <?php endif; ?>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="store_name" class="form-label">Store Name</label>
                                        <input type="text" class="form-control" id="store_name" name="store_name" 
                                               value="<?php echo htmlspecialchars($settings['store_name']); ?>"
                                               <?php echo $is_edit_mode ? '' : 'disabled'; ?>>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="currency" class="form-label">Currency</label>
                                        <select class="form-select" id="currency" name="currency" <?php echo $is_edit_mode ? '' : 'disabled'; ?>>
                                            <option value="EUR" <?php echo ($settings['currency'] == 'EUR') ? 'selected' : ''; ?>>Euro (€)</option>
                                            <option value="USD" <?php echo ($settings['currency'] == 'USD') ? 'selected' : ''; ?>>US Dollar ($)</option>
                                            <option value="GBP" <?php echo ($settings['currency'] == 'GBP') ? 'selected' : ''; ?>>British Pound (£)</option>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="tax_rate" class="form-label">Tax Rate (%)</label>
                                        <input type="number" class="form-control" id="tax_rate" name="tax_rate" min="0" max="100" step="0.01"
                                               value="<?php echo htmlspecialchars($settings['tax_rate']); ?>"
                                               <?php echo $is_edit_mode ? '' : 'disabled'; ?>>
                                        <div class="form-text">Standard VAT rate in Ireland is 23%</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Store Information -->
                        <div class="tab-pane fade" id="store">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Store Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="store_email" class="form-label">Store Email</label>
                                        <input type="email" class="form-control" id="store_email" name="store_email"
                                               value="<?php echo htmlspecialchars($settings['store_email']); ?>"
                                               <?php echo $is_edit_mode ? '' : 'disabled'; ?>>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="store_phone" class="form-label">Store Phone</label>
                                        <input type="text" class="form-control" id="store_phone" name="store_phone"
                                               value="<?php echo htmlspecialchars($settings['store_phone']); ?>"
                                               <?php echo $is_edit_mode ? '' : 'disabled'; ?>>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="store_address" class="form-label">Store Address</label>
                                        <textarea class="form-control" id="store_address" name="store_address" rows="3"
                                                 <?php echo $is_edit_mode ? '' : 'disabled'; ?>><?php echo htmlspecialchars($settings['store_address']); ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Orders & Shipping -->
                        <div class="tab-pane fade" id="orders">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Orders & Shipping</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="order_prefix" class="form-label">Order Number Prefix</label>
                                        <input type="text" class="form-control" id="order_prefix" name="order_prefix"
                                               value="<?php echo htmlspecialchars($settings['order_prefix']); ?>"
                                               <?php echo $is_edit_mode ? '' : 'disabled'; ?>>
                                        <div class="form-text">This will be added before order numbers (e.g. PP-12345)</div>
                                    </div>
                                    
                                    <!-- Adicionar mais configurações de pedidos e envio aqui -->
                                </div>
                            </div>
                        </div>
                        
                        <!-- Notifications -->
                        <div class="tab-pane fade" id="notifications">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Notification Settings</h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="enable_stock_notifications" name="enable_stock_notifications"
                                               <?php echo ($settings['enable_stock_notifications']) ? 'checked' : ''; ?>
                                               <?php echo $is_edit_mode ? '' : 'disabled'; ?>>
                                        <label class="form-check-label" for="enable_stock_notifications">Enable low stock notifications</label>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="low_stock_threshold" class="form-label">Low Stock Threshold</label>
                                        <input type="number" class="form-control" id="low_stock_threshold" name="low_stock_threshold" min="0"
                                               value="<?php echo htmlspecialchars($settings['low_stock_threshold']); ?>"
                                               <?php echo $is_edit_mode ? '' : 'disabled'; ?>>
                                        <div class="form-text">Notify when product stock falls below this number</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Advanced Settings -->
                        <div class="tab-pane fade" id="advanced">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Advanced Settings</h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="enable_customer_reviews" name="enable_customer_reviews"
                                               <?php echo ($settings['enable_customer_reviews']) ? 'checked' : ''; ?>
                                               <?php echo $is_edit_mode ? '' : 'disabled'; ?>>
                                        <label class="form-check-label" for="enable_customer_reviews">Enable customer reviews</label>
                                    </div>
                                    
                                    <!-- Adicionar mais configurações avançadas aqui -->
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <?php if ($is_edit_mode): ?>
                    <div class="mt-4 text-end">
                        <a href="/index.php/admin_settings" class="btn btn-secondary me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">Save Settings</button>
                    </div>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ativa a tab correta baseada na URL ou hash
        const hash = window.location.hash || '#general';
        const tabLink = document.querySelector('.settings-nav a[href="' + hash + '"]');
        if (tabLink) {
            const tab = new bootstrap.Tab(tabLink);
            tab.show();
        }
        
        // Atualiza a URL quando uma tab é clicada
        document.querySelectorAll('.settings-nav a').forEach(link => {
            link.addEventListener('click', function() {
                window.location.hash = this.getAttribute('href');
            });
        });
    });
</script>