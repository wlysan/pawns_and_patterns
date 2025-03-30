<?php
/**
 * API Core Functions
 * Central API implementation for database operations and authentication
 */

// Load environment variables if .env exists
function load_env_vars() {
    $env_file = __DIR__ . '/../.env';
    if (file_exists($env_file)) {
        $lines = file($env_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
                list($key, $value) = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);
                $_ENV[$key] = $value;
                $_SERVER[$key] = $value;
            }
        }
    }
}

// Load environment variables
load_env_vars();

/**
 * Get database connection using PDO
 * @return PDO Database connection object
 * @throws PDOException if connection fails
 */
function get_db_connection() {
    static $pdo = null;
    
    if ($pdo === null) {
        $host = $_ENV['DB_HOST'] ?? 'localhost';
        $dbname = $_ENV['DB_NAME'] ?? 'paws_patterns';
        $username = $_ENV['DB_USER'] ?? 'root';
        $password = $_ENV['DB_PASS'] ?? '';
        
        $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";
        
        try {
            $pdo = new PDO($dsn, $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch (PDOException $e) {
            error_log('Database connection error: ' . $e->getMessage());
            throw $e;
        }
    }
    
    return $pdo;
}

/**
 * Authenticate API request using API key
 * @return int|false User ID if authenticated, false otherwise
 */
function authenticate_api_request() {
    $api_key = $_SERVER['HTTP_X_API_KEY'] ?? null;
    
    if (!$api_key) {
        return false;
    }
    
    try {
        $pdo = get_db_connection();
        $stmt = $pdo->prepare("SELECT id FROM api_keys WHERE `key` = :key AND is_deleted = 0");
        $stmt->execute(['key' => $api_key]);
        return $stmt->fetchColumn();
    } catch (PDOException $e) {
        error_log('API authentication error: ' . $e->getMessage());
        return false;
    }
}

/**
 * Check if user has permission for operation on table
 * @param int $user_id User ID
 * @param string $table Table name
 * @param string $operation Operation type (read, write, delete)
 * @return bool True if permitted, false otherwise
 */
function check_permission($user_id, $table, $operation) {
    try {
        $pdo = get_db_connection();
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM permissions 
                               WHERE user_id = :user_id 
                               AND table_name = :table 
                               AND permission = :operation
                               AND is_deleted = 0");
        $stmt->execute([
            'user_id' => $user_id, 
            'table' => $table, 
            'operation' => $operation
        ]);
        return $stmt->fetchColumn() > 0;
    } catch (PDOException $e) {
        error_log('Permission check error: ' . $e->getMessage());
        return false;
    }
}

/**
 * Build WHERE clause for SQL queries
 * @param array $where Conditions for WHERE clause
 * @param array &$params Parameters for prepared statement
 * @param string $paramPrefix Prefix for parameter names
 * @return string WHERE clause
 */
function build_where_clause($where, &$params, $paramPrefix = '') {
    $conditions = [];
    $paramIndex = 0;
    
    foreach ($where as $key => $value) {
        if (is_array($value)) {
            if (isset($value['operador']) && isset($value['valor'])) {
                $paramName = ":{$paramPrefix}param{$paramIndex}";
                $paramIndex++;
                $conditions[] = "{$key} {$value['operador']} {$paramName}";
                $params[$paramName] = $value['valor'];
            } elseif (in_array(strtoupper($key), ['AND', 'OR'])) {
                $subConditions = [];
                foreach ($value as $subWhere) {
                    $subPrefix = "{$paramPrefix}sub{$paramIndex}";
                    $paramIndex++;
                    $subClause = build_where_clause($subWhere, $params, $subPrefix);
                    if ($subClause) {
                        $subConditions[] = "({$subClause})";
                    }
                }
                if ($subConditions) {
                    $conditions[] = implode(" {$key} ", $subConditions);
                }
            }
        } else {
            $paramName = ":{$paramPrefix}param{$paramIndex}";
            $paramIndex++;
            $conditions[] = "{$key} = {$paramName}";
            $params[$paramName] = $value;
        }
    }
    
    return implode(' AND ', $conditions);
}

/**
 * Create a new record in the database
 * @param string $table Table name
 * @param array $data Data to insert
 * @return int|bool Last insert ID or false on failure
 */
function create($table, $data) {
    try {
        $pdo = get_db_connection();
        
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        $stmt = $pdo->prepare($sql);
        
        foreach ($data as $key => $value) {
            $stmt->bindValue(":{$key}", $value);
        }
        
        $stmt->execute();
        return $pdo->lastInsertId();
    } catch (PDOException $e) {
        error_log("Error creating record in {$table}: " . $e->getMessage());
        return false;
    }
}

/**
 * Read records from the database
 * @param string $table Table name
 * @param array $where WHERE conditions
 * @param array $pagination Pagination options
 * @param array $order_by Order by options
 * @param array $joins Join tables
 * @return array|bool Records or false on failure
 */
function read($table, $where = [], $pagination = [], $order_by = [], $joins = []) {
    try {
        $pdo = get_db_connection();
        
        $sql = "SELECT * FROM {$table}";
        
        // Add joins if any
        foreach ($joins as $join) {
            $joinType = isset($join['type']) ? strtoupper($join['type']) : 'INNER';
            $joinTable = $join['table'];
            $joinOn = $join['on'];
            $sql .= " {$joinType} JOIN {$joinTable} ON {$joinOn}";
        }
        
        // Add WHERE clause if conditions exist
        $params = [];
        if (!empty($where)) {
            $whereClause = build_where_clause($where, $params);
            if ($whereClause) {
                $sql .= " WHERE {$whereClause}";
            }
        }
        
        // Add ORDER BY if specified
        if (!empty($order_by)) {
            $orderParts = [];
            foreach ($order_by as $column => $direction) {
                $orderParts[] = "{$column} {$direction}";
            }
            $sql .= " ORDER BY " . implode(', ', $orderParts);
        }
        
        // Add pagination if specified
        if (!empty($pagination) && isset($pagination['page']) && isset($pagination['per_page'])) {
            $page = (int)$pagination['page'];
            $perPage = (int)$pagination['per_page'];
            $offset = ($page - 1) * $perPage;
            $sql .= " LIMIT {$perPage} OFFSET {$offset}";
        }
        
        $stmt = $pdo->prepare($sql);
        
        foreach ($params as $param => $value) {
            $stmt->bindValue($param, $value);
        }
        
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Error reading from {$table}: " . $e->getMessage());
        return false;
    }
}

/**
 * Update records in the database
 * @param string $table Table name
 * @param array $data Data to update
 * @param array $where WHERE conditions
 * @return int|bool Number of affected rows or false on failure
 */
function update($table, $data, $where) {
    try {
        $pdo = get_db_connection();
        
        $setParts = [];
        foreach ($data as $key => $value) {
            $setParts[] = "{$key} = :set_{$key}";
        }
        $setString = implode(', ', $setParts);
        
        $whereParts = [];
        foreach ($where as $key => $value) {
            $whereParts[] = "{$key} = :where_{$key}";
        }
        $whereString = implode(' AND ', $whereParts);
        
        $sql = "UPDATE {$table} SET {$setString} WHERE {$whereString}";
        $stmt = $pdo->prepare($sql);
        
        foreach ($data as $key => $value) {
            $stmt->bindValue(":set_{$key}", $value);
        }
        
        foreach ($where as $key => $value) {
            $stmt->bindValue(":where_{$key}", $value);
        }
        
        $stmt->execute();
        return $stmt->rowCount();
    } catch (PDOException $e) {
        error_log("Error updating {$table}: " . $e->getMessage());
        return false;
    }
}

/**
 * Delete records from the database (hard delete)
 * @param string $table Table name
 * @param array $where WHERE conditions
 * @return int|bool Number of affected rows or false on failure
 */
function delete($table, $where) {
    try {
        $pdo = get_db_connection();
        
        $whereParts = [];
        foreach ($where as $key => $value) {
            $whereParts[] = "{$key} = :{$key}";
        }
        $whereString = implode(' AND ', $whereParts);
        
        $sql = "DELETE FROM {$table} WHERE {$whereString}";
        $stmt = $pdo->prepare($sql);
        
        foreach ($where as $key => $value) {
            $stmt->bindValue(":{$key}", $value);
        }
        
        $stmt->execute();
        return $stmt->rowCount();
    } catch (PDOException $e) {
        error_log("Error deleting from {$table}: " . $e->getMessage());
        return false;
    }
}

/**
 * Soft delete records from the database
 * @param string $table Table name
 * @param array $where WHERE conditions
 * @return int|bool Number of affected rows or false on failure
 */
function soft_delete($table, $where) {
    try {
        $data = [
            'is_deleted' => 1,
            'deleted_at' => date('Y-m-d H:i:s'),
            'status' => 'Inativo' // Default inactive status
        ];
        
        return update($table, $data, $where);
    } catch (Exception $e) {
        error_log("Error soft deleting from {$table}: " . $e->getMessage());
        return false;
    }
}

/**
 * Generate a unique slug from text
 * @param string $text Text to convert to slug
 * @param string $table Table to check uniqueness against
 * @param int $existing_id ID to exclude from uniqueness check
 * @return string Unique slug
 */
function generate_slug($text, $table = null, $existing_id = null) {
    // Convert to lowercase
    $text = strtolower($text);
    
    // Remove special characters
    $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
    
    // Replace spaces with hyphens
    $text = preg_replace('/[\s-]+/', '-', $text);
    
    // Remove duplicate hyphens
    $text = preg_replace('/-+/', '-', $text);
    
    // Remove leading/trailing hyphens
    $text = trim($text, '-');
    
    // If no table specified, return the slug without checking uniqueness
    if (empty($table)) {
        return $text;
    }
    
    // Check if slug already exists in the table
    $where = ['slug' => $text, 'is_deleted' => 0];
    
    // If updating an existing record, exclude it from the check
    if ($existing_id) {
        $where['id'] = ['operador' => '!=', 'valor' => $existing_id];
    }
    
    try {
        $existing = read($table, $where);
        
        // If slug exists, add numeric suffix
        if (!empty($existing)) {
            $count = count($existing);
            $text .= '-' . ($count + 1);
        }
    } catch (Exception $e) {
        error_log('Error checking slug uniqueness: ' . $e->getMessage());
    }
    
    return $text;
}

/**
 * Process API requests when accessed through /api/ routes
 */
function process_api_request() {
    // Only process if the request starts with /api/
    $path = $_SERVER['PATH_INFO'] ?? '';
    if (strpos($path, '/api/') !== 0) {
        return;
    }
    
    header('Content-Type: application/json');
    
    // Authenticate request
    $user_id = authenticate_api_request();
    if (!$user_id) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized: Invalid or missing API key']);
        exit;
    }
    
    // Get request details
    $method = $_SERVER['REQUEST_METHOD'];
    $request = explode('/', trim($path, '/'));
    array_shift($request); // Remove 'api'
    $table = array_shift($request) ?? '';
    $id = array_shift($request) ?? null;
    
    // Map HTTP methods to permission types
    $operationMap = [
        'GET' => 'read',
        'POST' => 'write',
        'PUT' => 'write',
        'DELETE' => 'delete'
    ];
    
    $operation = $operationMap[$method] ?? null;
    
    // Check permission
    if (!$operation || !check_permission($user_id, $table, $operation)) {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden: Insufficient permissions']);
        exit;
    }
    
    // Process request based on method
    try {
        switch ($method) {
            case 'GET':
                $where = json_decode($_GET['where'] ?? '[]', true) ?: [];
                $pagination = [
                    'page' => $_GET['page'] ?? 1,
                    'per_page' => $_GET['per_page'] ?? 10
                ];
                $order_by = json_decode($_GET['order_by'] ?? '[]', true) ?: [];
                $joins = json_decode($_GET['joins'] ?? '[]', true) ?: [];
                
                $response = read($table, $where, $pagination, $order_by, $joins);
                break;
                
            case 'POST':
                $data = json_decode(file_get_contents('php://input'), true);
                if ($data) {
                    $id = create($table, $data);
                    $response = ['id' => $id, 'message' => 'Record created successfully'];
                } else {
                    http_response_code(400);
                    $response = ['error' => 'Invalid data format'];
                }
                break;
                
            case 'PUT':
                if ($id) {
                    $data = json_decode(file_get_contents('php://input'), true);
                    if ($data) {
                        $where = ['id' => $id];
                        $rows = update($table, $data, $where);
                        $response = ['affected_rows' => $rows, 'message' => 'Record updated successfully'];
                    } else {
                        http_response_code(400);
                        $response = ['error' => 'Invalid data format'];
                    }
                } else {
                    http_response_code(400);
                    $response = ['error' => 'ID not specified'];
                }
                break;
                
            case 'DELETE':
                if ($id) {
                    $where = ['id' => $id];
                    // Use soft delete by default
                    $rows = soft_delete($table, $where);
                    $response = ['affected_rows' => $rows, 'message' => 'Record deleted successfully'];
                } else {
                    http_response_code(400);
                    $response = ['error' => 'ID not specified'];
                }
                break;
                
            default:
                http_response_code(405);
                $response = ['error' => 'Method not allowed'];
        }
    } catch (Exception $e) {
        http_response_code(500);
        $response = ['error' => 'Server error: ' . $e->getMessage()];
    }
    
    echo json_encode($response);
    exit;
}

// Process API request if applicable
process_api_request();