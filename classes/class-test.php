<?php
// Add all your rest api here
class TestCrudRestApi{
    public function __construct()
    {
        add_action('rest_api_init', [$this, 'test_api_endpoint']);
    }

    // Define your custom endpoint
    public function test_api_endpoint() {
        register_rest_route('test-plugin-crud/v1', '/add-data', array(
            'methods' => 'POST', // Change to POST method to handle data insertion
            'callback' => [$this, 'save_form_data'],
            'permission_callback' => [$this, 'verify_test_nonce'],
        ));

        register_rest_route('test-plugin-crud/v1', '/datas', array(
            'methods' => 'get', 
            'callback' => [$this, 'get_data'],
            'permission_callback' => function () {
                return true;
            },
        ));
        
        register_rest_route('test-plugin-crud/v1', '/get-data/(?P<id>\d+)', array(
            'methods' => 'get', 
            'callback' => [$this, 'get_single_data'],
            'permission_callback' => function () {
                return true;
            },
        ));
        
        register_rest_route('test-plugin-crud/v1', '/delete-data/(?P<id>\d+)', array(
            'methods' => 'get', 
            'callback' => [$this, 'delete_single_data'],
            'permission_callback' => [$this, 'verify_test_nonce'],
        ));
        
    }

    function verify_test_nonce($request) {
        $nonce = $request->get_header('X-WP-Nonce');
        if (!wp_verify_nonce($nonce, 'wp_rest')) {
            return new WP_Error('rest_forbidden', esc_html__('Invalid nonce.', 'test-crud'), array('status' => 403));
        }
        return true;
    }


    public function get_data() {
        global $wpdb;

        $table_name = $wpdb->prefix . 'crud';

        $query = $wpdb->prepare(
            "SELECT * FROM $table_name ",
        );

        $results = $wpdb->get_results($query);

        return rest_ensure_response($results);
    }    
    
    public function get_single_data($request) {
        global $wpdb;

        $table_name = $wpdb->prefix . 'crud';

        $id = $request['id'];

        // Query to fetch data details from custom table
        $query = $wpdb->prepare(
            "SELECT * FROM $table_name WHERE ID = %d",
            $id
        );
        // Fetch the data details using custom query
        $data = $wpdb->get_row( $query );

        // Check if the data exists
        if ( $data ) {
            // Return the data details
            return rest_ensure_response( $data );
        } else {
            // data not found, return error response
            return new WP_Error( 'data_not_found', esc_html__( 'Data not found', 'test-crud' ), array( 'status' => 404 ) );
        }
    }
    public function delete_single_data($request) {
        global $wpdb;

        $table_name = $wpdb->prefix . 'crud';

        $id = $request['id'];

        // Check if ID is provided
        if (empty($id)) {
            return new WP_Error( 'missing_id', esc_html__( 'Data ID is missing', 'test-crud' ), array( 'status' => 400 ) );
        }

        // Delete the data from the database
        $result = $wpdb->delete(
            $table_name,
            array( 'ID' => $id ),
            array( '%d' )
        );

        // Check if deletion was successful
        if ($result === false) {
            // Deletion failed, return error response
            return new WP_Error( 'data_delete_failed', esc_html__( 'Failed to delete data', 'test-crud' ), array( 'status' => 500 ) );
        } elseif ($result === 0) {
            // No rows were affected, data with provided ID doesn't exist
            return new WP_Error( 'data_not_found', esc_html__( 'Data not found', 'test-crud' ), array( 'status' => 404 ) );
        } else {
            // data successfully deleted
            return rest_ensure_response( array( 'message' => esc_html__('Data deleted successfully', 'test-crud') ) );
        };
    }


    public function save_form_data($request) {
        // Save data to the database
        global $wpdb;
        $table_name = $wpdb->prefix . 'crud';
    
        // Sanitize and validate input
        $name = sanitize_text_field($request['name']);
        $description = sanitize_text_field($request['description']);
    
        // Perform validation
        if (empty($name)) {
            $response = array(
                'message' => esc_html__('Name is required.', 'test-crud'),
            );
            return new WP_REST_Response($response, 400);
        }
    
        // Prepare data for insertion or update
        $insert_data = array(
            'name' => $name,
            'description' => $description,
        );
    
        if (!empty($request['id'])) {
            $where = array('id' => $request['id']); // Specify condition for updating
            $table = $wpdb->update($table_name, $insert_data, $where);
    
            // Prepare and return the response
            if ($table) {
                $response = array(
                    'message' => esc_html__('Data updated successfully!', 'test-crud'),
                    'data' => $table,
                );
            } else {
                $response = array(
                    'message' => esc_html__('Update data failed', 'test-crud'),
                );
            }
        } else {
            $table = $wpdb->insert($table_name, $insert_data);
    
            // Prepare and return the response
            if ($table) {
                $response = array(
                    'message' => esc_html__('Data updated successfully!', 'test-crud'),
                    'data' => $table,
                );
            } else {
                $response = array(
                    'message' => esc_html__('Create data failed', 'test-crud'),
                );
            }
        }
    
        return new WP_REST_Response($response, 200);
    }
    

}

$test = new TestCrudRestApi();