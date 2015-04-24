<?php
/*
Plugin Name: Hulvire aktuality
Plugin URI: http://www.amfajnor.sk/_hulvire_web/hulvire%20old/index.htm
Description: plugin for displaying messages or news with simple picture gallery on wordpress sites
Version: 1.3.4
Author: Fajnor
Author URI: http://amfajnor.sk
License: GPL2
Text Domain: mee
*/
// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly. Meee';
	exit;
}

if(!class_exists('WP_Hulvire_Aktuality'))
{
    class WP_Hulvire_Aktuality
    {
        /**
         * Construct the plugin object
         */
        public function __construct()
        {
			// Initialize Settings
			require_once(sprintf("%s/hulvire-aktuality-settings.php", dirname(__FILE__)));
			$WP_Hulvire_Aktuality_Settings = new WP_Hulvire_Aktuality_Settings();
			
			// Register custom post types
			//require_once(sprintf("%s/post-types/hulvire-aktuality-img_type.php", dirname(__FILE__)));
			//$Hulvire_Aktuality_Img_Type = new Hulvire_Aktuality_Img_Type();

			$plugin = plugin_basename(__FILE__);
			add_filter("plugin_action_links_$plugin", array( $this, 'plugin_settings_link' ));

			
			
			
			define( 'HUU_VERSION', '1.3.4' );
			define( 'HUU__PLUGIN_URL', plugin_dir_url( __FILE__ ) );
			define( 'HUU__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
			
			
			add_action( 'admin_enqueue_scripts', 'wptuts_add_color_picker' );
			function wptuts_add_color_picker( $hook ) {
 
			    if( is_admin() ) {
     
			        // Add the color picker css file      
			        wp_enqueue_style( 'wp-color-picker' );
         
			        // Include our custom jQuery file with WordPress Color Picker dependency
			        wp_enqueue_script( 'custom-script-handle', plugins_url( 'hulvire-aktuality-admin-script.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
			    }
			}

			function huu_aktuality_scripts_method() {
				wp_enqueue_style('hulvire-aktuality-style_css', HUU__PLUGIN_URL .'hulvire-aktuality-style.css');
				wp_enqueue_script('hulvire-aktuality-script', HUU__PLUGIN_URL .'hulvire-aktuality-script.js');
			}
			add_action('wp_enqueue_scripts', 'huu_aktuality_scripts_method');

			require_once('post-types/hulvire-aktuality-text-type.php');
			
			
			function huu_aktuality_script(){

				echo "<script type='text/javascript' charset='utf-8'>
				(function($) {
				    $(window).load(function() {
					
						$('.ukaz-viacej-aktualit').click(function(){
							$('.dalsia-aktualita').show(500, function(){
								$('.ukaz-viacej-aktualit').remove();
							});
						});
						
				    });
				})(jQuery)
				</script>";
 
			}
			add_action('wp_head', 'huu_aktuality_script');

			function huu_get_aktuality(){
				
				$current_color = get_option('setting_current_color') ?: "#9BC008";
				$img_border_thick = get_option('setting_img_border_thick') ?: "2px";
				$show_more_text = get_option('setting_show_more_text') ?: "Zobraz viac";
				$show_less_text = get_option('setting_show_less_text') ?: "Zobraz menej";
				$color_viacmenej = get_option('setting_color_viacmenej') ?: "Blue";
				$color_text_p = get_option('setting_color_text_p') ?: "#333";
				
				
				$aktuality = '<style>.aktuality .item img,.aktuality .item .image { border:'.$img_border_thick.' solid '.$current_color.'} .textRight a { color:'.$color_viacmenej.' } .aktuality p { color:'.$color_text_p.' }</style>';
				$aktuality.= '<div align="center" style="color:'.$current_color.'">';
				$aktuality.= '<div class="aktuality">';
 
				    $huu_query = "post_type=hulvire_aktuality";
				    query_posts($huu_query);
     			   
					$kolko = get_option('setting_kolko') ?: 3;
					$kolky = 1;
					
				    if (have_posts()) : while (have_posts()) : the_post();
        						
						if ( has_post_thumbnail() ) 
						{	
							$img = get_the_post_thumbnail( $post->ID, 'large' );
						    $thumb = wp_get_attachment_image_src(get_post_thumbnail_id(), 'thumbnail_name');
						$values = get_post_custom( $post->ID );
						$idecko = get_the_ID();
	
						$aktualita_before_text = isset( $values['aktualita_before_text'] ) ? esc_attr( $values['aktualita_before_text'][0] ) : '';
						$aktualita_after_text = isset( $values['aktualita_after_text'] ) ? esc_attr( $values['aktualita_after_text'][0] ) : '';
					
					        if($kolky<=$kolko){
						
								$aktuality.='<div class="item">';
								
								
								$aktuality.='<a href="'.$thumb[0].'" title="'.get_the_title().'"  rel="lightbox['.$idecko.']" >';
								
								$aktuality.=$img;
								
								$aktuality.='</a>';
								
								$aktuality.='<div class="more_img">';
								
								$images = rwmb_meta( 'hulvire_aktuality_imgadv', 'type=image' );
								foreach ( $images as $image )
								{
								$aktuality.= "<a href='{$image['full_url']}' title='{$image['title']}' rel='lightbox[".$idecko."]'><img src='{$image['url']}' width='{$image['width']}' height='{$image['height']}' alt='{$image['alt']}' /></a>";
								}
								
								$aktuality.='</div>';

								
								$aktuality.='<div class="itemContent">';
								$aktuality.='<h3>'.get_the_title().'</h3>';
								
								$aktuality.='<div class="before-more">';
								
								if ($aktualita_before_text!="")
								$aktuality.='<p>'.$aktualita_before_text.'</p>';
								
								$aktuality.='</div><!--/div before-more-->';
								
								$aktuality.='<div id="aftermore'.$idecko.'" style="display: none;" class="aftermore">';
								$aktuality.='<p><span id="more'.$idecko.'"></span>';
								
								if ($aktualita_after_text!="")
								$aktuality.= $aktualita_after_text.'</p>';
								
								$aktuality.='</div><!--/div aftermore-->';
								
								$javascriptCall = "javascript:toggleDiv('aftermore".$idecko."','toggler".$idecko."',".$idecko.");";
								$aktuality.='<p class="textRight"><a href="'.$javascriptCall.'" id="toggler'.$idecko.'" title="'.$show_more_text.' | '.$show_less_text.'">'.$show_more_text.'</a></p>';

								$aktuality.='</div><!--/div itemContent-->';
								$aktuality.='</div><!--/div item-->';
								
								if($kolky==$kolko){
									$aktuality.='<div class="ukaz-viacej-aktualit"><p>Zobraz ďalšie aktuality.</p></div>';
								}
								
							}else{
																
								
								
								$aktuality.='<div class="item dalsia-aktualita">';
								
								
								$aktuality.='<a href="'.$thumb[0].'" title="'.get_the_title().'"  rel="lightbox['.$idecko.']" >';
								
								$aktuality.=$img;
								
								$aktuality.='</a>';
								
								$aktuality.='<div class="more_img">';
								
								$images = rwmb_meta( 'hulvire_aktuality_imgadv', 'type=image' );
								foreach ( $images as $image )
								{
								$aktuality.= "<a href='{$image['full_url']}' title='{$image['title']}' rel='lightbox[".$idecko."]'><img src='{$image['url']}' width='{$image['width']}' height='{$image['height']}' alt='{$image['alt']}' /></a>";
								}
								
								$aktuality.='</div>';

								
								$aktuality.='<div class="itemContent">';
								$aktuality.='<h3>'.get_the_title().'</h3>';
								
								$aktuality.='<div class="before-more">';
								
								if ($aktualita_before_text!="")
								$aktuality.='<p>'.$aktualita_before_text.'</p>';
								
								$aktuality.='</div><!--/div before-more-->';
								
								$aktuality.='<div id="aftermore'.$idecko.'" style="display: none;" class="aftermore">';
								$aktuality.='<p><span id="more'.$idecko.'"></span>';
								
								if ($aktualita_after_text!="")
								$aktuality.= $aktualita_after_text.'</p>';
								
								$aktuality.='</div><!--/div aftermore-->';
								
								$javascriptCall = "javascript:toggleDiv('aftermore".$idecko."','toggler".$idecko."',".$idecko.");";
								$aktuality.='<p class="textRight"><a href="'.$javascriptCall.'" id="toggler'.$idecko.'">'.$show_more_text.'</a></p>';

								$aktuality.='</div><!--/div itemContent-->';
								$aktuality.='</div><!--/div item-->';
							}
							
							
						}
						$kolky++;
             
				    endwhile; endif; 
		
					wp_reset_query();
 
				    $aktuality.= '</div><!--/div aktuality-->';
					$aktuality.= '</div><!--/div center-->';
					
     
				    return $aktuality; 
			}
 
			/**add the shortcode for the aktuality- for use in editor**/
			function huu_insert_aktuality($atts, $content = null){
 
				$aktuality = huu_get_aktuality();
 
				return $aktuality;
			}
			add_shortcode('huu_aktuality', 'huu_insert_aktuality');
 
			/**add template tag- for use in themes**/
			function huu_aktuality(){
	
			    echo huu_get_aktuality();
			}

			
        } // END public function __construct

        /**
         * Activate the plugin
         */
        public static function activate()
        {
            // Do nothing
        } // END public static function activate

        /**
         * Deactivate the plugin
         */     
        public static function deactivate()
        {
            // Do nothing
        } // END public static function deactivate
		
		// Add the settings link to the plugins page
		function plugin_settings_link($links)
		{
			$settings_link = '<a href="options-general.php?page=wp_hulvire_aktuality">Settings</a>';
			array_unshift($links, $settings_link);
			return $links;
		}
		
		
    } // END class WP_Hulvire_Aktuality
} // END if(!class_exists('WP_Hulvire_Aktuality'))

if(class_exists('WP_Hulvire_Aktuality'))
{
    // Installation and uninstallation hooks
    register_activation_hook(__FILE__, array('WP_Hulvire_Aktuality', 'activate'));
    register_deactivation_hook(__FILE__, array('WP_Hulvire_Aktuality', 'deactivate'));

    // instantiate the plugin class
    $wp_hulvire_aktuality = new WP_Hulvire_Aktuality();
}
