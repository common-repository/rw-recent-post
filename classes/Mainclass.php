<?php
namespace Rwrpt\Main;
if ( !class_exists( 'rwrpt_recent_post_class' ) ) {
class rwrpt_recent_post_class
{   
    function __construct()
    {
        add_shortcode('rwrpt_recent_post', array(
            $this,
            'rwrpt_view_callback'
        ));
       
    }
    
    public function rwrpt_product_price_html( $product_id ) {
        return ( $product = wc_get_product( $product_id ) ) ? $product->get_price_html() : false;
    }

    public function rwrpt_view_callback($atts = [], $content = null, $tag = '')
    {
       
        $atts = array_change_key_case((array)$atts, CASE_LOWER);
        // override default attributes with user attributes
        $rw_atts = shortcode_atts(array(
            'title' => 'Recent Post',
            'post_type' => 'post',
            'number_of_post' => 10,
            'image' => 'false',
            'marquee' => 'true',
            'posted_date' => 'true',
            'view_style'=>''
        ) , $atts, $tag);
        $args = array(
            'post_type' => $rw_atts['post_type'],
            'post_status' => 'publish',
            'posts_per_page' => $rw_atts['number_of_post'],
            'orderby' => 'date',
            'order' => 'DESC',
        );

        $post = get_posts($args);
        $marquee = '';
        $marqueeEnd = '';
        $product_recent='';  
        
        $posted_date = '';
        $classul='';
        $divID='';
        if($rw_atts['view_style']=='list'){
        	$classul='rwrpt_cards rwrpt_cards_list';
        	$divID='rwrpt_listlayout';
        }elseif($rw_atts['view_style']=='grid'){
        	$classul='rwrpt_cards';
        	$divID='rwrpt_gridlayout';
        }
        $htm = '<div class="rw_post_list rwrpt_main" id="'.$divID.'"><h3>'.$rw_atts['title'].'</h3><ul id="rwrecentpost" class="'.$classul.'">' . $marquee;
        $image = '';

        if (count($post)!=0) {
               
        foreach ($post as $key => $postvalue)
        {
        	
        	if (has_post_thumbnail( $postvalue->ID )) {
        		$thumburl = wp_get_attachment_url( get_post_thumbnail_id($postvalue->ID), 'thumbnail' );
        	}else{
        		$thumburl=plugins_url().'/rw-recent-post/img/image-default.jpg';
        	}
        	$content = $postvalue->post_content; //contents saved in a variable

        	if ( class_exists( 'WooCommerce' ) && $rw_atts['post_type'] == 'product') {
                $product_recent=$this->rwrpt_product_price_html($postvalue->ID).'&nbsp;<a href="'.esc_url( get_permalink($postvalue->ID)).'" class="rwrpt_btn rwrpt_card_btn">Buy now</a>';
        }else{
        	$product_recent='<a href="'.esc_url( get_permalink($postvalue->ID) ).'">Read More</a>';
        } 
         if ($rw_atts['posted_date'] == 'true')
        {
            $posted_date = ' <i>Posted date: ' . get_the_date('dS M Y', $postvalue->ID) . '</i>';

        }

        	if($rw_atts['view_style']=='list'||$rw_atts['view_style']=='grid'){
        		
        		$htm.='
    <li class="rwrpt_cards_item">
      <div class="rwrpt_card">
        <div class="rwrpt_card_image">
            <img src="'.$thumburl.'" alt="'.$postvalue->post_title.'">
        </div>
        <div class="rwrpt_card_content">
          <p class="rwrpt_card_title"><a href="'.esc_url( get_permalink($postvalue->ID) ).'">'.$postvalue->post_title.'</a> &nbsp;<div>'.$posted_date.'</div></p>
          <p class="rwrpt_card_text">'.substr(strip_tags($content), 0, 100).'<a href="'.esc_url( get_permalink($postvalue->ID) ).'">[...]</a></p>
           '.$product_recent.'
        </div>
      </div>
    </li>   
  ';
   }else{
	$htm .= '<li><a href="'.esc_url( get_permalink($postvalue->ID) ).'">' . " " . $postvalue->post_title .'</a> &nbsp; '.$product_recent.''. $posted_date . '</li>';
			}
        }
      }else{
        $htm.='<li>'.$rw_atts['title'].' not found...</li>';
      }
        $htm.= $marqueeEnd . '</ul></div>';
	return $htm;
    }
}

}
?>