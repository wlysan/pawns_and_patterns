<?php
/**
 * Simple Page Cache System
 * Paws&Patterns - Pet Boutique Ireland
 */

class PageCache {
    private static $cache_dir = 'cache/';
    private static $cache_enabled = true;
    private static $cache_time = 3600; // 1 hora em segundos
    
    /**
     * Inicia o buffer de saída e tenta entregar conteúdo em cache
     */
    public static function start() {
        if (!self::$cache_enabled) {
            return false;
        }
        
        // Não fazer cache para administradores
        if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']) {
            return false;
        }
        
        // Não fazer cache para formulários POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return false;
        }
        
        $cache_file = self::getCacheFilename();
        
        // Verifica se o arquivo de cache existe e está atualizado
        if (file_exists($cache_file) && (time() - filemtime($cache_file) < self::$cache_time)) {
            echo file_get_contents($cache_file);
            exit; // Para a execução aqui - conteúdo em cache já enviado
        }
        
        // Inicia o buffer de saída para capturar conteúdo
        ob_start();
        return true;
    }
    
    /**
     * Finaliza o buffer e salva o conteúdo em cache
     */
    public static function end() {
        if (!self::$cache_enabled) {
            return false;
        }
        
        // Não fazer cache para administradores
        if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']) {
            return false;
        }
        
        // Não fazer cache para formulários POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return false;
        }
        
        $cache_file = self::getCacheFilename();
        
        // Assegura que o diretório de cache existe
        if (!is_dir(dirname($cache_file))) {
            mkdir(dirname($cache_file), 0755, true);
        }
        
        // Salva o conteúdo de saída no arquivo de cache
        $content = ob_get_contents();
        file_put_contents($cache_file, $content);
        
        return true;
    }
    
    /**
     * Gera um nome de arquivo de cache baseado na URL atual
     */
    private static function getCacheFilename() {
        $uri = $_SERVER['REQUEST_URI'];
        $cache_key = md5($uri);
        return self::$cache_dir . $cache_key . '.html';
    }
    
    /**
     * Limpa todos os arquivos de cache
     */
    public static function clearAll() {
        $files = glob(self::$cache_dir . '*.html');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }
    
    /**
     * Limpa o cache de uma URL específica
     */
    public static function clearUrl($url) {
        $cache_key = md5($url);
        $cache_file = self::$cache_dir . $cache_key . '.html';
        if (file_exists($cache_file)) {
            unlink($cache_file);
        }
    }
}