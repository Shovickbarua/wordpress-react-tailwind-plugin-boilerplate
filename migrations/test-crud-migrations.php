<?php

if (!class_exists('TestCrudMigration')) {
    class TestCrudMigration {
        public function __construct() {
            $this->run_migrations();
        }

        public function run_migrations() {
            $this->create_crud_table();
        }

        private function create_crud_table() {
            global $wpdb;

            $table_name = $wpdb->prefix . 'crud';

            $charset_collate = $wpdb->get_charset_collate();

            $sql = "CREATE TABLE $table_name  (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255),
                description TEXT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) $charset_collate;";

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }

    }
$test_crud = new TestCrudMigration();
}
?>