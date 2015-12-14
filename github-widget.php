<?php
/**
 * Plugin Name: Sample Widget

 * Adds Foo_Widget widget.
 */
class Githubapi_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'githubapi-widget', // Base ID
			__( 'Github API Widget', 'text_domain' ), // Name
			array( 'description' => __( 'Display Github Repositories', 'text_domain' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
    githubapi_repos( $instance );

	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
    $gh_username = ! empty( $instance['gh_username'] ) ? $instance['gh_username'] : __( 'Github Username', 'text_domain' );
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'gh_username' ); ?>"><?php _e( 'Username:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'gh_username' ); ?>" name="<?php echo $this->get_field_name( 'gh_username' ); ?>" type="text" value="<?php echo esc_attr( $gh_username ); ?>">
		</p>
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['gh_username'] = ( ! empty( $new_instance['gh_username'] ) ) ? strip_tags( $new_instance['gh_username'] ) : '';

		return $instance;
	}

} // class Foo_Widget

function githubapi_repos( $instance ) {
    $username = $instance['gh_username'];
    $url = 'https://api.github.com/users/' . $username . '/repos';
    $response = wp_remote_get( esc_url_raw( $url ) );

    /* Will result in $api_response being an array of data,
     * parsed from the JSON response of the API listed above */
    $api_response = json_decode( wp_remote_retrieve_body( $response ), true);
    echo "<h3>My Github Repositories</h3>";
    echo "<ul>";
    foreach ( $api_response as $repo ) {
        echo "<li><a target=_blank href=" . $repo["html_url"] . ">" . $repo["name"] . "</a></li>";

    }
    echo "</ul>";
}

// register Githubapi_Widget widget
function register_ghapi_widget() {
    register_widget( 'Githubapi_Widget' );
}
add_action( 'widgets_init', 'register_ghapi_widget' );
