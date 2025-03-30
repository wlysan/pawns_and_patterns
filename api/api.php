<?php
require 'vendor/autoload.php'; // Carrega o Composer autoload
use Dotenv\Dotenv;

//header('Content-Type: application/json');

// Carregar variáveis do arquivo .env
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Função para conectar ao banco de dados usando .env
function getConnection() {
    $dsn = sprintf('mysql:host=%s;dbname=%s', $_ENV['DB_HOST'], $_ENV['DB_NAME']);
    $username = $_ENV['DB_USER'];
    $password = $_ENV['DB_PASS'];
    try {
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        http_response_code(500);
        die(json_encode(['error' => 'Erro na conexão: ' . $e->getMessage()]));
    }
}

// Autenticação via chave de API
function authenticate() {
    $apiKey = $_SERVER['HTTP_X_API_KEY'] ?? null;
    if (!$apiKey) {
        http_response_code(401);
        die(json_encode(['error' => 'Chave de API não fornecida']));
    }
    $pdo = getConnection();
    $stmt = $pdo->prepare("SELECT id FROM api_keys WHERE `key` = :key");
    $stmt->execute(['key' => $apiKey]);
    $userId = $stmt->fetchColumn();
    if (!$userId) {
        http_response_code(401);
        die(json_encode(['error' => 'Chave de API inválida']));
    }
    return $userId;
}

// Verificação de permissão
function hasPermission($userId, $table, $operation) {
    $pdo = getConnection();
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM permissions WHERE user_id = :user_id AND table_name = :table AND permission = :operation");
    $stmt->execute(['user_id' => $userId, 'table' => $table, 'operation' => $operation]);
    return $stmt->fetchColumn() > 0;
}

// Função auxiliar para construir cláusula WHERE
function buildWhereClause($where, &$params, $paramPrefix = '') {
    $conditions = [];
    $paramIndex = 0;
    foreach ($where as $key => $value) {
        if (is_array($value)) {
            if (isset($value['operador']) && isset($value['valor'])) {
                $paramName = ":$paramPrefix" . "param" . $paramIndex++;
                $conditions[] = "$key {$value['operador']} $paramName";
                $params[$paramName] = $value['valor'];
            } elseif (in_array(strtoupper($key), ['AND', 'OR'])) {
                $subConditions = [];
                foreach ($value as $subWhere) {
                    $subClause = buildWhereClause($subWhere, $params, $paramPrefix . "sub" . $paramIndex++);
                    if ($subClause) {
                        $subConditions[] = "($subClause)";
                    }
                }
                if ($subConditions) {
                    $conditions[] = implode(" $key ", $subConditions);
                }
            }
        } else {
            $paramName = ":$paramPrefix" . "param" . $paramIndex++;
            $conditions[] = "$key = $paramName";
            $params[$paramName] = $value;
        }
    }
    return implode(' AND ', $conditions);
}

// Função CREATE
function create($table, $data) {
    $pdo = getConnection();
    $columns = implode(', ', array_keys($data));
    $placeholders = ':' . implode(', :', array_keys($data));
    $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
    $stmt = $pdo->prepare($sql);
    foreach ($data as $key => $value) {
        $stmt->bindValue(':' . $key, $value);
    }
    $stmt->execute();
    return $pdo->lastInsertId();
}

// Função READ
function read($table, $where = [], $pagination = [], $order_by = [], $joins = []) {
    $pdo = getConnection();
    $sql = "SELECT * FROM $table";
    foreach ($joins as $join) {
        $joinType = isset($join['type']) ? strtoupper($join['type']) : 'INNER';
        $joinTable = $join['table'];
        $joinOn = $join['on'];
        $sql .= " $joinType JOIN $joinTable ON $joinOn";
    }
    $params = [];
    if (!empty($where)) {
        $whereClause = buildWhereClause($where, $params);
        if ($whereClause) {
            $sql .= " WHERE $whereClause";
        }
    }
    if (!empty($order_by)) {
        $orderParts = [];
        foreach ($order_by as $column => $direction) {
            $orderParts[] = "$column $direction";
        }
        $sql .= " ORDER BY " . implode(', ', $orderParts);
    }
    if (!empty($pagination) && isset($pagination['page']) && isset($pagination['per_page'])) {
        $page = (int)$pagination['page'];
        $perPage = (int)$pagination['per_page'];
        $offset = ($page - 1) * $perPage;
        $sql .= " LIMIT $perPage OFFSET $offset";
    }
    $stmt = $pdo->prepare($sql);
    foreach ($params as $param => $value) {
        $stmt->bindValue($param, $value);
    }
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Função UPDATE
function update($table, $data, $where) {
    $pdo = getConnection();
    $setParts = [];
    foreach ($data as $key => $value) {
        $setParts[] = "$key = :set_$key";
    }
    $setString = implode(', ', $setParts);
    $whereParts = [];
    foreach ($where as $key => $value) {
        $whereParts[] = "$key = :where_$key";
    }
    $whereString = implode(' AND ', $whereParts);
    $sql = "UPDATE $table SET $setString WHERE $whereString";
    $stmt = $pdo->prepare($sql);
    foreach ($data as $key => $value) {
        $stmt->bindValue(':set_' . $key, $value);
    }
    foreach ($where as $key => $value) {
        $stmt->bindValue(':where_' . $key, $value);
    }
    $stmt->execute();
    return $stmt->rowCount();
}

// Função DELETE
function delete($table, $where) {
    $pdo = getConnection();
    $sql = "DELETE FROM $table";
    if (!empty($where)) {
        $conditions = [];
        foreach ($where as $key => $value) {
            $conditions[] = "$key = :$key";
        }
        $sql .= " WHERE " . implode(' AND ', $conditions);
    }
    $stmt = $pdo->prepare($sql);
    foreach ($where as $key => $value) {
        $stmt->bindValue(':' . $key, $value);
    }
    $stmt->execute();
    return $stmt->rowCount();
}

// Função principal da API
function handleApiRequest() {
    $userId = authenticate();
    $method = $_SERVER['REQUEST_METHOD'];
    $request = explode('/', trim($_SERVER['PATH_INFO'] ?? '', '/'));
    $table = array_shift($request);
    $id = array_shift($request);

    $operationMap = [
        'GET' => 'read',
        'POST' => 'write',
        'PUT' => 'write',
        'DELETE' => 'delete'
    ];

    $operation = $operationMap[$method] ?? null;
    if (!$operation || !hasPermission($userId, $table, $operation)) {
        http_response_code(403);
        die(json_encode(['error' => 'Permissão negada para esta tabela']));
    }

    switch ($method) {
        case 'GET':
            $where = json_decode($_GET['where'] ?? '[]', true) ?: [];
            $pagination = ['page' => $_GET['page'] ?? 1, 'per_page' => $_GET['per_page'] ?? 10];
            $order_by = json_decode($_GET['order_by'] ?? '[]', true) ?: [];
            $joins = json_decode($_GET['joins'] ?? '[]', true) ?: [];
            $response = read($table, $where, $pagination, $order_by, $joins);
            break;

        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            if ($data) {
                $lastId = create($table, $data);
                $response = ['id' => $lastId, 'message' => 'Registro criado com sucesso'];
            } else {
                http_response_code(400);
                $response = ['error' => 'Dados inválidos'];
            }
            break;

        case 'PUT':
            if ($id) {
                $data = json_decode(file_get_contents('php://input'), true);
                if ($data) {
                    $where = ['id' => $id];
                    $affectedRows = update($table, $data, $where);
                    $response = ['affected_rows' => $affectedRows, 'message' => 'Registro atualizado com sucesso'];
                } else {
                    http_response_code(400);
                    $response = ['error' => 'Dados inválidos'];
                }
            } else {
                http_response_code(400);
                $response = ['error' => 'ID não especificado'];
            }
            break;

        case 'DELETE':
            if ($id) {
                $where = ['id' => $id];
                $affectedRows = delete($table, $where);
                $response = ['affected_rows' => $affectedRows, 'message' => 'Registro deletado com sucesso'];
            } else {
                http_response_code(400);
                $response = ['error' => 'ID não especificado'];
            }
            break;

        default:
            http_response_code(405);
            $response = ['error' => 'Método HTTP não suportado'];
    }

    echo json_encode($response);
}

// Só processa a API se a rota começar com '/api'
$path = $_SERVER['PATH_INFO'] ?? '/';
if (strpos($path, '/api') === 0) {
    handleApiRequest();
}