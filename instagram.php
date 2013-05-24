<?php
/*
Plugin Name: TCVN Instagram Gallery Widget
Plugin URI: http://VinaThemes.biz
Description: A widget that display your images from Instagram in a clean and simple gallery.
Version: 1.0
Author: VinaThemes
Author URI: http://VinaThemes.biz
Author email: mr_hiennc@yahoo.com
Demo URI: http://VinaDemo.biz
Forum URI: http://VinaForum.biz
License: GPLv3+
*/

//Defined global variables
if(!defined('TCVN_INSTAGRAM_DIRECTORY')) 		define('TCVN_INSTAGRAM_DIRECTORY', dirname(__FILE__));
if(!defined('TCVN_INSTAGRAM_INC_DIRECTORY')) 	define('TCVN_INSTAGRAM_INC_DIRECTORY', TCVN_INSTAGRAM_DIRECTORY . '/includes');
if(!defined('TCVN_INSTAGRAM_URI')) 			define('TCVN_INSTAGRAM_URI', get_bloginfo('url') . '/wp-content/plugins/tcvn-instagram-widget');
if(!defined('TCVN_INSTAGRAM_INC_URI')) 		define('TCVN_INSTAGRAM_INC_URI', TCVN_INSTAGRAM_URI . '/includes');

//Include library
if(!defined('TCVN_FUNCTIONS')) {
    include_once TCVN_INSTAGRAM_INC_DIRECTORY . '/functions.php';
    define('TCVN_FUNCTIONS', 1);
}
if(!defined('TCVN_FIELDS')) {
    include_once TCVN_INSTAGRAM_INC_DIRECTORY . '/fields.php';
    define('TCVN_FIELDS', 1);
}

class Instagram_Widget extends WP_Widget 
{
	function Instagram_Widget()
	{
		$widget_ops = array(
			'classname' 	=> 'instagram_widget',
			'description' 	=> __('A widget that display your images from Instagram in a clean and simple gallery.')
		);
		$this->WP_Widget('instagram_widget', __('TCVN Instagram Gallery'), $widget_ops);
	}
	
	function form($instance)
	{
		$instance = wp_parse_args( 
			(array) $instance, 
			array( 
				'title' 		=> '',
				'clientID' 		=> '',
				'clientSecret' 	=> '',
				'redirectURI' 	=> '',
				'accessToken' 	=> '',
				'imageType' 	=> 'mostpopular',
				'userId' 		=> '',
				'userTag' 		=> '',
				'gwidth' 		=> '590',
				'gheight' 		=> '350',
				'noImages' 		=> '10',
				'thumnSize' 	=> '100',
				'imageStyle' 	=> 'background:#DDDDDD; border:1px solid #CCCCCCC; padding:4px;',
				'likeComments' 	=> 'yes',
				'introText1' 	=> 'Gallery Image',
				'introText2' 	=> 'from Instagram',
			)
		);

		$title			= esc_attr($instance['title']);
		$clientID		= esc_attr($instance['clientID']);
		$clientSecret	= esc_attr($instance['clientSecret']);
		$redirectURI	= esc_attr($instance['redirectURI']);
		$accessToken	= esc_attr($instance['accessToken']);
		$imageType		= esc_attr($instance['imageType']);
		$userId			= esc_attr($instance['userId']);
		$userTag		= esc_attr($instance['userTag']);
		$gwidth			= esc_attr($instance['gwidth']);
		$gheight		= esc_attr($instance['gheight']);
		$noImages		= esc_attr($instance['noImages']);
		$thumnSize		= esc_attr($instance['thumnSize']);
		$imageStyle		= esc_attr($instance['imageStyle']);
		$likeComments	= esc_attr($instance['likeComments']);
		$introText1		= esc_attr($instance['introText1']);
		$introText2		= esc_attr($instance['introText2']);
		?>
        <div id="tcvn-instagram" class="tcvn-plugins-container">
            <div id="tcvn-tabs-container">
                <ul id="tcvn-tabs">
                    <li class="active"><a href="#basic"><?php _e('Basic'); ?></a></li>
                    <li><a href="#advanced"><?php _e('Advanced'); ?></a></li>
                </ul>
            </div>
            <div id="tcvn-elements-container">
                <!-- Basic Block -->
                <div id="basic" class="tcvn-telement" style="display: block;">
                    <p><?php echo eTextField($this, 'title', 'Title', $title); ?></p>
                    <p><?php echo eTextField($this, 'clientID', 'Client ID', $clientID); ?></p>
                    <p><?php echo eTextField($this, 'clientSecret', 'Client Secret', $clientSecret); ?></p>
                    <p><?php echo eTextField($this, 'redirectURI', 'Redirect URI', $redirectURI); ?></p>
                    <p><a href="javascript: void(0);" onClick="javascript: connectInstagram();"><?php _e('Connect Instagram'); ?></a></p>
                    <p><?php echo eTextField($this, 'accessToken', 'Access Token', $accessToken); ?></p>
                </div>
                <!-- Advanced Block -->
                <div id="advanced" class="tcvn-telement">
                    <p>
						<?php echo eSelectOption($this, 'imageType', 'Image Type', 
						array(
							'mostpopular'	=> 'Most popular images from Instagram', 
							'recentuser'	=> 'Most recent images published by a user', 
							'userfeed'		=> 'Authenticated users feed', 
							'userlikes'		=> 'Authenticated users list of image they have liked',
							'recenttag'		=> 'Get list recent tagged images',
						), $imageType); ?>
                	</p>
                    <p><?php echo eTextField($this, 'userId', 'Images from User', $userId, 
						'Enter user id if you want get image from a special User.'); ?></p>
                    <p><?php echo eTextField($this, 'userTag', 'Images from Tag', $userTag, 
						'Enter Tag Name if you want get image from a special Tag.'); ?></p>
                    <p><?php echo eTextField($this, 'gwidth', 'Gallery Width(px)', $gwidth, 
						'Set the Gallery Width in pixels.'); ?></p>
                    <p><?php echo eTextField($this, 'gheight', 'Gallery Height(px)', $gheight, 
						'Set the Gallery Height in pixels.'); ?></p>
                    <p><?php echo eTextField($this, 'noImages', 'Num of Images', $noImages, 
						'Set the number of images that you want to display in your gallery. You can set a number between 1 and 30. The maximum of 30 is due to Instagram limitations. Default: 12'); ?></p>
                    <p><?php echo eTextField($this, 'thumnSize', 'Thumb size(px)', $thumnSize, 
						'Set the thumb size in pixels. You can use a value between 1 and 150 pixels.'); ?></p>
                    <p><?php echo eTextField($this, 'imageStyle', 'Image Style', $imageStyle); ?></p>
                    <p><?php echo eSelectOption($this, 'likeComments', 'Show likes and comments', 
						array('yes' => 'Show', 'no'  => 'Hide'), $likeComments); ?>
                	</p>
                    <p><?php echo eTextField($this, 'introText1', 'Introtext line 1', $introText1, 'Introtext line 1'); ?></p>
                    <p><?php echo eTextField($this, 'introText2', 'Introtext line 2', $introText2, 'Introtext line 2'); ?></p>
                </div>
            </div>
        </div>
		<script>
			function connectInstagram()
			{
				valid 		= true;
				clientid 	= jQuery("#widget-instagram_widget-2-clientID").val();
				redirecturi = jQuery("#widget-instagram_widget-2-redirectURI").val();
				if(!clientid){
					alert("Please enter Client ID");
					valid = false;
				}else if(!redirecturi){
					alert("Please enter Redirect URI");
					valid = false;
				}
				if(valid){
					wleft = (screen.width - 800) / 2;
					wtop = (screen.height - 600) / 2;
					iwindow = window.open("https://instagram.com/oauth/authorize/?display=touch&client_id="+ clientid + 
							"&redirect_uri="+ redirecturi + "&response_type=token", "iwindow", 
							"status=1, scrollbars=1, width=800, height=600");
					iwindow.moveTo(wleft, wtop);
				}
			}
				
			jQuery(document).ready(function($){
				var prefix = '#tcvn-instagram ';
				$(prefix + "li").click(function() {
					$(prefix + "li").removeClass('active');
					$(this).addClass("active");
					$(prefix + ".tcvn-telement").hide();
					
					var selectedTab = $(this).find("a").attr("href");
					$(prefix + selectedTab).show();
					
					return false;
				});
			});
        </script>
		<?php
	}
	
	function update($new_instance, $old_instance) 
	{
		return $new_instance;
	}
	
	function widget($args, $instance) 
	{
		extract($args);
		
		$title 			= getConfigValue($instance, 'title',		'');
		$clientID		= getConfigValue($instance, 'clientID',		'');
		$clientSecret	= getConfigValue($instance, 'clientSecret',	'');
		$redirectURI	= getConfigValue($instance, 'redirectURI',	'');
		$accessToken	= getConfigValue($instance, 'accessToken',	'');
		$imageType		= getConfigValue($instance, 'imageType',	'mostpopular');
		$userId			= getConfigValue($instance, 'userId',		'');
		$userTag		= getConfigValue($instance, 'userTag',		'');
		$gwidth			= getConfigValue($instance, 'gwidth',		'690');
		$gheight		= getConfigValue($instance, 'gheight',		'290');
		$noImages		= getConfigValue($instance, 'noImages',		'12');
		$thumnSize		= getConfigValue($instance, 'thumnSize',	'100');
		$imageStyle		= getConfigValue($instance, 'imageStyle',	'background:#DDDDDD; border:1px solid #CCCCCCC; padding:4px;');
		$likeComments	= getConfigValue($instance, 'likeComments',	'yes');
		$introText1		= getConfigValue($instance, 'introText1',	'Gallery Image');
		$introText2		= getConfigValue($instance, 'introText2',	'from Instagram');
		$baseUrl		= 'https://api.instagram.com/v1/';
		
		if($noImages > 30) $noImages = 30;
		if($thumnSize > 150) $thumnSize = 150;
		
		switch($imageType)
		{
			case 'recentuser':
				$url = $baseUrl . 'users/'.$userId.'/media/recent?access_token='.$accessToken.'&count='.$noImages;
				break;
				
			case 'recenttag':
				$url = $baseUrl . 'tags/'.$userTag.'/media/recent?client_id='.$clientID.'&access_token='.$accessToken.'&count='.$noImages;
				break;
				
			case 'userfeed':
				$url = $baseUrl . 'users/self/feed?client_id='.$clientID.'&access_token='.$accessToken.'&count='.$noImages;
				break;
			
			case 'userlikes':
				$url = $baseUrl . 'users/self/media/liked?client_id='.$clientID.'&access_token='.$accessToken.'&count='.$noImages;
				break;
			
			default:
				$url = $baseUrl . 'media/popular?client_id='.$clientID.'&access_token='.$accessToken.'&count='.$noImages;
				break;
		}
		
		// Check CURL
		if(!function_exists('curl_version')) {
			exit('Please enable php_curl.dll extension in your host !');
		}
		
		$ch = curl_init(); // Initialize a CURL session.
		// set URL and other appropriate options
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  // Return Page contents.
		curl_setopt($ch, CURLOPT_CAINFO, TCVN_INSTAGRAM_INC_DIRECTORY . "/cacert.pem");
		curl_setopt($ch, CURLOPT_URL, $url);  // Pass URL as parameter.
		$content = curl_exec($ch);  // grab URL and pass it to the variable.
		curl_close($ch);  // close curl resource, and free up system resources.
		$data = json_decode($content);
		unset($content);
		
		echo $before_widget;
		
		if($title) echo $before_title . $title . $after_title;
		?>
        <style type="text/css">
		.tcvn-instagram-main {
			padding: 10px 20px 20px;
			width: <?php echo $gwidth - 20; ?>px;
			height:<?php echo $gheight + 65?>px !important;
			z-index:9999;
		}
		.tcvn-instagram-list-items {
			width:<?php echo $gwidth; ?>px; 
			height:<?php echo $gheight; ?>px; 
			overflow: auto; 
			position:relative; 
			padding-top:10px;
		}
		.tcvn-instagram-image {
			float:left; 
			width:<?php echo $thumnSize; ?>px; 
			margin-right:15px;
		}
		.tcvn-instagram-likes-comments {
			width:<?php echo $thumnSize; ?>px; 
			overflow:hidden; 
			font-size:80%; 
			margin:0 auto;
		}      
		p.tcvn-instagram_likes { 
			float:left;
		}
		p.tcvn-instagram_likes span { 
			background :url(<?php echo TCVN_INSTAGRAM_INC_URI; ?>/images/likes15x15.png) no-repeat 0 50%; 
			display:block; 
			padding-left:20px;
		}
		p.tcvn-instagram-comments { 
			float:right;
		}
		p.tcvn-instagram-comments span { 
			background :url(<?php echo TCVN_INSTAGRAM_INC_URI; ?>/images/comments15x15.png) no-repeat 0 50%; 
			display:block; 
			padding-left:20px;
		}
		.tcvn-instagram-introtext { 
			border-bottom:1px solid #ddd; 
			padding-bottom :5px; 
			overflow:hidden;
		}
		.tcvn-instagram-introtext h3 {
			border:0 !important;
			margin: 7px !important;
			clear: none;
		}
		.tcvn-instagram-introtext h3 span { 
			display:block;
		} 
		.tcvn-instagram-introtext h3 span.tcvn-instagram-text1 { 
			font-size:90%;
			line-height: 18px;
		}
		.tcvn-instagram-introtext h3 span.tcvn-instagram-text2 { 
			font-weight:bold;
			font-size:120%;
			line-height: 20px;
		}
		.tcvn-instagram-img {
			<?php echo $imageStyle; ?>
		}
		.tcvn-instagram-main .instagram-icon {
			float: left;
			box-shadow: 0 0 !important;
			border-radius: 0 0 0 0 !important;
		}
		</style>
        <div id="tcvn-instagram-main" class="tcvn-instagram-main">
            <div class="tcvn-instagram-introtext">
                <img width="50px" height="50px" class="instagram-icon" src="<?php echo TCVN_INSTAGRAM_INC_URI; ?>/images/instagram-icon.png">
                <h3><span class="tcvn-instagram-text1"><?php echo $introText1; ?></span>
                <span class="tcvn-instagram-text2"><?php echo $introText2; ?></span></h3>
            </div>
            <div id="tcvn-instagram-items" class="tcvn-instagram-list-items">
            <?php
            if($data)
            {
                if($data->meta->code != 200) {
                    echo $data->meta->error_message;
                } 
                else { 
                    for($i=0; $i < count($data->data); $i++)
                    {
                        if($data->data[$i])
                        {
                            $url = $data->data[$i]->images->standard_resolution->url;
                    ?>
                        <div class="tcvn-instagram-image">
                            <a href="<?php echo $url; ?>" title="<?php if(isset($data->data[$i]->caption->text)) echo $data->data[$i]->caption->text ?>" target="blank">
                                <img class="tcvn-instagram-img" src="<?php echo $data->data[$i]->images->thumbnail->url ?>" alt="<?php if(isset($data->data[$i]->caption->text)) echo $data->data[$i]->caption->text ?>" height="<?php echo $thumbsize ?>" width="<?php echo $thumnSize ?>" />
                            </a>
                            <?php if($likeComments) { ?>
                                <div class="tcvn-instagram-likes-comments">
                                    <p class="tcvn-instagram_likes"><span><?php if($data->data[$i]->likes->count) echo ''.$data->data[$i]->likes->count; else echo '0';?></span></p>
                                    <p class="tcvn-instagram-comments"><span><?php if($data->data[$i]->comments->count) echo ''.$data->data[$i]->comments->count; else	echo '0';?></span></p>
                                </div>
                            <?php } ?>
                        </div>	
                        <?php }
                    }
                 } 
            }
            ?>
            </div>
        </div>
        <div id="tcvn-copyright">
        	<a href="http://vinathemes.biz" title="Free download Wordpress Themes, Wordpress Plugins - VinaThemes.biz">Free download Wordpress Themes, Wordpress Plugins - VinaThemes.biz</a>
        </div>
        <script type="text/javascript">
		jQuery(document).ready(function($) { 
			$('#tcvn-instagram-main a').lightBox();
		})
		</script>
		<?php
		echo $after_widget;
	}
}

add_action('widgets_init', create_function('', 'return register_widget("Instagram_Widget");'));
wp_enqueue_style('tcvn-recentpost-css', TCVN_INSTAGRAM_INC_URI . '/css/style.css', '', '1.0', 'screen' );
wp_enqueue_style('tcvn-recentpost-admin-css', TCVN_INSTAGRAM_INC_URI . '/admin/css/style.css', '', '1.0', 'screen' );
wp_enqueue_style('tcvn-lightbox-css', TCVN_INSTAGRAM_INC_URI . '/jquery-lightbox/css/jquery.lightbox-0.5.css', '', '1.0', 'screen' );
wp_enqueue_script('tcvn-tooltips', TCVN_INSTAGRAM_INC_URI . '/admin/js/jquery.simpletip-1.3.1.js', 'jquery', '1.0', true);
wp_enqueue_script('tcvn-lightbox', TCVN_INSTAGRAM_INC_URI . '/jquery-lightbox/js/jquery.lightbox-0.5.js', 'jquery', '1.0', true);
?>