<?php


/* start post type */
if ( ! class_exists( 'myticket_identities_Post_Type' ) ) :

class myticket_identities_Post_Type {

    private $theme = 'myticket';
	public function __construct() {
        // Run when the plugin is activated
		register_activation_hook( __FILE__, array( $this, 'plugin_activation' ) );

		// Add the identities post type and taxonomies
		add_action( 'init', array( $this, 'identities_init' ) );

		// Thumbnail support for identities posts
		add_theme_support( 'post-thumbnails', array( 'identities' ) );

		// Add thumbnails to column view
		add_filter( 'manage_edit-identities_columns', array( $this, 'add_thumbnail_column_identities'), 10, 1 );
		add_action( 'manage_pages_custom_column', array( $this, 'display_thumbnail_identities' ), 10, 1 );

		// Allow filtering of posts by taxonomy in the admin view
		add_action( 'restrict_manage_posts', array( $this, 'add_taxonomy_filters' ) );

		// Show identities post counts in the dashboard
		add_action( 'right_now_content_table_end', array( $this, 'add_identities_counts' ) );
		
        // Add custom metaboxes
        add_action( 'cmb2_init', array( $this, 'add_identities_metaboxes' ) );
	}

    /**
  	 * Create myticket block specific meta box key values
  	 */
  	public function add_identities_metaboxes() {

        /**
         * Initiate the metabox
         */
        $cmb = new_cmb2_box( array(
                       'id'            => 'identities_metabox',
                       'title'         => esc_html( 'Fill in one identities tab', $this->theme ),
                       'object_types'  => array( 'identities', ), // Post type
                       'context'       => 'normal',
                       'priority'      => 'high',
                       'show_names'    => true, // Show field names on the left
                       ) );

        $cmb->add_field( array(
                       'name' => esc_html( 'Title', $this->theme ),
                       'desc' => esc_html( 'Set up title. Ex.: Selena Gomez or Golf Club', $this->theme ),
                       'id'   => $this->theme . '_title',
                       'type' => 'text',
                       'default' => ''
                       ) );

        $cmb->add_field( array(
                       'name' => esc_html( 'Description', $this->theme ),
                       'desc' => esc_html( 'Provide short identity description', $this->theme ),
                       'id'   => $this->theme . '_desc',
                       'type' => 'textarea',
                       'default' => ''
                       ) );

        $cmb->add_field( array(
		               'name' => esc_html( 'CTA text', $this->theme ),
		               'desc' => esc_html( 'Override default Follow button text', $this->theme ),
		               'id'   => $this->theme . '_cta_text',
		               'type' => 'text',
		               'default' => 'Follow'
		               ) );

        $cmb->add_field( array(
		               'name' => esc_html( 'CTA link', $this->theme ),
		               'desc' => esc_html( 'Provide CTA link url', $this->theme ),
		               'id'   => $this->theme . '_cta_link',
		               'type' => 'text_url',
		               'default' => ''
		               ) );

    }

	/**
	 * Load the plugin text domain for translation.
	 */


	/**
	 * Flushes rewrite rules on plugin activation to ensure identities posts don't 404.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/flush_rewrite_rules
	 *
	 * @uses identities Item_Post_Type::identities_init()
	 */
	public function plugin_activation() {
		$this->identities_init();
		flush_rewrite_rules();
	}

	/**
	 * Initiate registrations of post type and taxonomies.
	 *
	 * @uses identities Item_Post_Type::register_post_type()
	 * @uses identities Item_Post_Type::register_taxonomy_tag()
	 * @uses identities Item_Post_Type::register_taxonomy_category()
	 */
	public function identities_init() {
		$this->register_post_type();
		$this->register_taxonomy_category();
		$this->register_taxonomy_tag();
        //$this->add_events_metaboxes();
	}

	/**
	 * Get an array of all taxonomies this plugin handles.
	 *
	 * @return array Taxonomy slugs.
	 */
	protected function get_taxonomies() {
		return array( 'identities_category', 'identities_tag' );
	}

	/**
	 * Enable the identities Item custom post type.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/register_post_type
	 */
	protected function register_post_type() {
		$labels = array(
			'name'               => esc_html( 'Identities', 'myticket' ),
			'singular_name'      => esc_html( 'Identities', 'myticket' ),
			'add_new'            => esc_html( 'Add New', 'myticket' ),
			'add_new_item'       => esc_html( 'Add New', 'myticket' ),
			'edit_item'          => esc_html( 'Edit Item', 'myticket' ),
			'new_item'           => esc_html( 'Add New  Item', 'myticket' ),
			'view_item'          => esc_html( 'View Item', 'myticket' ),
			'search_items'       => esc_html( 'Search Items', 'myticket' ),
			'not_found'          => esc_html( 'No items found', 'myticket' ),
			'not_found_in_trash' => esc_html( 'No items found in trash', 'myticket' ),
		);
		
		$args = array(
			'menu_icon' => 'dashicons-universal-access-alt',
			'labels'          => $labels,
			'public'          => true,
			'publicly_queryable' => false,
			'supports'        => array(
				'title',
				//'editor',
				//'excerpt',
				'thumbnail',
				//'comments',
				//'author',
				//'custom-fields',
				'revisions',
			),
			'capability_type' => 'page',
			'menu_position'   => 4,
			'hierarchical'      => true,
			'has_archive'     => true,
		);

		$args = apply_filters( 'myticket_args', $args );
		register_post_type( 'identities', $args );
	}



	/**
	 * Register a taxonomy for identities Item Tags.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/register_taxonomy
	 */
	protected function register_taxonomy_tag() {
		$labels = array(
			'name'                       => esc_html( 'Tags', 'myticket' ),
			'singular_name'              => esc_html( 'Tag', 'myticket' ),
			'menu_name'                  => esc_html( 'Tags', 'myticket' ),
			'edit_item'                  => esc_html( 'Edit Tag', 'myticket' ),
			'update_item'                => esc_html( 'Update Tag', 'myticket' ),
			'add_new_item'               => esc_html( 'Add New Tag', 'myticket' ),
			'new_item_name'              => esc_html( 'New  Tag Name', 'myticket' ),
			'parent_item'                => esc_html( 'Parent Tag', 'myticket' ),
			'parent_item_colon'          => esc_html( 'Parent Tag:', 'myticket' ),
			'all_items'                  => esc_html( 'All Tags', 'myticket' ),
			'search_items'               => esc_html( 'Search  Tags', 'myticket' ),
			'popular_items'              => esc_html( 'Popular Tags', 'myticket' ),
			'separate_items_with_commas' => esc_html( 'Separate tags with commas', 'myticket' ),
			'add_or_remove_items'        => esc_html( 'Add or remove tags', 'myticket' ),
			'choose_from_most_used'      => esc_html( 'Choose from the most used tags', 'myticket' ),
			'not_found'                  => esc_html( 'No  tags found.', 'myticket' ),
		);

		$args = array(
			'labels'            => $labels,
			'public'            => false,
			'show_in_nav_menus' => false,
			'show_ui'           => true,
			'show_tagcloud'     => true,
			'hierarchical'      => false,
			'show_admin_column' => true,
			'query_var'         => true,

		);

		$args = apply_filters( 'myticket_tag_args', $args );

		register_taxonomy( 'identities_tag', array( 'identities' ), $args );
	}

	/**
	 * Register a taxonomy for identities Item Categories.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/register_taxonomy
	 */
	protected function register_taxonomy_category() {
		

		$labels = array(
			'name'                       => esc_html( 'Categories', 'myticket' ),
			'singular_name'              => esc_html( 'Category', 'myticket' ),
			'menu_name'                  => esc_html( 'Categories', 'myticket' ),
			'edit_item'                  => esc_html( 'Edit Category', 'myticket' ),
			'update_item'                => esc_html( 'Update Category', 'myticket' ),
			'add_new_item'               => esc_html( 'Add New Category', 'myticket' ),
			'new_item_name'              => esc_html( 'New Category Name', 'myticket' ),
			'parent_item'                => esc_html( 'Parent Category', 'myticket' ),
			'parent_item_colon'          => esc_html( 'Parent Category:', 'myticket' ),
			'all_items'                  => esc_html( 'All Categories', 'myticket' ),
			'search_items'               => esc_html( 'Search Categories', 'myticket' ),
			'popular_items'              => esc_html( 'Popular Categories', 'myticket' ),
			'separate_items_with_commas' => esc_html( 'Separate categories with commas', 'myticket' ),
			'add_or_remove_items'        => esc_html( 'Add or remove categories', 'myticket' ),
			'choose_from_most_used'      => esc_html( 'Choose from the most used categories', 'myticket' ),
			'not_found'                  => esc_html( 'No categories found.', 'myticket' ),
		);

		$args = array(
			'labels'            => $labels,
			'public'            => false,
			'show_in_nav_menus' => false,
			'show_ui'           => true,
			'show_tagcloud'     => true,
			'hierarchical'      => true,
			'show_admin_column' => true,
			'query_var'         => true,
		);

		$args = apply_filters( 'myticket_category_args', $args );

        register_taxonomy( 'identities_category', array( 'identities' ), $args );
	}

		

	/**
	 * Add taxonomy terms as body classes.
	 *
	 * If the taxonomy doesn't exist (has been unregistered), then get_the_terms() returns WP_Error, which is checked
	 * for before adding classes.
	 *
	 * @param array $classes Existing body classes.
	 *
	 * @return array Amended body classes.
	 */
	public function add_body_classes( $classes ) {
		$taxonomies = $this->get_taxonomies();

		foreach( $taxonomies as $taxonomy ) {
			$terms = get_the_terms( get_the_ID(), $taxonomy );
			if ( $terms && ! is_wp_error( $terms ) ) {
				foreach( $terms as $term ) {
					$classes[] = sanitize_html_class( str_replace( '_', '-', $taxonomy ) . '-' . $term->slug );
				}
			}
		}

		return $classes;
	}

	/**
	 * Add columns to identities Item list screen.
	 *
	 * @link http://wptheming.com/2010/07/column-edit-pages/
	 *
	 * @param array $columns Existing columns.
	 *
	 * @return array Amended columns.
	 */
	public function add_thumbnail_column_identities( $columns ) {
		$column_thumbnail = array( 'thumbnail' => esc_html( 'Thumbnail', 'myticket' ) );
		return array_slice( $columns, 0, 2, true ) + $column_thumbnail + array_slice( $columns, 1, null, true );
	}

	/**
	 * Custom column callback
	 *
	 * @global stdClass $post Post object.
	 *
	 * @param string $column Column ID.
	 */
	public function display_thumbnail_identities( $column ) {
		global $post;
        if( $post->post_type == 'identities' ){
            switch ( $column ) {
                case 'thumbnail':
                    echo get_the_post_thumbnail( $post->ID, array(35, 35, true ), array('class' => 'img-responsive') );
                break;
            }
        }
	}

	/**
	 * Add taxonomy filters to the identities admin page.
	 *
	 * Code artfully lifted from http://pippinsplugins.com/
	 *
	 * @global string $typenow
	 */
	public function add_taxonomy_filters() {
		global $typenow;

		// An array of all the taxonomies you want to display. Use the taxonomy name or slug
		$taxonomies = $this->get_taxonomies();

		// Must set this to the post type you want the filter(s) displayed on
		if ( 'identities' != $typenow ) {
			return;
		}

		foreach ( $taxonomies as $tax_slug ) {
			$current_tax_slug = isset( $_GET[$tax_slug] ) ? $_GET[$tax_slug] : false;
			$tax_obj          = get_taxonomy( $tax_slug );
			$tax_name         = $tax_obj->labels->name;
			$terms            = get_terms( $tax_slug );
			if ( 0 == count( $terms ) ) {
				return;
			}
			echo '<select name="' . esc_attr( $tax_slug ) . '" id="' . esc_attr( $tax_slug ) . '" class="postform">';
			echo '<option>' . esc_html( $tax_name ) .'</option>';
			foreach ( $terms as $term ) {
				printf(
					'<option value="%s"%s />%s</option>',
					esc_attr( $term->slug ),
					selected( $current_tax_slug, $term->slug ),
					esc_html( $term->name . '(' . $term->count . ')' )
				);
			}
			echo '</select>';
		}
	}

	/**
	 * Add identities Item count to "Right Now" dashboard widget.
	 *
	 * @return null Return early if identities post type does not exist.
	 */
	public function add_identities_counts() {
		if ( ! post_type_exists( 'identities' ) ) {
			return;
		}

		$num_posts = wp_count_posts( 'identities' );

		// Published items
		$href = 'edit.php?post_type=identities';
		$num  = number_format_i18n( $num_posts->publish );
		$num  = $this->link_if_can_edit_posts( $num, $href );
		$text = _n( 'identities Item Item', 'identities Item Items', intval( $num_posts->publish ) );
		$text = $this->link_if_can_edit_posts( $text, $href );
		$this->display_dashboard_count( $num, $text );

		if ( 0 == $num_posts->pending ) {
			return;
		}

		// Pending items
		$href = 'edit.php?post_status=pending&amp;post_type=identities';
		$num  = number_format_i18n( $num_posts->pending );
		$num  = $this->link_if_can_edit_posts( $num, $href );
		$text = _n( 'identities Item Item Pending', 'identities Item Items Pending', intval( $num_posts->pending ) );
		$text = $this->link_if_can_edit_posts( $text, $href );
		$this->display_dashboard_count( $num, $text );
	}

	/**
	 * Wrap a dashboard number or text value in a link, if the current user can edit posts.
	 *
	 * @param  string $value Value to potentially wrap in a link.
	 * @param  string $href  Link target.
	 *
	 * @return string        Value wrapped in a link if current user can edit posts, or original value otherwise.
	 */
	protected function link_if_can_edit_posts( $value, $href ) {
		if ( current_user_can( 'edit_posts' ) ) {
			return '<a href="' . esc_url( $href ) . '">' . $value . '</a>';
		}
		return $value;
	}

	/**
	 * Display a number and text with table row and cell markup for the dashboard counters.
	 *
	 * @param  string $number Number to display. May be wrapped in a link.
	 * @param  string $label  Text to display. May be wrapped in a link.
	 */
	protected function display_dashboard_count( $number, $label ) {
		?>
		<tr>
			<td class="first b b-identities"><?php echo esc_html( $number ); ?></td>
			<td class="t identities"><?php echo esc_html( $label ); ?></td>
		</tr>
		<?php
	}
}

new myticket_identities_Post_Type;

endif;
