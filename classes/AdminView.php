<?php
require_once(dirname(__FILE__) . '/Adminclass.php');
if ( !class_exists( 'Rwrpt_Admin_Element' ) ) {
class Rwrpt_Admin_Element extends Rwrpt\Admin\RwrptAdminClass {    
   
    public function shortcodePageCallback() {
	    	$posts = get_posts( 
	    		array(
					'post_type' => 'rwrpt_shortcode',
					'posts_per_page' => -1,
					'post_status'    => 'publish',
				) 
	    	);

	    	if(isset($_GET['rwrpt_short_del'])){
		    	$del=self::rwrpt_delete($_GET['rwrpt_short_del']);
		    	wp_safe_redirect( esc_url(admin_url( 'admin.php?page=rwrpt-all-shortcode')));
				exit();
			}
        ?><br>
        <h3>All Shortcode</h3>
        <table class="wp-list-table widefat fixed striped table-view-list pages" cellspacing="0">
        	<thead>        		
        		<th>ID</th>
        		<th>Title</th>
        		<th>Shortcode</th>
        		<th>Created at</th>
        		<th>Action</th>        	
        	</thead>
        	<body>
        		<?php
        		foreach($posts as $key=>$shortcode){
        			$post_meta = get_post_meta($shortcode->ID);
        		 ?>        		
        		<tr>
        			<td><?php echo esc_html($shortcode->ID); ?></td>
        			<td><?php echo esc_html($shortcode->post_title); ?></td>
        			<td> <span style="background: #ccc;">[rwrpt_recent_post title="<?php echo esc_html($shortcode->post_title); ?>" posted_date="<?php echo esc_html($post_meta['date_posted'][0]); ?>" number_of_post=<?php echo esc_html($post_meta['number_of_post'][0]); ?> post_type="<?php echo esc_html($post_meta['rwrptpost_type_'][0]); ?>" view_style="<?php echo esc_html($post_meta['poststyle'][0]); ?>"]</span>
				</td>
				<td><?php echo esc_html($shortcode->post_date); ?></td>
        		<td><a href="?page=rwrpt-all-shortcode&rwrpt_short_del=<?php echo $shortcode->ID; ?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><span class="dashicons dashicons-table-row-delete"></span><a></td>
        		</tr>
        	<?php } ?>
        	</body>        	
        </table>
        <?php
    }

    public function addShortcodePageCallback() {

    $args = array(
       'public'   => true,
       '_builtin' => false,
    );

    $output = 'names'; 
    $operator = 'and'; 
    $rwrptpost_types = get_post_types( $args, $output, $operator ); 

    if(isset($_POST['submit'])){
	    $args = array(
		    'post_type' => 'rwrpt_shortcode',
		    'post_title' => isset($_POST['shortcodetitle'])?$_POST['shortcodetitle']:'',
		    'post_content' => '',
		    'post_status'=>'publish'
		);
		$post_id=wp_insert_post( $args );

		if($post_id){
			update_post_meta( $post_id, 'date_posted', sanitize_text_field( $_POST['date_posted'] ) );
			update_post_meta( $post_id, 'rwrptpost_type_', sanitize_text_field( $_POST['rwrptpost_type_'] ) );
			update_post_meta( $post_id, 'number_of_post', sanitize_text_field( $_POST['number_of_post'] ) );
			update_post_meta( $post_id, 'poststyle', sanitize_text_field( $_POST['poststyle'] ) );
			wp_admin_notice( 'Shortcode Added', [ 'type' => 'success' ] );
		}else{
			wp_admin_notice( 'There was an error!', [ 'type' => 'error' ] );
		}

    }     

    ?>      
        <style>
        	.postbox input, select {
			    width: 300px;
			}
        </style>
        <br>
        <h3>Create Shortcode</h3>
        	<div class="postbox" style="padding:10px;">
		        <form method="post" action="">
		            <?php wp_nonce_field('rwrpt-add-shortcode') ?>
		            <div>
		            	<label>Title:</label><br>
		                <input type="text" name="shortcodetitle" value="" />
		            </div>

		            <div>
		            	<label>Date:</label><br>
		                <select name="date_posted">
		                	<option value="true">true</option>
		                	<option value="false">false</option>
		                </select>
		            </div>

		            <div>
		            	<label>Number of post:</label><br>
		                <input type="number" name="number_of_post" value="" />
		            </div>

		            <div>
		            	<label>Post Type:</label><br>
		                <select name="rwrptpost_type_">
		                	<option value="post">post</option>
		                	<?php 
		                	foreach($rwrptpost_types as $k=>$value){
		                	?>
		                		<option value="<?php echo $value; ?>"><?php echo $value; ?></option>
		                	<?php } ?>
		                </select>
		            </div>

					<div>
		            	<label>Style:</label><br>
		                <select name="poststyle" class="inside">
		                	<option value="grid">grid</option>
		                	<option value="list">list</option>
		                </select>
		            </div>
		            <div >
		            	<input class="button-primary" type="submit" name="submit" value="Add" />
		        	</div>		           
		        </form>
		    </div>   
        <?php
    }

    public function rwrpt_delete($post_id){
    	return wp_delete_post( $post_id, true); // Set to False if you want to send them to Trash.
    }
    
}
new Rwrpt_Admin_Element();
}
?>