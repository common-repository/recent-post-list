<?php
/*
Plugin Name: Recent post list
Description: This plugin will allow to easily and quickly adapted to the post List of design to your website. He also register new post type. More info about this plugin e-mail: aivaraskarnatka@gmail.com
Author: Aivaras KarnatkaTested up to: 4.0Requires at least: 3.8
License: GPLv2 or laterLicense URI: http://www.gnu.org/licenses/gpl-2.0.html
*/



global $wpdb;
$installed_ver = get_option( "jal_db_version"); 
$table_name = $wpdb->prefix .'recent_post_list';
$sql="CREATE TABLE $table_name(
id mediumint(9) NOT NULL AUTO_INCREMENT,
name tinytext NOT NULL,text text NOT NULL,txt tinytext NOT NULL,ex_color tinytext NOT NULL,tml_width tinytext NOT NULL,tml_height tinytext NOT NULL,ex_word_limit tinytext NOT NULL,title_characters_limit tinytext ,button_text tinytext ,button_text_color tinytext ,post_date tinytext ,post_author tinytext ,button_bg_color tinytext NOT NULL NOT NULL,UNIQUE KEY id (id));";
$wpdb->query($sql);
$my_data = $wpdb->get_row("SELECT COUNT(id) as ats FROM $table_name");
if ($my_data->ats == 0 ) {
$sql1 = "INSERT INTO $table_name values (NULL, '#000000', '25', '15', '#525252', '150', '150', '90', '60', 'Read more..','#FFFFFF','yes',' ', '#2A7AB8')";
$wpdb->query($sql1);
}
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
dbDelta($sql);
update_option("jal_db_version", $jal_db_version);$table_name = $wpdb->prefix . 'recent_post_list';$my_data = $wpdb->get_row("SELECT * FROM $table_name");
add_action('admin_print_footer_scripts','eg_quicktags');
function eg_quicktags() {
?>
<script type="text/javascript" charset="utf-8">
edButtons[edButtons.length] = new edButton('post_list','Add Recent post list','[recent_post_list]');
</script>
<?php
}
add_action( 'init', 'create_posttype' );
function create_posttype() {
register_post_type( 'recent_post_list',
array(
'labels' => array(
'name' => __( 'Recent post list' ),
'add_new' => 'Add New post',
'edit_item' => 'Edit post',
'new_item' => 'Add New post list item',
'view_item' => 'View post',
'search_items' => 'Search post',
'not_found' => 'No post found',
'not_found_in_trash' => 'No post found in Trash',
'singular_name' => __( 'Recent post list' )
),
'public' => true,'thumbnail' => true,
'has_archive' => true,'supports' => array('title','editor',
'author','thumbnail'),
'rewrite' => array('slug' => 'post_list'),
)
);
};
function baztag_func($atts) {//start front end
$args = array( 'post_type' => 'recent_post_list', 'orderby'=>'menu_order','order'=>'ASC' );
$loop = new WP_Query( $args );function word_count($string, $limit) { $words = explode(' ', $string);  return implode(' ', array_slice($words, 0, $limit));  }global $wpdb;$table_name = $wpdb->prefix . 'recent_post_list';$my_data = $wpdb->get_row("SELECT * FROM $table_name");$tml_w = $my_data->tml_width;$tml_h = $my_data->tml_height;if (function_exists('add_theme_support')){add_theme_support('post-thumbnails');add_image_size('tml', $tml_w, $tml_h, true);}$plugin_dir = plugins_url( '' , __FILE__ );
function font_end_css() {
	wp_register_style('web_css', plugin_dir_url(__FILE__).'css/default.css');
	wp_enqueue_style('web_css');	
}
font_end_css();
if ($loop->have_posts()){print '<div id="post_list">';
while ( $loop->have_posts() ) : $loop->the_post();
?>
<li style="height:<? print $tml_h; ?>px; width:100%; overflow:hidden;">
<?
$tml_url = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
if (strlen($tml_url) != 0)
{
?><div style="width:<? print $tml_w; ?>px; height:<? print $tml_h; ?>px;"class="tml_renginys"><? the_post_thumbnail('tml'); ?></div>
<?} else {?>
<div style="width:150px; height:150px;"class="tml_renginys">
<? print  '<img src ='.$plugin_dir.'/css/images/defailt_thumbnail.jpg>' ?>
</div>
<?}?>	<div style="height:<? print $tml_h - 23; ?>px; overflow:hidden;" id="content_right">
<div style="color:<? echo $my_data->name;?>; font-size:<? echo $my_data->text;?>px">
<strong>
<?php if (strlen(get_the_title()) > $my_data->title_characters_limit) {
echo substr(the_title($before = '', $after = '', FALSE), 0, $my_data->title_characters_limit) . '...'; } else {
the_title();
}?>
</strong>
<div style="font-size:<? echo $my_data->txt;?>px; color:<? echo $my_data->ex_color;?>">
<?php echo word_count(get_the_excerpt(), $my_data->ex_word_limit); ?> </div> </div> </div> <div class="btn" ><a style="background:<?echo $my_data->button_bg_color?>;color:<?echo $my_data->button_text_color?>;border-radius:3px;" href="<?php the_permalink() ?>"><? echo $my_data->button_text; ?></a></div>
<? if ($my_data->post_date == 'yes'){?> 
 <span class="date">Publication date: <?php the_time('Y-m-d'); ?>&nbsp;&nbsp;&nbsp;</span>
 <?}?>
<?if ($my_data->post_author == 'yes'){?> 
  <span class="date">Author: <?php the_author(); ?></span>
   <?}?>
</li>
<?
endwhile;print '</div>';// end front end
}else {print "<center>Not fount post in post list. <strong>Create post in Recent post list. </strong></center>";}};
add_shortcode('recent_post_list','baztag_func');
add_action('admin_menu','my_plugins_menu');
function my_plugins_menu() {
	add_options_page('Recent post list', 'Recent post list settings', 'manage_options', 'my-unique-identifier', 'my_plugins_options');
}
function my_plugins_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}	
global $wpdb;
$attributes = array($_POST["spalva"], $_POST["dydis"], $_POST["tekst"]);
$characters_numbers = "$attributes[0]$attributes[1]$attributes[2]$attributes[3]";
if ((strlen($characters_numbers)) != 0 ) {
$table_name = $wpdb->prefix . 'recent_post_list';
$sql = (
"UPDATE ".$table_name."
SET name = '".$_POST["spalva"]."',
text = '".$_POST["dydis"]."',
ex_color = '".$_POST["ex_color"]."',tml_width = '".$_POST["tml_w"]."',tml_height = '".$_POST["tml_h"]."',ex_word_limit = '".$_POST["ex_word"]."',
title_characters_limit = '".$_POST["title_characters_limit"]."',
txt = '".$_POST["tekst"]."',
button_bg_color = '".$_POST["button_bg_color"]."',
post_date = '".$_POST["post_date"]."',
button_text = '".$_POST["button_text"]."',
post_author = '".$_POST["post_author"]."',
button_text_color = '".$_POST["button_text_color"]."'
WHERE id = 1"
);
$wpdb->query($sql);
};
$table_name = $wpdb->prefix . 'recent_post_list';
$my_data = $wpdb->get_row("SELECT * FROM $table_name");
add_action('admin_enqueue_scripts', 'pw_load_scripts');
function admin_css() {
	wp_register_style('admin_styles', plugin_dir_url(__FILE__).'css/admin.css');
	wp_enqueue_style('admin_styles');
}
admin_css();
//load WP jQuery
    wp_enqueue_script('wp-color-picker');
    wp_enqueue_script('jquery-ui-spinner');	
    wp_enqueue_style( 'wp-color-picker' );


$db_in_quary_1 = $my_data->text;
$db_in_quary_2 = $my_data->txt;
?>
  
<script>
    jQuery(document).ready(function($) {   
        $('#mv_cr_section_color').wpColorPicker();
        $('#mv_cr_section_color1').wpColorPicker();
        $('#mv_cr_section_color2').wpColorPicker();
        $('#mv_cr_section_color3').wpColorPicker();

    });  
jQuery(function() {
jQuery( "#slider-range-min" ).slider({
range: "min",
value: <? echo $db_in_quary_1 ?>,
min: 1,
max: 40,
slide: function( event, ui ) {
jQuery( "#amount" ).val(ui.value );
}
});
jQuery( "#amount" ).val(  jQuery( "#slider-range-min" ).slider( "value" ));
});

jQuery(function() {
jQuery( "#slider-range-min2" ).slider({
range: "min",
value: <? echo $db_in_quary_2 ?>,
min: 1,
max: 20,
slide: function( event, ui ) {
jQuery( "#amount2" ).val(ui.value);
}
});
jQuery( "#amount2" ).val(  jQuery( "#slider-range-min2" ).slider( "value" ));
});
jQuery(function() {jQuery( "#spinner" ).spinner();});jQuery(function() {jQuery( "#spinner2" ).spinner();});jQuery(function() {jQuery( "#spinner3" ).spinner();});
jQuery(function() {jQuery( "#spinner4" ).spinner();});
</script>

<h1>Recent post list settings </h1>
Add this: <span style="color:green;">[recent_post_list] </span> shortcode to page or post content. 
 Or this php code to themes php file:  <i style="font-size:95%"><?php echo htmlentities("<?php do_shortcode('[recent_post_list]');?>" );?></i>
<br />
<br />
<br />

 <form action="?page=my-unique-identifier" method="POST"> 
Header text color:&nbsp; <input name="spalva" id="mv_cr_section_color" style="border:1px solid #dddddd; border-radius:5px;" value="<? echo $my_data->name; ?>"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
Excerpt text color:&nbsp; <input name="ex_color" style="border:1px solid #dddddd; border-radius:5px;"  id="mv_cr_section_color1" value="<? echo $my_data->ex_color; ?>">
<br />
<br />

Buttons background color:&nbsp; <input name="button_bg_color" style="border:1px solid #dddddd; border-radius:5px;" id="mv_cr_section_color2" value="<? echo $my_data->button_bg_color; ?>">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
Buttons text color:&nbsp; <input name="button_text_color" id="mv_cr_section_color3" style="border:1px solid #dddddd; border-radius:5px;" value="<? echo $my_data->button_text_color; ?>">
<br />
<br />
<br />
<div style="width:35%">
  <label for="amount">Header text size (px):</label>
<input type="text" id="amount" name="dydis"  readonly style="border:0; color:#f6931f; font-weight:bold;">
</p>
<div id="slider-range-min"></div>
</div>
<div style="width:35%">
  <label for="amount2">Excerpt text size (px):</label>
<input type="text" id="amount2" name="tekst"  readonly style="border:0; color:#f6931f; font-weight:bold;">
</p>
<div id="slider-range-min2"></div>
</div>
	  <br />	  Thumbnail size (px)	  <br />	  <br />	 Width:&nbsp;&nbsp; <input  id="spinner" name="tml_w" value="<? echo $my_data->tml_width; ?>">	 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	 Height:&nbsp;&nbsp; <input id="spinner2" name="tml_h"  value="<? echo $my_data->tml_height; ?>"> <br /> <br />  Excerpt word limit:&nbsp;&nbsp; <input id="spinner3" name="ex_word" value="<? echo $my_data->ex_word_limit; ?>"> 
   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  Title characters limit:&nbsp;&nbsp; <input id="spinner4" name="title_characters_limit" value="<? echo $my_data->title_characters_limit; ?>">  <br /> <br /> 
Buttons text:&nbsp;&nbsp;<input name="button_text" style="border:1px solid #dddddd; border-radius:5px;" id="autocomplete" value="<? echo $my_data->button_text; ?>"> 
 <br /> <br />
Show post date &nbsp;&nbsp;
<?if ($my_data->post_date == 'yes'){?>
<input type="checkbox" name="post_date" value="yes"checked>
<?} else {?>
<input type="checkbox" name="post_date" value="yes">
<?}?>
   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
Show post author &nbsp;&nbsp;
<?if ($my_data->post_author == 'yes'){?>
<input type="checkbox" name="post_author" value="yes"checked>
<?} else {?>
<input type="checkbox" name="post_author" value="yes">
<?}?>
<br /> 
<br />
 <input id="submit" class="button button-primary" type="submit" value="Save Changes" name="submit"> <br /> <br /><i style="font-size:95%">Plugin developers find is <a href="http://elektroninesvizijos.lt/" target="_blank">here</a>.</i>
<?
}
?>