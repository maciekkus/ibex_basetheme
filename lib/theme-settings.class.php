<?php

/**
 * Master theme class
 * 
 * @package Bolts
 * @since 1.0
 */
class BaseIbexTheme_Options {
	
	private $sections;
	private $checkboxes;
	private $settings;
	
	/**
	 * Construct
	 *
	 * @since 1.0
	 */
	public function __construct() {
		
		// This will keep track of the checkbox options for the validate_settings function.
		$this->checkboxes = array();
		$this->settings = array();
		$this->get_settings();
		
		$this->sections['general']      = __( 'General Settings' );
		$this->sections['appearance']   = __( 'Appearance' );
		$this->sections['images']   = __( 'Images Settings' );
		$this->sections['advanced']   = __( 'Advanced Settings' );
		$this->sections['social-media']   = __( 'Social media' );
		$this->sections['reset']        = __( 'Reset to Defaults' );
		$this->sections['about']        = __( 'About' );

		$this->sections = apply_filters('child_theme_options_sections',&$this->sections); // alow child theme to override default config sections

		add_action( 'admin_menu', array( &$this, 'add_pages' ) );
		add_action( 'admin_init', array( &$this, 'register_settings' ) );
		
		if ( ! get_option( 'baseibextheme_options' ) )
			$this->initialize_settings();
		
	}
	
	/**
	 * Add options page
	 *
	 * @since 1.0
	 */
	public function add_pages() {
		
		$admin_page = add_theme_page( __( 'Theme Options' ), __( 'Theme Options' ), 'manage_options', 'baseibextheme-options', array( &$this, 'display_page' ) );
		
		add_action( 'admin_print_scripts-' . $admin_page, array( &$this, 'scripts' ) );
		add_action( 'admin_print_styles-' . $admin_page, array( &$this, 'styles' ) );
		
	}
	
	/**
	 * Create settings field
	 *
	 * @since 1.0
	 */
	public function create_setting( $args = array() ) {
		
		$defaults = array(
			'id'      => 'default_field',
			'title'   => __( 'Default Field' ),
			'desc'    => __( 'This is a default description.' ),
			'std'     => '',
			'type'    => 'text',
			'section' => 'general',
			'choices' => array(),
			'class'   => ''
		);
			
		extract( wp_parse_args( $args, $defaults ) );
		
		$field_args = array(
			'type'      => $type,
			'id'        => $id,
			'desc'      => $desc,
			'std'       => $std,
			'choices'   => $choices,
			'label_for' => $id,
			'class'     => $class
		);
		
		if ( $type == 'checkbox' )
			$this->checkboxes[] = $id;
		
		add_settings_field( $id, $title, array( $this, 'display_setting' ), 'baseibextheme-options', $section, $field_args );
	}
	
	/**
	 * Display options page
	 *
	 * @since 1.0
	 */
	public function display_page() {
		
		echo '<div class="wrap">
	<div class="icon32" id="icon-options-general"></div>
	<h2>' . __( 'Theme Options' ) . '</h2>';
	
		if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] == true )
			echo '<div class="updated fade"><p>' . __( 'Theme options updated.' ) . '</p></div>';
		
		echo '<form action="options.php" method="post">';
	
		settings_fields( 'baseibextheme_options' );
		echo '<div class="ui-tabs">
			<ul class="ui-tabs-nav">';
		
		foreach ( $this->sections as $section_slug => $section )
			echo '<li><a href="#' . $section_slug . '">' . $section . '</a></li>';
		
		echo '</ul>';
		do_settings_sections( $_GET['page'] );
		
		echo '</div>
		<p class="submit"><input name="Submit" type="submit" class="button-primary" value="' . __( 'Save Changes' ) . '" /></p>
		
	</form>';
	
	echo '<script type="text/javascript">
		jQuery(document).ready(function($) {
			var sections = [];';
			
			foreach ( $this->sections as $section_slug => $section )
				echo "sections['$section'] = '$section_slug';";
			
			echo 'var wrapped = $(".wrap h3").wrap("<div class=\"ui-tabs-panel\">");
			wrapped.each(function() {
				$(this).parent().append($(this).parent().nextUntil("div.ui-tabs-panel"));
			});
			$(".ui-tabs-panel").each(function(index) {
				$(this).attr("id", sections[$(this).children("h3").text()]);
				if (index > 0)
					$(this).addClass("ui-tabs-hide");
			});
			$(".ui-tabs").tabs({
				fx: { opacity: "toggle", duration: "fast" }
			});
			
			$("input[type=text], textarea").each(function() {
				if ($(this).val() == $(this).attr("placeholder") || $(this).val() == "")
					$(this).css("color", "#999");
			});
			
			$("input[type=text], textarea").focus(function() {
				if ($(this).val() == $(this).attr("placeholder") || $(this).val() == "") {
					$(this).val("");
					$(this).css("color", "#000");
				}
			}).blur(function() {
				if ($(this).val() == "" || $(this).val() == $(this).attr("placeholder")) {
					$(this).val($(this).attr("placeholder"));
					$(this).css("color", "#999");
				}
			});
			
			$(".wrap h3, .wrap table").show();
			
			// This will make the "warning" checkbox class really stand out when checked.
			// I use it here for the Reset checkbox.
			$(".warning").change(function() {
				if ($(this).is(":checked"))
					$(this).parent().css("background", "#c00").css("color", "#fff").css("fontWeight", "bold");
				else
					$(this).parent().css("background", "none").css("color", "inherit").css("fontWeight", "normal");
			});
			
			// Browser compatibility
			if ($.browser.mozilla) 
			         $("form").attr("autocomplete", "off");
		});
	/*	jQuery(document).ready(function() {
		 
		    jQuery(".st_upload_button").click(function() {
		         targetfield = jQuery(this).prev(".upload-url");
		         tb_show("", "media-upload.php?type=image&amp;TB_iframe=true");
		         return false;
		    });
		 
		    window.send_to_editor = function(html) {
		         imgurl = jQuery("img",html).attr("src");
		         jQuery(targetfield).val(imgurl);
		         tb_remove();
		    }
		 
		}); */		
	</script>
</div>';
		
	}
	
	/**
	 * Description for section
	 *
	 * @since 1.0
	 */
	public function display_section() {
		// code
	}
	
	/**
	 * Description for About section
	 *
	 * @since 1.0
	 */
	public function display_about_section() {
		
		// This displays on the "About" tab. Echo regular HTML here, like so:
		// echo '<p>Copyright 2011 me@example.com</p>';
		
	}
	
	/**
	 * HTML output for text field
	 *
	 * @since 1.0
	 */
	public function display_setting( $args = array() ) {
		
		extract( $args );
		
		$options = get_option( 'baseibextheme_options' );
		
		if ( ! isset( $options[$id] ) && $type != 'checkbox' )
			$options[$id] = $std;
		elseif ( ! isset( $options[$id] ) )
			$options[$id] = 0;
		
		$field_class = '';
		if ( $class != '' )
			$field_class = ' ' . $class;
		
		switch ( $type ) {
			
			case 'heading':
				echo '</td></tr><tr valign="top"><td colspan="2"><h4>' . $desc . '</h4>';
				break;
			
			case 'checkbox':
				
				echo '<input class="checkbox' . $field_class . '" type="checkbox" id="' . $id . '" name="baseibextheme_options[' . $id . ']" value="1" ' . checked( $options[$id], 1, false ) . ' /> <label for="' . $id . '">' . $desc . '</label>';
				
				break;
			
			case 'select':
				echo '<select class="select' . $field_class . '" name="baseibextheme_options[' . $id . ']">';
				
				foreach ( $choices as $value => $label )
					echo '<option value="' . esc_attr( $value ) . '"' . selected( $options[$id], $value, false ) . '>' . $label . '</option>';
				
				echo '</select>';
				
				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';
				
				break;
			
			case 'radio':
				$i = 0;
				foreach ( $choices as $value => $label ) {
					echo '<input class="radio' . $field_class . '" type="radio" name="baseibextheme_options[' . $id . ']" id="' . $id . $i . '" value="' . esc_attr( $value ) . '" ' . checked( $options[$id], $value, false ) . '> <label for="' . $id . $i . '">' . $label . '</label>';
					if ( $i < count( $options ) - 1 )
						echo '<br />';
					$i++;
				}
				
				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';
				
				break;
			
			case 'textarea':
				echo '<textarea class="' . $field_class . '" id="' . $id . '" name="baseibextheme_options[' . $id . ']" placeholder="' . $std . '" rows="5" cols="30">' . wp_htmledit_pre( $options[$id] ) . '</textarea>';
				
				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';
				
				break;
			
			case 'password':
				echo '<input class="regular-text' . $field_class . '" type="password" id="' . $id . '" name="baseibextheme_options[' . $id . ']" value="' . esc_attr( $options[$id] ) . '" />';
				
				if ( $desc != '' )
					echo '<br /><span class="description">' . $desc . '</span>';
				
				break;
/*			case 'upload':
			   echo '<input id="' . $id . '" class="upload-url' . $field_class . '" type="text" name="baseibextheme_options[' . $id . ']" value="' . esc_attr( $options[$id] ) . '" />';
			   echo '<input id="st_upload_button" class="st_upload_button" type="button" name="upload_button" value="Upload" />';
			 
			   if ( $desc != '' )
			    	echo '<br /><span class="description">' . $desc . '</span>';
			 
			break; */
						
			case 'text':
			default:
		 		echo '<input class="regular-text' . $field_class . '" type="text" id="' . $id . '" name="baseibextheme_options[' . $id . ']" placeholder="' . $std . '" value="' . esc_attr( $options[$id] ) . '" />';
		 		
		 		if ( $desc != '' )
		 			echo '<br /><span class="description">' . $desc . '</span>';
		 		
		 		break;
		 	
		}
		
	}
	
	/**
	 * Settings and defaults
	 * 
	 * @since 1.0
	 */
	public function get_settings() {
		
		/* Images Settings 
		===========================================*/

		$this->settings['colorbox_style'] = array(
			'title'   => __( 'Colorbox Style' ),
			'desc'    => __( 'Select color template for colorbox script' ),
			'std'     => 'black',
			'type'    => 'radio',
			'section' => 'images',
			'choices' => array(
				'black' => __( 'Black Style' ),
				'white' => __( 'White Style' ),
			)
			
		);
		$this->settings['jquery_version'] = array(
			'title'   => __( 'JQuery version in CDN' ),
			'desc'    => __( 'Default: 1, risky - if Google CDN will change, and your scripts will fall into some incompatibilities' ),
			'std'     => '1',
			'type'    => 'text',
			'section' => 'advanced'
		);
		$this->settings['cdn_domain'] = array(
			'title'   => __( 'CDN domain' ),
			'desc'    => __( 'Default: ' ),
			'std'     => '',
			'type'    => 'text',
			'section' => 'advanced'
		);
		$this->settings['disable_base_style'] = array(
			'title'   => __( 'Disable Base Theme Style (HTML5 Boilerplate)' ),
			'desc'    => __( 'check to disable base theme style' ),
			'std'     => '',
			'type'    => 'checkbox',
			'section' => 'advanced'
		);
		$this->settings['disable_normalize_style'] = array(
			'title'   => __( 'Disable Base Theme Style (Normalize)' ),
			'desc'    => __( 'check to disable Normalize style' ),
			'std'     => '',
			'type'    => 'checkbox',
			'section' => 'advanced'
		);
		$this->settings['disable_modernizr_script'] = array(
			'title'   => __( 'Disable modernizr script' ),
			'desc'    => __( 'check to disable modernizr script' ),
			'std'     => '',
			'type'    => 'checkbox',
			'section' => 'advanced'
		);
		$this->settings['fb_app_enabled'] = array(
			'title'   => __( 'Enable Facebook Social Plugins' ),
			'desc'    => __( '' ),
			'std'     => '',
			'type'    => 'checkbox',
			'section' => 'social-media'
		);	
		$this->settings['fb_app_id'] = array(
			'title'   => __( 'Facebook App ID' ),
			'desc'    => __( 'Application Id/Key: ' ),
			'std'     => '',
			'type'    => 'text',
			'section' => 'social-media'
		);		
		$this->settings['fb_app_secret'] = array(
			'title'   => __( 'Facebook App Secret' ),
			'desc'    => __( '' ),
			'std'     => '',
			'type'    => 'text',
			'section' => 'social-media'
		);		
		$this->settings['gplus_profile_id'] = array(
			'title'   => __( 'Google Plus Profile' ),
			'desc'    => __( 'Enter Google+ ID (numbers): ' ),
			'std'     => '',
			'type'    => 'text',
			'section' => 'social-media'
		);	
		$this->settings['base_style_version'] = array(
			'title'   => __( 'Styles and scripts version' ),
			'desc'    => __( '' ),
			'std'     => '',
			'type'    => 'text',
			'section' => 'advanced'
		);		
		/* General Settings
		===========================================*/
		/*
		$this->settings['example_text'] = array(
			'title'   => __( 'Example Text Input' ),
			'desc'    => __( 'This is a description for the text input.' ),
			'std'     => 'Default value',
			'type'    => 'text',
			'section' => 'general'
		);
		
		$this->settings['example_textarea'] = array(
			'title'   => __( 'Example Textarea Input' ),
			'desc'    => __( 'This is a description for the textarea input.' ),
			'std'     => 'Default value',
			'type'    => 'textarea',
			'section' => 'general'
		);
		
		$this->settings['example_checkbox'] = array(
			'section' => 'general',
			'title'   => __( 'Example Checkbox' ),
			'desc'    => __( 'This is a description for the checkbox.' ),
			'type'    => 'checkbox',
			'std'     => 1 // Set to 1 to be checked by default, 0 to be unchecked by default.
		);
		
		$this->settings['example_heading'] = array(
			'section' => 'general',
			'title'   => '', // Not used for headings.
			'desc'    => 'Example Heading',
			'type'    => 'heading'
		);
		
		$this->settings['example_radio'] = array(
			'section' => 'general',
			'title'   => __( 'Example Radio' ),
			'desc'    => __( 'This is a description for the radio buttons.' ),
			'type'    => 'radio',
			'std'     => '',
			'choices' => array(
				'choice1' => 'Choice 1',
				'choice2' => 'Choice 2',
				'choice3' => 'Choice 3'
			)
		);
		
		$this->settings['example_select'] = array(
			'section' => 'general',
			'title'   => __( 'Example Select' ),
			'desc'    => __( 'This is a description for the drop-down.' ),
			'type'    => 'select',
			'std'     => '',
			'choices' => array(
				'choice1' => 'Other Choice 1',
				'choice2' => 'Other Choice 2',
				'choice3' => 'Other Choice 3'
			)
		);
		*/
		/* Appearance
		===========================================*/
		/*
		$this->settings['header_logo'] = array(
			'section' => 'appearance',
			'title'   => __( 'Header Logo' ),
			'desc'    => __( 'Enter the URL to your logo for the theme header.' ),
			'type'    => 'text',
			'std'     => ''
		);
		
		$this->settings['favicon'] = array(
			'section' => 'appearance',
			'title'   => __( 'Favicon' ),
			'desc'    => __( 'Enter the URL to your custom favicon. It should be 16x16 pixels in size.' ),
			'type'    => 'text',
			'std'     => ''
		);
		
		$this->settings['custom_css'] = array(
			'title'   => __( 'Custom Styles' ),
			'desc'    => __( 'Enter any custom CSS here to apply it to your theme.' ),
			'std'     => '',
			'type'    => 'textarea',
			'section' => 'appearance',
			'class'   => 'code'
		);
		*/	
		/* Reset
		===========================================*/
		
		$this->settings['reset_theme'] = array(
			'section' => 'reset',
			'title'   => __( 'Reset theme' ),
			'type'    => 'checkbox',
			'std'     => 0,
			'class'   => 'warning', // Custom class for CSS
			'desc'    => __( 'Check this box and click "Save Changes" below to reset theme options to their defaults.' )
		);
		$this->settings = apply_filters('child_theme_options_settings',&$this->settings);  // alow child theme to override default config settings
	}
	
	/**
	 * Initialize settings to their default values
	 * 
	 * @since 1.0
	 */
	public function initialize_settings() {
		
		$default_settings = array();
		foreach ( $this->settings as $id => $setting ) {
			if ( $setting['type'] != 'heading' )
				$default_settings[$id] = $setting['std'];
		}
		
		update_option( 'baseibextheme_options', $default_settings );
		
	}
	
	/**
	* Register settings
	*
	* @since 1.0
	*/
	public function register_settings() {
		
		register_setting( 'baseibextheme_options', 'baseibextheme_options', array ( &$this, 'validate_settings' ) );
		foreach ( $this->sections as $slug => $title ) {
			if ( $slug == 'about' )
				add_settings_section( $slug, $title, array( &$this, 'display_about_section' ), 'baseibextheme-options' );
			else
				add_settings_section( $slug, $title, array( &$this, 'display_section' ), 'baseibextheme-options' );
		}
		
		$this->get_settings();
		
		foreach ( $this->settings as $id => $setting ) {
			$setting['id'] = $id;
			$this->create_setting( $setting );
		}
		
	}
	
	/**
	* jQuery Tabs
	*
	* @since 1.0
	*/
	public function scripts() {
		
		wp_print_scripts( 'jquery-ui-tabs' );
		//Media Uploader Scripts
//		wp_enqueue_script('media-upload');
//		wp_enqueue_script('thickbox');
//		wp_register_script('my-upload', get_bloginfo( 'stylesheet_directory' ) . '/js/uploader.js', array('jquery','media-upload','thickbox'));
//		wp_enqueue_script('my-upload');
		
		
	}
	
	/**
	* Styling for the theme options page
	*
	* @since 1.0
	*/
	public function styles() {
		
		wp_register_style( 'baseibextheme-admin', get_bloginfo( 'template_directory' ) . '/css/baseibextheme-options.css' );
		wp_enqueue_style( 'baseibextheme-admin' );

		//Media Uploader Style
//		wp_enqueue_style('thickbox');
		
		
	}	
	/**
	* Validate settings
	*
	* @since 1.0
	*/
	public function validate_settings( $input ) {
		
		if ( ! isset( $input['reset_theme'] ) ) {
			$options = get_option( 'baseibextheme_options' );
			
			foreach ( $this->checkboxes as $id ) {
				if ( isset( $options[$id] ) && ! isset( $input[$id] ) ) {
					echo $options[$id];
					unset( $options[$id] );
				}
			}
			
			return $input;
		}
		return false;
		
	}
	
}

$theme_options = new BaseIbexTheme_Options();

function baseibextheme_option( $option, $default_value = false) {
	$options = get_option( 'baseibextheme_options');
	if ( isset( $options[$option] ) )
		return $options[$option];
	else
		return $default_value;
}
?>