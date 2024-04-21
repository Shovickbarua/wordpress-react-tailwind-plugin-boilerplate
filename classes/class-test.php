<?php
// Add all your rest api here
class TestPluginRestApi{
    public function __construct()
    {
        add_action('rest_api_init', [$this, 'test_api_endpoint']);
    }

    // Define your custom endpoint
    public function test_api_endpoint() {
        register_rest_route('test-plugin-crud/v1', '/add-data', array(
            'methods' => 'POST', // Change to POST method to handle data insertion
            'callback' => [$this, 'save_form_data'],
            // 'permission_callback' => function () {
            //     return current_user_can('edit_posts'); // Adjust permissions as needed
            // },
        ));

        register_rest_route('test-plugin-crud/v1', '/datas', array(
            'methods' => 'get', 
            'callback' => [$this, 'get_data'],
            // 'permission_callback' => function () {
            //     return current_user_can('edit_posts'); // Adjust permissions as needed
            // },
        ));
        
        register_rest_route('test-plugin-crud/v1', '/get-data/(?P<id>\d+)', array(
            'methods' => 'get', 
            'callback' => [$this, 'get_single_data'],
            // 'permission_callback' => function () {
            //     return current_user_can('edit_posts'); // Adjust permissions as needed
            // },
        ));
        
        register_rest_route('test-plugin-crud/v1', '/delete-data/(?P<id>\d+)', array(
            'methods' => 'get', 
            'callback' => [$this, 'delete_single_data'],
            // 'permission_callback' => function () {
            //     return current_user_can('edit_posts'); // Adjust permissions as needed
            // },
        ));
        
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

        // Query to fetch task details from custom table
        $query = $wpdb->prepare(
            "SELECT * FROM $table_name WHERE ID = %d",
            $id
        );

        // Fetch the task details using custom query
        $data = $wpdb->get_row( $query );

        // Check if the task exists
        if ( $data ) {
            // Return the task details
            return rest_ensure_response( $data );
        } else {
            // Task not found, return error response
            return new WP_Error( 'task_not_found', __( 'Task not found', 'text-domain' ), array( 'status' => 404 ) );
        }
    }
    public function delete_single_data($request) {
        global $wpdb;

        $table_name = $wpdb->prefix . 'crud';

        $id = $request['id'];

        // Check if ID is provided
        if (empty($id)) {
            return new WP_Error( 'missing_id', __( 'Data ID is missing', 'text-domain' ), array( 'status' => 400 ) );
        }

        // Delete the category from the database
        $result = $wpdb->delete(
            $table_name,
            array( 'ID' => $id ),
            array( '%d' )
        );

        // Check if deletion was successful
        if ($result === false) {
            // Deletion failed, return error response
            return new WP_Error( 'data_delete_failed', __( 'Failed to delete data', 'text-domain' ), array( 'status' => 500 ) );
        } elseif ($result === 0) {
            // No rows were affected, category with provided ID doesn't exist
            return new WP_Error( 'data_not_found', __( 'Data not found', 'text-domain' ), array( 'status' => 404 ) );
        } else {
            // Category successfully deleted
            return rest_ensure_response( array( 'message' => 'Data deleted successfully' ) );
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
                'message' => 'Name is required.',
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
                    'message' => 'Data updated successfully!',
                    'data' => $table,
                );
            } else {
                $response = array(
                    'message' => 'Update data failed',
                );
            }
        } else {
            $table = $wpdb->insert($table_name, $insert_data);
    
            // Prepare and return the response
            if ($table) {
                $response = array(
                    'message' => 'Data created successfully!',
                    'data' => $table,
                );
            } else {
                $response = array(
                    'message' => 'Create Data failed',
                );
            }
        }
    
        return new WP_REST_Response($response, 200);
    }
    

}

$test = new TestPluginRestApi();