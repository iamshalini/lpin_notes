<?php
add_action('wp_enqueue_scripts', 'my_theme_enqueue_styles');
function my_theme_enqueue_styles()
{
    $parenthandle = 'twenty-twenty-one-style';  
    $theme = wp_get_theme();
    wp_enqueue_style($parenthandle, get_template_directory_uri() . '/style.css', array() ,  
    $theme->parent()
        ->get('Version'));
    wp_enqueue_style('custom-style', get_stylesheet_uri() , array(
        $parenthandle
    ) , $theme->get('Version')  
    );
    wp_enqueue_style('responsive', get_theme_file_uri() . '/assets/css/responsive.css');
}

//Register Sidebar
 
add_action('widgets_init', 'footer_sidebar1');
function footer_sidebar1()
{
    $args = array(
        'name' => 'footer sidebar 1',
        'id' => 'footer_sidebar1',
        'class' => '',
        'before_title' => '<h2 class="widgettitle">',
        'after_title' => '</h2>'
    );

    register_sidebar($args);

}

add_action('widgets_init', 'footer_sidebar2');
function footer_sidebar2()
{
    $args = array(
        'name' => 'footer sidebar 2',
        'id' => 'footer_sidebar2',
        'class' => '',
        'before_title' => '<h2 class="widgettitle">',
        'after_title' => '</h2>'
    );

    register_sidebar($args);

}

add_filter('manage_edit-bill_columns', 'custom_add_new_columns');
function custom_add_new_columns( $columns ){
    $columns['author_email'] = 'Author';
    return $columns;
}
add_action('manage_bill_posts_custom_column', 'custom_manage_new_columns', 10, 2);
function custom_manage_new_columns( $column_name, $id ){
    if ('author_email'==$column_name){
     $current_item = get_post($id);
     $author_id = $current_item->post_author;
     $author_email = get_the_author_meta( 'user_email', $author_id);
     echo '<a href="mailto:'.$author_email.'">'.$author_email.'</a>';
    }
}

 add_filter('manage_edit-legislators_columns', 'custom_add_new_columns_legislators');
function custom_add_new_columns_legislators( $columns ){
    $columns['author_email'] = 'Author';
    return $columns;
}
add_action('manage_legislators_posts_custom_column', 'custom_manage_new_columns_legislators', 10, 2);
function custom_manage_new_columns_legislators( $column_name, $id ){
    if ('author_email'==$column_name){
     $current_item = get_post($id);
     $author_id = $current_item->post_author;
     $author_email = get_the_author_meta( 'user_email', $author_id);
     echo '<a href="mailto:'.$author_email.'">'.$author_email.'</a>';
    }
}
 
function voting_add_custom_js_file_to_admin()
{
    // Register and enqueue the script we need for load more
    wp_register_script('custom-script', get_theme_file_uri() . '/assets/js/voting-script.js', array(
        'jquery'
    ));
    wp_enqueue_script('custom-script');
	
	 wp_register_script('datatable-script', get_theme_file_uri() . '/assets/js/jquery.dataTables.min.js', array(
        'jquery'
    ));
	 wp_enqueue_script('datatable-script');
	 
    wp_enqueue_style('backend-style', get_theme_file_uri() . '/assets/css/voting.css');
	 wp_enqueue_style('dataTables-style', get_theme_file_uri() . '/assets/css/dataTables.min.css');
    wp_enqueue_style('font-style', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css');

    // Localize the script so we can access tthe variables in PHP
    wp_localize_script('custom-script', 'ajax_load_more_object', array(
        'ajax_url' => admin_url('admin-ajax.php') ,
    ));

}

add_action('admin_enqueue_scripts', 'voting_add_custom_js_file_to_admin');

add_action('init', 'wpb_register_cpt_bill');

function wpb_register_cpt_bill()
{

    $labels = array(
        'name' => _x('Bill', 'bill') ,
        'singular_name' => _x('bill', 'bill') ,
        'add_new' => _x('Add New', 'bill') ,
        'add_new_item' => _x('Add New Bill', 'bill') ,
        'edit_item' => _x('Edit Bill', 'bill') ,
        'new_items' => _x('New Bill', 'bill') ,
        'view_items' => _x('View Bill', 'bill') ,
        'search_items' => _x('Search Bill', 'bill') ,
        'not_found' => _x('No Bill found', 'bill') ,
        'not_found_in_trash' => _x('No bill found in Trash', 'bill') ,
        'parent_items_colon' => _x('Parent Bill:', 'bill') ,
        'menu_name' => _x('Bill', 'bill') ,
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => false,
        'supports' => array(
            'title',
            'thumbnail',
            'editor',
            'editor',
            'page-attributes'
        ) ,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'has_archive' => true,
        'query_var' => true,
        'can_export' => true,
        'menu_icon' => 'dashicons-screenoptions',
        'rewrite' => true,
        'capability_type' => 'post'
    );
    register_post_type('bill', $args);
    flush_rewrite_rules();
}

function bills()
{
    register_taxonomy('bills', //The name of the taxonomy. Name should be in slug form (must not contain capital letters or spaces).
    'bill', //post type name
    array(
        'hierarchical' => true,
        'label' => 'Category', //Display name
        'query_var' => true,
		 'show_admin_column' => true,

        'rewrite' => array(
            'slug' => 'bills', // This controls the base slug that will display before each term
            'hierarchical' => true,
            'with_front' => false
            // Don't display the category base before
            
        )
    ));
}
add_action('init', 'bills');

//hooks to change default post titles
function wpb_change_title_text_bill($title)
{
    $screen = get_current_screen();

    if ('bill' == $screen->post_type)
    {
        $title = 'Bill Name';
    }

    return $title;
}

add_filter('enter_title_here', 'wpb_change_title_text_bill');

add_action('init', 'wpb_register_cpt_legislators');

function wpb_register_cpt_legislators()
{

    $labels = array(
        'name' => _x('Legislator', 'legislators') ,
        'singular_name' => _x('Legislator', 'legislators') ,
        'add_new' => _x('Add New', 'legislators') ,
        'add_new_item' => _x('Add New Legislator', 'legislators') ,
        'edit_item' => _x('Edit Legislator', 'legislators') ,
        'new_items' => _x('New Legislator', 'legislators') ,
        'view_items' => _x('View Legislator', 'legislators') ,
        'search_items' => _x('Search Legislator', 'legislators') ,
        'not_found' => _x('No Legislator found', 'legislators') ,
        'not_found_in_trash' => _x('No Legislator found in Trash', 'legislators') ,
        'parent_items_colon' => _x('Parent Legislator:', 'legislators') ,
        'menu_name' => _x('Legislator', 'legislators') ,
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => false,
        'supports' => array(
            'title',
            'thumbnail',
            'editor',
            'editor',
            'page-attributes'
        ) ,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'has_archive' => true,
        'query_var' => true,
        'can_export' => true,
        'menu_icon' => 'dashicons-screenoptions',
        'rewrite' => true,
        'capability_type' => 'post'
    );
    register_post_type('legislators', $args);
    flush_rewrite_rules();
}

function legislators_texonomy()
{
    register_taxonomy('legislators_texonomy', //The name of the taxonomy. Name should be in slug form (must not contain capital letters or spaces).
    'legislators', //post type name
    array(
        'hierarchical' => true,
        'label' => 'Category', //Display name
        'query_var' => true,
		 'show_admin_column' => true,
    'rewrite' => array(
            'slug' => 'legislators_texonomy', // This controls the base slug that will display before each term
            'hierarchical' => true,
            'with_front' => false
            // Don't display the category base before
            
        )
    ));
}
add_action('init', 'legislators_texonomy');

// hooks to change post default titles
function wpb_change_title_text($title)
{
    $screen = get_current_screen();

    if ('legislators' == $screen->post_type)
    {
        $title = 'Legislator Name';
    }

    return $title;
}

add_filter('enter_title_here', 'wpb_change_title_text');

function km_change_featured_image_metabox_title()
{
    remove_meta_box('postimagediv', 'legislators', 'side');
    add_meta_box('postimagediv', __('Image', 'km') , 'post_thumbnail_meta_box', 'legislators', 'side');
}
add_action('do_meta_boxes', 'km_change_featured_image_metabox_title');

function km_change_featured_image_text($content)
{

    if ('legislators' === get_post_type())
    {
        $content = str_replace('Set featured image', __('Set Image', 'km') , $content);
        $content = str_replace('Remove featured image', __('Remove Image', 'km') , $content);
    }

    return $content;
}
add_filter('admin_post_thumbnail_html', 'km_change_featured_image_text');
/******* Call All JS & CSS files - Code Start ******/

function wpdocs_theme_name_scripts()
{
    wp_enqueue_script('custom-jquery', 'https://code.jquery.com/jquery-3.3.1.min.js', array() , '1.0.0', true);
    wp_enqueue_script('custom', get_theme_file_uri('/assets/js/custom.js') , array() , '1.0.0', true);

}
add_action('wp_enqueue_scripts', 'wpdocs_theme_name_scripts');

function get_selected_cat()
{
    $bill_year = $_POST['bill_year'];
    $selected_catId = $_POST['selected_catId'];
    if (empty($selected_catId) && !empty($bill_year))
    {
        $args = array(
            'post_type' => 'bill',
            'posts_per_page' => - 1,
            'post_status' => 'publish',
            'meta_query' => array(
                array(
                    'key' => 'bill_year',
                    'value' => $bill_year,
                    'compare' => '='
                )
            )

        );
    }
    elseif (empty($bill_year) && empty($selected_catId))
    {
        $args = array(
            'post_type' => 'bill',
            'posts_per_page' => 10
        );
    }
    elseif (!empty($bill_year))
    {
        $args = array(
            'post_type' => 'bill',
            'posts_per_page' => - 1,
            'post_status' => 'publish',
            'tax_query' => array(
                array(
                    'taxonomy' => 'bills',
                    'field' => 'term_id',
                    'terms' => $selected_catId
                ) ,
            ) ,

            'meta_query' => array(
                array(
                    'key' => 'bill_year',
                    'value' => $bill_year,
                    'compare' => '='
                )
            )

        );
    }

    else
    {
        $args = array(
            'post_type' => 'bill',
            'posts_per_page' => - 1,
            'post_status' => 'publish',
            'tax_query' => array(
                array(
                    'taxonomy' => 'bills',
                    'field' => 'term_id',
                    'terms' => $selected_catId
                ) ,
            ) ,
        );
    }
    $the_query = new WP_Query($args);
    if ($the_query->have_posts()):
        while ($the_query->have_posts()):
            $the_query->the_post();
?>

 <tr class="border-b">
     <?php $field = get_field_object('bill_year');
            $value = $field['value'];
            $label = $field['choices'][$value];
            $title = get_the_title(); ?>
              <td>
                   <input type="hidden" id="fname" name="fname" value="<?php echo esc_attr($value); ?>">
                  <div class="vote__consicder__td">
                            <p> <?php echo $title; ?></p>
                        <?php $bill_term = get_the_terms($post->ID, 'bills'); ?>
                            <?php foreach ($bill_term as $brand): ?>
                         <span> <?php echo $brand->name; ?> Roll Call #: 05    </span>
                      <?php
            endforeach ?>       
                          <a href="<?php the_permalink(); ?>">Read More Details</a>
                          </div>
                        </td>
                    <td>
                    <div class="box" data-animate="false">
                   <div class="circle" data-dots="20" data-percent="12" style="--bgColor: #f3c816"></div>
                            <div class="text">
                              <h2>68</h2>
                              <!-- <small>HTML</small> -->
                            </div>
                          </div>
                        </td>
                  <td>
                    <div class="box" data-animate="false">
                      <div class="circle" data-dots="20" data-percent="12" style="--bgColor: #f3c816"></div>
                            <div class="text">
                              <h2>18</h2>
                              <!-- <small>HTML</small> -->
                            </div>
                          </div>
                        </td>
                     
      </tr>
         
         
<?php
        endwhile;
        wp_reset_postdata();
    else: ?>
<p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
<?php
    endif; ?>
 
<?php
    exit; // leave ajax call
    
}
 
add_action('wp_ajax_get_selected_cat', 'get_selected_cat');
add_action('wp_ajax_nopriv_get_selected_cat', 'get_selected_cat');

function get_selected_year_bill()
{
   $args = array(
                        'post_type' => 'legislators',
                        'posts_per_page' => -1 );
                        $wp_query = new WP_Query( $args);

                        $post_count = $wp_query->post_count;
                        $total_leg = $post_count;  
             
  $args = array(
         'post_type' => 'bill',
                            'posts_per_page' => -1 ,
                            'post_status' => 'publish',
                              'meta_query' => array(
                                array(
                                'key' => 'social_type',
                                'value' => 'social',
                                'compare' => '='
                                )
                            )
                            );
                                $wp_query = new WP_Query( $args);

                                $post_count = $wp_query->post_count;

                                $number_of_socialbill = $post_count;  
                                $the_query = new WP_Query( $args ); 
                                $Sbilid = wp_list_pluck( $the_query->posts, 'ID' );

                                $socialList = implode(', ', $Sbilid);
 
                      $eargs = array(
                            'post_type' => 'bill',
                            'posts_per_page' => -1 ,
                            'post_status' => 'publish',
                              'meta_query' => array(
                              array(
                                'key' => 'social_type',
                                'value' => 'economic',
                                'compare' => '='
                                )
                            )
                            );
                              $wp_query = new WP_Query( $eargs);

                              $post_count = $wp_query->post_count;

                              $number_of_economicbill = $post_count;  
                              $the_query = new WP_Query( $eargs ); 
                              $Ebilid = wp_list_pluck( $the_query->posts, 'ID' );
                              $economicList = implode(', ', $Ebilid);




    $bill_year = $_POST['bill_year'];
    $selected_catId = $_POST['selected_catId'];
    $social_type =$_POST['social_type'];
  $socialvalues =$_POST['social'];
	$bilid =$_POST['billids'];
  if (!empty($bill_year) && !empty($selected_catId) && !empty($social_type ) )
    {
    
    $args = array(
            'post_type' => 'bill',
            'posts_per_page' => - 1,
            'post_status' => 'publish',
            'tax_query' => array(
                array(
                    'taxonomy' => 'bills',
                    'field' => 'term_id',
                    'terms' => $selected_catId
                )  
            ), 
   'meta_query'     => array(
    'relation'  => 'AND',
     array (
       'key'     => 'date_&_time',
       'value'   => $bill_year,
       'compare' => 'LIKE'
     ),
     array (
       'key'     => 'social_type',
       'value'   =>  $social_type,
       'compare' => '='
     ) 
    )
    
 );

  }

 elseif( !empty($bill_year ) && !empty($social_type) )
 {
     
         $args = array(
            'post_type' => 'bill',
            'posts_per_page' => - 1,
            'post_status' => 'publish',
       'meta_query'     => array(
    'relation'  => 'AND',
     array (
       'key'     => 'date_&_time',
       'value'   => $bill_year,
       'compare' => '%LIKE%'
     ),
     array (
       'key'     => 'social_type',
       'value'   =>  $social_type,
       'compare' => '='
     ) 
    )
    
);

    
  }
    elseif( !empty($bill_year ) || !empty($social_type) )
 { 
      
      if (!empty($selected_catId)){
           
          $args = array(
            'post_type' => 'bill',
            'posts_per_page' => - 1,
            'post_status' => 'publish',
      
         
    'meta_query'     => array(
    'relation'  => 'OR',
     array (
       'key'     => 'date_&_time',
       'value'   => $bill_year,
       'compare' => 'LIKE'
     ),
     array (
      'key'     => 'social_type',
       'value'   =>  $social_type,
       'compare' => '='
     ) 
    ),
        'tax_query' => array(
            'relation'  => 'OR',
           array(
                 'taxonomy' => 'bills',
                  'field' => 'term_id',
                  'terms' => $selected_catId
                )  
      )  
);
      }

       else{
         
        $args = array(
            'post_type' => 'bill',
            'posts_per_page' => - 1,
            'post_status' => 'publish',
      
         
    'meta_query'     => array(
    'relation'  => 'OR',
     array (
      'key'     => 'date_&_time',
       'value'   => $bill_year,
       'compare' => 'LIKE'
     ),
     array (
      'key'     => 'social_type',
       'value'   =>  $social_type,
       'compare' => '='
     ) 
    ),
        
);
  }
         
  }
  elseif( !empty($selected_catId ) )
 {
     
       $args = array(
            'post_type' => 'bill',
            'posts_per_page' => - 1,
            'post_status' => 'publish',
          'tax_query' => array(
            array(
                 'taxonomy' => 'bills',
                  'field' => 'term_id',
                  'terms' => $selected_catId
                )  
      )  
    );

    
  }
    else
      {
        $args = array(
            'post_type' => 'bill',
            'posts_per_page' => - 1,
            'post_status' => 'publish',
        );
    }

  $the_query = new WP_Query($args);

    if ($the_query->have_posts()):
        while ($the_query->have_posts()):
            $the_query->the_post();
?>

 <tr class="border-b">

   <?php

   $field = get_field_object('bill_year');
            $value = $field['value'];
            $label = $field['choices'][$value];
            $title = get_the_title(); ?>
              <td>
                   <input type="hidden" id="fname" name="fname" value="<?php echo esc_attr($value); ?>">
                  <div class="vote__consicder__td">
                            <p> <?php echo $title; ?></p>
                        <?php $bill_term = get_the_terms($post->ID, 'bills'); ?>
                            <?php foreach ($bill_term as $brand): ?>
                         <span> <?php echo $brand->name; ?> Roll Call #: 05    </span>
                      <?php
            endforeach ?>       
                          <a href="<?php the_permalink(); ?>">Read More Details</a>
                          </div>
                        </td>
                
      <td>
                    <?php   $billid = get_the_ID();   
                     global $wpdb, $table_prefix;   
                        if (in_array($billid, $Sbilid))
                        {
                       
                        $countsocialSupport = $wpdb->get_var('SELECT COUNT(*) FROM wp_vote2 WHERE bill_Id = '.$billid); 
                        $persocial=  round ($countsocialSupport * 100 /  $total_leg); 
                       
                        ?>
                          <div class="box" data-animate="false">
                          <div class="circle" data-dots="20" data-percent="<?php echo $persocial;?>" style="--bgColor: #f3c816"></div>
                            <div class="text">
                                <h2><?php echo $persocial;?></h2>
                              <input type="hidden" class="socialbill" value="<?php echo $persocial;?>">
                            </div>
                          </div>
                          <?php 
                        }else{?>
                          <div class="box" data-animate="false">
                          <div class="circle" data-dots="20" data-percent="0" style="--bgColor: #f3c816"></div>
                            <div class="text">
                              <input type="hidden" class="emptybill" value="-">
                                <h2>-</h2>
                            <!-- <small>HTML</small> -->
                            </div>
                          </div>
                    <?php   }?>
                        </td>
                 <td>
                    <?php  if (in_array($billid, $Ebilid))
                    {
                    $counteconomicSupport = $wpdb->get_var('SELECT COUNT(*) FROM wp_vote2 WHERE bill_Id = '.$billid );
                              
                
                     $pereconomic=  round ($counteconomicSupport * 100 /  $total_leg); 

                    ?>
                    <div class="box" data-animate="false">
                    <div class="circle" data-dots="20" data-percent="<?php echo $pereconomic;?>" style="--bgColor: #f3c816"></div>
                    <div class="text">
                    <h2><?php echo $pereconomic;?></h2>
                    <!-- <small>HTML</small> -->
                    </div>
                    </div>
                    <?php  
                    }else{?>
                    <div class="box" data-animate="false">
                    <div class="circle" data-dots="20" data-percent="0" style="--bgColor: #f3c816"></div>
                    <div class="text">
                    <h2>-</h2>
                    <!-- <small>HTML</small> -->
                    </div>
                    </div>

                 <?php   }?> 
                       </td>
                       
      </tr>
         
<?php
        endwhile;
        wp_reset_postdata();
    else: ?>
<p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
<?php
    endif; ?>
 
<?php
    exit;
}
add_action('wp_ajax_get_selected_year_bill', 'get_selected_year_bill');
add_action('wp_ajax_nopriv_get_selected_year_bill', 'get_selected_year_bill');


//single leg bill year filter 
function get_single_leg_selected_year_bill()
{
    $bill_year = $_POST['bill_year'];
    $leg_id = $_POST['leg_id'];
    //print_r($bill_year);
      $args = array(
            'post_type' => 'bill',
            'posts_per_page' => - 1,
            'post_status' => 'publish',
            'meta_query' => array(
                array(
                    'key' => 'date_&_time',
                    'value' => $bill_year,
                    'compare' => 'LIKE'
                )
            )

        );
    
    $the_query = new WP_Query($args);
       // echo $wpdb->last_query;

    if ($the_query->have_posts()):
        while ($the_query->have_posts()):
          $the_query->the_post();
           $bid = get_the_ID();

          ?>

                 <tr class="border-b">
                    <td>
                      <div class="vote__consicder__td">
                    <?php $title = get_the_title();?>
                        <p><?php echo $title;?> </p>

                      <?php   $date = get_field( 'date_&_time')  ?>
                     
                         <span>year: <?php echo $date; ?> </span>   

  
                        <?php if ( $roll_call = get_field( 'roll_call' ) ) : ?>
                          <span>House Roll Call #:  <?php echo $roll_call; ?></span>   
                         <?php endif; ?>  
                  
                   <a href="<?php echo get_permalink()?>">Read More Details</a>
                      </div>

                    </td>
         <td class="text-center"> 
                   <?php global $wpdb, $table_prefix;
                    $results = $wpdb->get_results('SELECT vote_support FROM '.$table_prefix.'vote2 WHERE legislator_Id = '.$leg_id.' AND bill_Id= '.$bid  );

                     if (!empty($results))
                  { 
                      foreach ($results as $row){
                      $support= $row->vote_support;
                        if ($support == '1'){?>
                    <img src="<?php echo site_url(); ?>/wp-content/uploads/2022/02/vote_green.png" alt="">
                     <?php   }
                          else{?>
                    <img src="<?php echo site_url(); ?>/wp-content/uploads/2022/02/vote_red.png" alt="">    
                        <?php  }
                       }

                    }
                    else{?>
                      <img src="<?php echo site_url(); ?>/wp-content/uploads/2022/02/dash.png" alt=""> 
              <?php      }

         ?>
         </td>
                  </tr>
 
<?php

            endwhile;
            wp_reset_postdata();

            else: ?>

<p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
<?php endif;  
exit;
}
add_action('wp_ajax_get_single_leg_selected_year_bill', 'get_single_leg_selected_year_bill');
add_action('wp_ajax_nopriv_get_single_leg_selected_year_bill', 'get_single_leg_selected_year_bill');

//End single leg bill year filter 




// legistor
 
function get_selected_legistor_dist()
{

 $args = array(
         'post_type' => 'bill',
                            'posts_per_page' => -1 ,
                            'post_status' => 'publish',
                              'meta_query' => array(
                                array(
                                'key' => 'social_type',
                                'value' => 'social',
                                'compare' => '='
                                )
                            )
                            );
                                $wp_query = new WP_Query( $args);

                                $post_count = $wp_query->post_count;

                                $number_of_socialbill = $post_count;  
                                $the_query = new WP_Query( $args ); 
                                $Sbilid = wp_list_pluck( $the_query->posts, 'ID' );

                                $socialList = implode(', ', $Sbilid);
 
                      $eargs = array(
                            'post_type' => 'bill',
                            'posts_per_page' => -1 ,
                            'post_status' => 'publish',
                              'meta_query' => array(
                              array(
                                'key' => 'social_type',
                                'value' => 'economic',
                                'compare' => '='
                                )
                            )
                            );
                              $wp_query = new WP_Query( $eargs);

                              $post_count = $wp_query->post_count;

                              $number_of_economicbill = $post_count;  
                              $the_query = new WP_Query( $eargs ); 
                              $Ebilid = wp_list_pluck( $the_query->posts, 'ID' );
                              $economicList = implode(', ', $Ebilid);




    $selectedlegistor_dist = $_POST['selectedlegistor_dist'];
    $selectedparty_label= $_POST['selectedpartylabel'];
    $selected_chamber = $_POST['selectedchamber'];

    if (!empty($selectedlegistor_dist) && !empty($selectedparty_label) && !empty($selected_chamber ) )
    {
         
    $args = array(
            'post_type' => 'legislators',
            'posts_per_page' => - 1,
            'post_status' => 'publish',

         'tax_query' => array(
                array(
                    'taxonomy' => 'legislators_texonomy',
                    'field' => 'term_id',
                    'terms' => $selected_chamber
                )  
            ), 
   'meta_query'     => array(
    'relation'  => 'AND',
     array (
       'key'     => 'district',
       'value'   => $selectedlegistor_dist,
       'compare' => '='
     ),
     array (
       'key'     => 'choose_party_label',
       'value'   =>  $selectedparty_label,
       'compare' => '='
     ) 
    )
    

 );

  }

 elseif( !empty($selectedlegistor_dist ) && !empty($selectedparty_label) )
 {
    
         $args = array(
            'post_type' => 'legislators',
            'posts_per_page' => - 1,
            'post_status' => 'publish',
      
         
    'meta_query'     => array(
    'relation'  => 'AND',
     array (
       'key'     => 'district',
       'value'   => $selectedlegistor_dist,
       'compare' => '='
     ),
     array (
       'key'     => 'choose_party_label',
       'value'   =>  $selectedparty_label,
       'compare' => '='
     ) 
    )
    
);

    
  }
    elseif( !empty($selectedlegistor_dist ) || !empty($selectedparty_label) )
 { 
      if (!empty($selected_chamber)){
 
            $args = array(
            'post_type' => 'legislators',
            'posts_per_page' => - 1,
            'post_status' => 'publish',
      
         
    'meta_query'     => array(
    'relation'  => 'OR',
     array (
       'key'     => 'district',
       'value'   => $selectedlegistor_dist,
       'compare' => '='
     ),
     array (
       'key'     => 'choose_party_label',
       'value'   =>  $selectedparty_label,
       'compare' => '='
     ) 
    ),
        'tax_query' => array(
            'relation'  => 'OR',
           array(
                    'taxonomy' => 'legislators_texonomy',
                    'field' => 'term_id',
                    'terms' => $selected_chamber
                )  
      )  
);
      }

       else{
        $args = array(
            'post_type' => 'legislators',
            'posts_per_page' => - 1,
            'post_status' => 'publish',
      
         
    'meta_query'     => array(
    'relation'  => 'OR',
     array (
       'key'     => 'district',
       'value'   => $selectedlegistor_dist,
       'compare' => '='
     ),
     array (
       'key'     => 'choose_party_label',
       'value'   =>  $selectedparty_label,
       'compare' => '='
     ) 
    ),
        
);
       }
         
  }
  elseif( !empty($selected_chamber ) )
 {
       $args = array(
            'post_type' => 'legislators',
            'posts_per_page' => - 1,
            'post_status' => 'publish',
          'tax_query' => array(
           array(
                    'taxonomy' => 'legislators_texonomy',
                    'field' => 'term_id',
                    'terms' => $selected_chamber
                )  
      )  
    );

    
  }
    else
    {
        $args = array(
            'post_type' => 'legislators',
            'posts_per_page' => - 1,
            'post_status' => 'publish',
        );
    }

   
    $the_query = new WP_Query($args);

    if ($the_query->have_posts()):
        while ($the_query->have_posts()):
            $the_query->the_post();
            $key = get_post_meta($post->ID, '$selectedlegistor_dist', true);
    ?>

        <tr class="border-b">
        <td>
            <div class="first-td">
            <?php $title = get_the_title();
                if (has_post_thumbnail()): ?>
                <?php $imageurl = wp_get_attachment_url(get_post_thumbnail_id($post->ID) , 'thumbnail'); ?>
                <img class="img-circle" src="<?php echo $imageurl; ?>" />
                <?php
                else: ?>
                <img class="img-circle" src="<?php echo site_url(); ?>/wp-content/uploads/2022/01/download.jpeg "  />
                <?php
                endif; ?>

            <span><?php echo $title; ?> </span>
            </div>
        </td>
        <td>
            <?php if ($district = get_field('district')): ?>
            <?php echo $district; ?>
            <?php
            endif; ?>
        </td>
        <td>
            <?php if ($party_labels = get_field('choose_party_label')): ?>
            <?php echo esc_html($party_labels[0]); ?>
            <?php
            endif; ?>
        </td>

            <td>
                <?php   
                   $legid = get_the_ID();   
                     global $wpdb, $table_prefix;  
                    $countsocialleg = $wpdb->get_var('SELECT COUNT(*) FROM wp_vote2 WHERE legislator_Id = '.$legid.'  AND bill_Id IN ('.$socialList.')' ); 

                                    
                                   if ($number_of_socialbill != '0'){
                                     $persocialleg =  round ($countsocialleg * 100 / $number_of_socialbill); 
                                   }else {
                                     $persocialleg = '0';
                                   }
     ?>
                             <div class="box" data-animate="false">
                              <div class="circle" data-dots="20" data-percent="<?php echo $persocialleg;?>" style="--bgColor: #f3c816"></div>
                              <div class="text">
                                <h2><?php echo $persocialleg;?></h2>
                                <!-- <small>HTML</small> -->
                              </div>
                            </div>
                        
                          </td>
         <td>
                 <?php   
                  
                          $counteccoleg = $wpdb->get_var('SELECT COUNT(*) FROM wp_vote2 WHERE legislator_Id = '.$legid.' AND bill_Id IN ('.$economicList.')' ); 
                                 
                                   if ($number_of_economicbill != '0'){
                                    $pereccoleg =  round ($counteccoleg * 100 / $number_of_economicbill); 
                                  
                                  }else{
                                    $pereccoleg = '0';
                                  }

                                  ?>
                            <div class="box" data-animate="false">
                              <div class="circle" data-dots="20" data-percent="<?php echo $pereccoleg;?>" style="--bgColor: #f3c816"></div>
                              <div class="text">
                                <h2><?php echo $pereccoleg;?></h2>
                                <!-- <small>HTML</small> -->
                              </div>
                            </div>
                          </td>
                          <td>
                            <?php  
                              $ynoverallsupport =  $number_of_economicbill + $number_of_socialbill;
                                  $overallsupport =  $counteccoleg + $countsocialleg;
                                   if ($ynoverallsupport != '0'){
                                  $peroverallSupport= round ($overallsupport * 100 / $ynoverallsupport); 
                                }else{
                                   $peroverallSupport= '0';
                                }
                            ?>
                            <div class="box" data-animate="false">
                              <div class="circle" data-dots="20" data-percent="<?php echo $peroverallSupport;?>" style="--bgColor: #f3c816"></div>
                              <div class="text">
                                <h2><?php echo $peroverallSupport;?></h2>
                                <!-- <small>HTML</small> -->
                              </div>
                            </div>
                          </td> 

        </tr>
         
<?php
        endwhile;
        wp_reset_postdata();
    else: ?>
<p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
<?php
    endif; ?>
 
<?php
    exit; // leave ajax call
    
}

// Fire AJAX action for both logged in and non-logged in users
add_action('wp_ajax_get_selected_legistor_dist', 'get_selected_legistor_dist');
add_action('wp_ajax_nopriv_get_selected_legistor_dist', 'get_selected_legistor_dist');

 
 
//custome page in admin
//
//Bill Category Selection
function get_selected_bill_cat()
{
    $selected_bill_catId = $_POST['selected_bill_catId'];
    $args = array(
        'post_type' => 'bill',
        'posts_per_page' => - 1,
        'post_status' => 'publish',
        'tax_query' => array(
            array(
                'taxonomy' => 'bills',
                'field' => 'term_id',
                'terms' => $selected_bill_catId
            ) ,
        ) ,
    );

    $the_query = new WP_Query($args);

    if ($the_query->have_posts()): ?>
             <option data-id="-1" value="">Bill</option>
         <?php
        while ($the_query->have_posts()):
            $the_query->the_post();
            $bills_title = get_the_title();
            echo '<option id="selection" value="' . get_the_ID() . '">' . $bills_title . '</option>';

        endwhile;
        wp_reset_postdata(); ?>
   
                <?php
    else: ?>
<p></p>
<?php
    endif; ?>
 
<?php
    exit; // leave ajax call
    
}

// Fire AJAX action for both logged in and non-logged in users
add_action('wp_ajax_get_selected_bill_cat', 'get_selected_bill_cat');
add_action('wp_ajax_nopriv_get_selected_bill_cat', 'get_selected_bill_cat');

//Legislator Category Selection
function get_legislator_cat()
{
    $args = array(
        'post_type' => 'legislators',
        'posts_per_page' => - 1,
        'post_status' => 'publish',
    );
    $the_query = new WP_Query($args);

    if ($the_query->have_posts()): ?>
           <?php
        $legislator_terms = get_terms(array(
            'taxonomy' => 'legislators_texonomy',
            'hide_empty' => true,
        )); ?>
                 <option data-id="-1" value="">Legislator Type</option>
                <?php foreach ($legislator_terms as $key => $value)
        {
            echo '<option value="' . $value->term_id . '" >' . $value->name . '</option>';
        }

        wp_reset_postdata(); ?>
     
<?php
    else: ?>
<p>No data </p>
<?php
    endif; ?>
 
<?php
    exit;
}

add_action('wp_ajax_get_legislator_cat', 'get_legislator_cat');
add_action('wp_ajax_nopriv_get_legislator_cat', 'get_legislator_cat');

//Legislator Category Selection
function get_selected_legislator_cat()
{
    $selected_legislator_catId = $_POST['selected_legislator_catId'];
    $args = array(
        'post_type' => 'legislators',
        'posts_per_page' => - 1,
        'post_status' => 'publish',
        'tax_query' => array(
            array(
                'taxonomy' => 'legislators_texonomy',
                'field' => 'term_id',
                'terms' => $selected_legislator_catId
            ) ,
        ) ,
    );

    $the_query = new WP_Query($args);

    if ($the_query->have_posts()): ?>    
              <option data-id="-1" value=""> Legislator</option>
              <?php
        while ($the_query->have_posts()):
            $the_query->the_post();
            $legislator_title = get_the_title();
            echo '<option id="selection" value="' . get_the_ID() . '">' . $legislator_title . '</option>';
?>  
         
<?php
        endwhile;
        wp_reset_postdata(); ?>


<?php
    else: ?>
<p></p>
<?php
    endif; ?>
 
<?php
    exit; // leave ajax call
    
}

// Fire AJAX action for both logged in and non-logged in users
add_action('wp_ajax_get_selected_legislator_cat', 'get_selected_legislator_cat');
add_action('wp_ajax_nopriv_get_selected_legislator_cat', 'get_selected_legislator_cat');

 
//voting2
function get_selected_voting2()
{
    global $wpdb, $table_prefix;
    $selected_legislator = $_POST['selecdedleg'];
    $selected_bill = $_POST['selecdedbill'];
    $results = $wpdb->get_results('SELECT id, vote_support FROM ' . $table_prefix . 'vote2 WHERE bill_Id = ' . $selected_bill . ' and legislator_Id=' . $selected_legislator);

    if (!empty($results))
    {
        foreach ($results as $row)
        {
?>
<input type="hidden" id="fatchid" name="fatchid" value= <?php echo $row->id ?> >
<div class="voting_type">
     <label class="typecheck" for="vote_support">Support</label> 
     <label class="option_yesNo">
     <input type="radio" name="vote_support"value='1' <?php if ($row->vote_support == '1')
            {
                echo 'checked';
            } ?> > Yes 
  </label>
  <label class="option_yesNo">
    <input type="radio" name="vote_support"value='0'<?php if ($row->vote_support == '0')
            {
                echo 'checked';
            } ?>>No 
  </label>
</div>
  <?php
        }
    }
    else
    {

?>
<div class="voting_type">
    <label class="typecheck" for="vote_support">Support</label> 
   <label class="option_yesNo">
     <input type="radio" name="vote_support"value='1'> Yes 
  </label>
  <label class="option_yesNo">
   <input type="radio" name="vote_support"value='0' required>No 
  </label>
</div>
 
    
     
<?php
    }
    exit; // leave ajax call
    
}
add_action('wp_ajax_get_selected_voting2', 'get_selected_voting2');
add_action('wp_ajax_nopriv_get_selected_voting2', 'get_selected_voting2');

//end









// delete voting function
function delete_voting()
{
    global $wpdb;
    $id = $_POST['ids'];
    $table = 'wp_vote2';
    $wpdb->delete($table, array(
        'id' => $id
    ));

    exit; // leave ajax call
    
}
add_action('wp_ajax_delete_voting', 'delete_voting');
add_action('wp_ajax_nopriv_delete_voting', 'delete_voting');

// End delete voting function


//edit_voting record
function edit_voting()
{
    $editid = $_POST['editedid'];
    global $wpdb, $table_prefix;
    $results = $wpdb->get_results('SELECT * FROM ' . $table_prefix . 'vote2 WHERE id=' . $editid); ?>
      <?php foreach ($results as $row)
    {
        $Bill_Name = get_the_title($row->bill_Id);
        $bill_catId = get_term($row->bill_catId)->name;
        $legislator_Name = get_the_title($row->legislator_Id);
        $legislator_catId = get_term($row->legislator_catId)->name;
?>
      <div class=" bill-content editform">
        <div class="selectbill">    
            <label class="">Bill Type </label>
            <select  onmousedown="(function(e){ e.preventDefault(); })(event, this)" id="bills_category" class="cat-select" name="bill_catid1">
             <option value="<?php echo $row->bill_catId; ?>"><?php echo $bill_catId; ?> </option>   
            </select> </div>   
          <div class="bill_info">
               <label class="">Bill</label>
                <select  onmousedown="(function(e){ e.preventDefault(); })(event, this)" id="bills_posts" class="category-select" name="bills_postsid">  
             <option value="<?php echo $row->bill_Id; ?>"><?php echo $Bill_Name; ?> </option>   
        </select>   
          </div> 
      
          </div>

        <div class="legislator-content editform">
        <div class="legislator_cat" >
            <label class="">Legislator Type</label> 
         <select  onmousedown="(function(e){ e.preventDefault(); })(event, this)" id="legislator_category" class="cat-select" name="legislator_catid1">  
             <option value="<?php echo $row->legislator_catId; ?>"><?php echo $legislator_catId; ?>                  </option>  
        </select> 
            </div>
            <div class="legislator_info">
                <label class="">Legislator</label> 
                    <select onmousedown="(function(e){ e.preventDefault(); })(event, this)"  id="legislator_posts" class="category-select" name="legislator_postsid">    
             <option value="<?php echo $row->legislator_Id; ?>"><?php echo $legislator_Name; ?>           </option>     
        </select> 
            </div>
            </div>
<div class="voting_type_main">
<input type="hidden" id="fatchid" name="fatchid" value= <?php echo $row->id ?> >
<div class="voting_type">
     <label class="typecheck" for="support">Support</label> 
     <label class="option_yesNo">
     <input type="radio" name="vote_support"value='1' <?php if ($row->vote_support == '1')
        { echo 'checked';} ?> > Yes 
  </label>
  <label class="option_yesNo">
    <input type="radio" name="vote_support"value='0'<?php if ($row->vote_support == '0')
        {
            echo 'checked';
        } ?>>No 
  </label>
</div>

 
</div>
     <div class="editsavebutton submitform">
       <button class="vote_submit button-primary" type="submit" name="submit" style="">Save</button> 
    </div>  
 
    <?php
    }
?> 
<?php exit; // leave ajax call
    
}

add_action('wp_ajax_edit_voting', 'edit_voting');
add_action('wp_ajax_nopriv_edit_voting', 'edit_voting');

//End edit_voting


function my_admin_menu()
{
    add_menu_page(__('Vote2', 'my-textdomain') , __('Vote', 'my-textdomain') , 'manage_options', 'vote', 'my_admin_page_contents', 'dashicons-schedule', 3);
    add_submenu_page('vote', 'Submenu Page', 'All Votes', 'manage_options', 'all-votes', 'my_admin_submenu_page_content');
}

add_action('admin_menu', 'my_admin_menu');

function my_admin_submenu_page_content()
{ ?>
<h2>All Votes</h2>
 
<table id="all_votes" class="cell-border" style="width:100%">
	<thead>

    <tr>
		<th>Sr.No</th>
        <th>Bill Name</th>
        <th>Bill Category</th>
        <th>Bill Type</th>
        <th>Legislator Name</th>
        <th>Legislator Type</th>
        <th>Support/Oppose</th>
     
        <th>Action</th>
    </tr>
			</thead>
	 <tbody>
<?php
    global $wpdb, $table_prefix;
// 	$total = $wpdb->get_var("SELECT COUNT(*) FROM (SELECT * FROM wp_voting) AS a");
// 	$post_per_page = 4;
// 	$page = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
// 	$offset = ( $page * $post_per_page ) - $post_per_page;
// 	  $results = $wpdb->get_results("SELECT * FROM wp_voting LIMIT $post_per_page OFFSET $offset");
 	   $results = $wpdb->get_results('SELECT * FROM '.$table_prefix.'vote2 ORDER BY id DESC');
      if (!empty($results))
    {
        $srnb ='1' ;
		  // echo  $wpdb->last_query ;
        foreach ($results as $row)
        {
            $record_id = $row->id;
            $Bill_Name = get_the_title($row->bill_Id);
            $bill_catId = get_term($row->bill_catId)->name;
          
            $legislator_Name = get_the_title($row->legislator_Id);
            $legislator_catId = get_term($row->legislator_catId)->name;

            if ($row->vote_support == '1')
            {
                $vote_support = 'Support';
            }
            else
            {
                $vote_support = 'Oppose';
            }
            

?>
  <tr>
	 <td><?php echo $srnb++ ;?></td>
    <td> <?php echo $Bill_Name; ?> </td>
    <td> <?php echo $bill_catId; ?>  </td>
       <td> <?php the_field('social_type', $row->bill_Id); ?></td>
     <td> <?php echo $legislator_Name; ?>  </td>
    <td> <?php echo $legislator_catId; ?> </td>
    <td> <?php echo $vote_support; ?> </td>
   
     
     <td class="actiondata"><a href="<?php echo get_bloginfo('url') ?>/wp-admin/admin.php?page=vote&id=<?php echo $record_id; ?>"> <i id="edit_record" data-id= "<?php echo $record_id; ?>" class="fa fa-edit"></i> </a>
     <i data-id= "<?php echo $record_id; ?>" class="fa fa-trash delete_record" ></i>
    </td>
  </tr>
  
    
    
 <?php
        }
    }
?>    
	</tbody>
</table>
<?php /*
echo '<div class="pagination">';
echo paginate_links( array(
'base' => add_query_arg( 'cpage', '%#%' ),
'format' => '',
'prev_text' => __('&laquo;'),
'next_text' => __('&raquo;'),
'total' => ceil($total / $post_per_page),
'current' => $page,
'type' => 'list'
));
echo '</div>';
*/?>
<?php
}

  
function my_admin_page_contents() {
    ?>
 <h1 class="addvote">
Add Vote    
</h1>
<h1 class="editvote" style="display:none">
	Edit Vote    
</h1>
 <input type= "hidden" class= "ajaxurl" data-url="<?php echo get_admin_url() . 'admin-ajax.php' ?>">

<?php
    global $wpdb, $table_prefix;

?>

 <input type="hidden" name="selecdedbill" id="selecdedbill" value=" ">
<input type="hidden" name="selecdedleg" id="selecdedleg" value=" ">
 
<form action="" id="postjob" method="post">
    <div class=" bill-content">
        <div class="selectbill" >   
            <label class="">Bill Type </label>
      <select id="bills_category" class="cat-select" name="bill_catid1"  >
            <option data-id="-1" value="">Bill Type </option>
            <?php $bills_terms = get_terms(array(
        'taxonomy' => 'bills',
        'hide_empty' => true,
    ));
    foreach ($bills_terms as $key => $value)
    {
        echo '<option value="' . $value->term_id . '" >' . $value->name . '</option>';
    }
?>  
        </select>   </div>  
        <div class="bill_info" style=" visibility: hidden;">
            <label class="">Bill </label>
            <select id="bills_posts" class="category-select" name="bills_postsid">
               
            </select>
            
                </div>
    </div>
    <div class="legislator-content">
        <div class="legislator_cat"  style=" visibility: hidden;">
            <label class="">Legislator Type</label> 
             <select id="legislator_category" class="cat-select" name="legislator_catid1"  >
                </select>
                </div>
    <div class="legislator_info"  style=" visibility: hidden;">
         <label class="">Legislator</label> 
        <select id="legislator_posts" class="category-select" name="legislator_postsid">

        </select>
                </div>
  </div> 
      <div class= "voting_type_main">
    
	</div>  
	
    <div class="submitform">
	 
         <button class="vote_submit button-primary" type="submit" name="submit" >Submit</button> 
    </div>   
        
    
</form>

<div id="recorddlt" >
    
</div>
    <?php
    if (isset($_POST['submit']))
    {
        global $wpdb;
        $id = $_POST['fatchid'];
        $tablename = $wpdb->prefix . 'vote2';
        $data = array(
            'bill_catId' => $_POST['bill_catid1'],
            'bill_Id' => $_POST['bills_postsid'],
            'legislator_catId' => $_POST['legislator_catid1'],
            'legislator_Id' => $_POST['legislator_postsid'],
			'vote_support' => $_POST['vote_support']
        );
        if ($id != '')
        {
            $wpdb->update($tablename, $data, array(
                'id' => $id
            ));
              $success = "<p class='sucess_msg'>vote updated successfully</p>";
             echo  $success ;
        }
        else
        {
            $wpdb->insert($tablename, $data);
			  $success = "<p class='sucess_msg'>vote submit successfully</p>";
              echo  $success ;
			//echo  $wpdb->last_query ;
        }

    }
 
}
add_filter( 'manage_legislators_posts_columns', 'smashing_filter_posts_columns' );
function smashing_filter_posts_columns( $columns ) {
  $columns['district'] = __( 'District' );
    $columns['choose_party_label'] = __( 'Party' );


 
  return $columns;

}
function my_custom_columns($columns) {
 global $post;
 if($columns == 'district') {
  echo get_field('district', $post->ID);
 } else {
  echo '';
 }
  if($columns == 'choose_party_label') {
  echo get_field('choose_party_label', $post->ID);
 } else {
  echo '';
 }

}
add_action("manage_legislators_posts_custom_column", "my_custom_columns");
//bill
add_filter( 'manage_bill_posts_columns', 'smashing_posts_columns' );
function smashing_posts_columns( $columns ) {
    $columns['social_type'] = __( 'Type' );
	  $columns['position'] = __( 'Position' );
	 $columns['date_&_time'] = __( 'Date' );
return $columns;
}
function my_custom_columnss($columns) {
 global $post;
  if($columns == 'social_type') {
  echo get_field('social_type', $post->ID);
 } else {
  echo '';
 }
	if($columns == 'position') {
		$col = get_field('position', $post->ID);
		
		print_r ($col['label']);
		
 } else {
  echo '';
 }
		if($columns == 'date_&_time') {
  echo get_field('date_&_time', $post->ID);
 } else {
  echo '';
 }

}
add_action("manage_bill_posts_custom_column", "my_custom_columnss");
function my_manage_columns( $columns ) {
  unset($columns['date']);
  return $columns;
}


