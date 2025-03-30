<?php
/**
 * Funções utilitárias compartilhadas
 * Contém funções usadas por múltiplos componentes do plugin
 */

/**
 * Gera um slug a partir de um texto
 * @param string $text Texto para gerar o slug
 * @param string $table_name Nome da tabela para verificar unicidade (opcional)
 * @param int $existing_id ID de registro existente a ser ignorado na verificação (opcional)
 * @return string Slug gerado
 */
function generate_slug($text, $table_name = null, $existing_id = null) {
    // Converte para minúsculas
    $text = strtolower($text);
    
    // Remove caracteres especiais
    $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
    
    // Substitui espaços por hífens
    $text = preg_replace('/[\s-]+/', '-', $text);
    
    // Remove hífens duplicados
    $text = preg_replace('/-+/', '-', $text);
    
    // Remove hífens no início e no fim
    $text = trim($text, '-');
    
    // Se a tabela não foi especificada, retorna o slug sem verificar unicidade
    if (empty($table_name)) {
        return $text;
    }
    
    // Verificar se o slug já existe para a tabela especificada
    $where = ['slug' => $text, 'is_deleted' => false];
    
    // Se for atualização de registro existente, excluir o próprio registro da verificação
    if ($existing_id) {
        $where['id'] = ['operador' => '!=', 'valor' => $existing_id];
    }
    
    try {
        $existing = read($table_name, $where);
        
        // Se existir, adiciona um sufixo numérico
        if (!empty($existing)) {
            $count = count($existing);
            $text .= '-' . ($count + 1);
        }
    } catch (Exception $e) {
        error_log('Error checking slug uniqueness: ' . $e->getMessage());
    }
    
    return $text;
}