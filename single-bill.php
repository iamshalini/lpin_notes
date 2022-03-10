<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

get_header();
 
 ?>
    
    <div class="candidateBanner"<?php if(has_post_thumbnail()):?> style="background: url(<?php echo $featured_image; ?>);" <?php else: ?> style="background: url(<?php echo site_url();?>/wp-content/uploads/2022/01/bill-detail-bg.jpg);"    <?php endif; ?>>
      <div class="container">
            <div class="candidatebanner__inner">

            </div>
        </div>
    </div>
    <!-- /banner candidate page -->

<div class="billdetails">
      <div class="container">
          <div class="billdetails__inner">
                <div class="billdetails__innerLeft">
                    <h2 class="commnHeading30"><?php the_title( ); ?></h2>
          <?php $terms = get_the_terms( $post->ID , 'bills' );?>  
                    <h3 class="commnHeading24">
                 <?php foreach ( $terms as $term ) {
            echo $term->name;
        }?>
               
                 Roll Call #:  <?php the_field('roll_call'); ?></h3>
               <?php echo  the_content();?>
                </div>
                <div class="billdetails__innerRight">
                    <div class="dilldetails__innerRightTop">
                        <h3 class="commnHeading24">Vote Details</h3>
                        <ul>
                       
                         <li>Date of Vote: <?php the_field('date_&_time') ;?></li>
              
                          
                            
                            <li>Roll Call:  <?php the_field('roll_call'); ?></li>
                            <li>Presiding:  <?php the_field('author'); ?></li>
                            <li>Type:  <?php the_field('social_type'); ?></li>
                        </ul>
                    </div>
                    <div class="billdetails__innerBtm">   
                         <?php
                        $position = get_field( 'position' );
?>
                        <h3 class="commnHeading24">Position: <?php  echo $position['label']; ?></h3>
                        <p>Back to <span>lpinscorecard</span> </p>
                    </div>
                </div>
            </div>
      </div>
    </div>


<div class="billbreakedown__innerTop">
          <div class="container">
          <h3 class="commnHeading30">Rep.  <?php the_title(); ?></h3>
          <h3 class="commnHeading24">Vote Breakdown:</h3>
       <?php
           

$args = array(
      'posts_per_page' => -1,
      'post_type'=> 'legislators',
     ); 
 $wp_query = new WP_Query( $args );

 $post_count = $wp_query->post_count;
 
 $number_of_results = $post_count;
   
          $id = get_the_ID();
         $bid = get_the_ID();
        global $wpdb, $table_prefix;
      
        $supportcount = $wpdb->get_var(" SELECT COUNT(*) FROM wp_vote2 WHERE vote_support = '1' and bill_Id = ".$id);
         //echo $wpdb->last_query;
        $opposecount = $wpdb->get_var(" SELECT COUNT(*) FROM wp_vote2 WHERE vote_support = '0'and bill_Id = ".$id);
  
          $sumcount =   $supportcount + $opposecount;
           $abccount =  $number_of_results - $sumcount;
             
            $perabccount=  round ($abccount * 100 / $number_of_results); 
            $stotal = $number_of_results;
                     $support = round ($supportcount * 100 / $stotal); 
             
             $ototal = $number_of_results;
               $oppose= round ($opposecount * 100 / $ototal); 
              ?>
 
          <div class="billbreakedown__progress">
            <div class="billbreakedown__progressHolder">
              <div class="greenProgess greenProgess__text" style="width: <?php echo $support;?>%;">
                <div class="digit__holder">
                  <span> <?php if($supportcount != '0'){echo $supportcount;} ?> <br>
                    <img src="<?php echo site_url()?>/wp-content/uploads/2022/02/green_arrowTop.png" alt="">
                  </span>
                </div>
              </div>
              <div class="redProgress greenProgess__text" style="width: <?php echo $oppose;?>%;">
                <div class="digit__holder">
                  <span> <?php if($opposecount != '0'){echo $opposecount;} ?> <br>
                    <img src="<?php echo site_url()?>/wp-content/uploads/2022/02/red_arrowTop.png" alt="">
                  </span>
                </div>
              </div>
              <input name="locationID" class="perofabcent" type="hidden" value="23">
            
              <div class="blackProgress greenProgess__text" style="width: <?php echo $perabccount;?>%;">
                <div class="digit__holder">
                  <span > <?php if($abccount != '0'){echo $abccount;} ?>  <br>
                    <img src="<?php echo site_url()?>/wp-content/uploads/2022/02/black_arrowTop.png" alt="">
                  </span>
                </div>
              </div>
              
            </div>
          </div>
       

 <div class="billbreakedown__innerBtm">
          <div class="table__holder">
            <div class="tabs__badges">
              <button><span class="greenbtn"></span> Support</button>
              <button><span class="greenbtn"></span> Oppose</button>
              <button><span class="greenbtn"></span> Absent, Excused</button>

            </div>
          </div>
        </div>
 </div>
 </div>

    <!--  -->
 <div class="billbrakedown">
    <div class="container">
      <div class="billbreakedown__inner">
        <div class="billbreakedown__innerTop">
 
          
        </div>
        <div class="billbreakedown__innerBtm">
          <div class="table__holder">

            <div>
              <table class="tabs-table">
                <thead>
                  <tr>
                    <th><a href="#">Legislator<img src="http://122.160.61.100/dev/nk/lpin/wp-content/uploads/2022/02/sort-arrow.jpg" alt=""></a> </th>
                    <th><a href="#">District <img src="http://122.160.61.100/dev/nk/lpin/wp-content/uploads/2022/02/sort-arrow.jpg" alt=""></a> </th>
                    <th class="text-center"> <a href="#">Party Labels<img src="http://122.160.61.100/dev/nk/lpin/wp-content/uploads/2022/02/sort-arrow.jpg" alt=""></a>
                    </th>
                    <th class="text-center"> <a href="#">Vote<img src="http://122.160.61.100/dev/nk/lpin/wp-content/uploads/2022/02/sort-arrow.jpg" alt=""></a> </th>

                  </tr>
                </thead>
                <tbody>
          
           <?php

               $bid = get_the_ID();
               

                  $args = array(
                         'post_type' => 'legislators',
                         'posts_per_page' => -1 );
                           $the_query = new WP_Query( $args ); 
                           if ( $the_query->have_posts() ) : 
                           while ( $the_query->have_posts() ) : $the_query->the_post();  ?>
                  <tr class="border-b">
                    <td>
                      <a href="<?php echo get_permalink()?>">  <div class="first-td">
                                 <?php $title = get_the_title(); 
                               if(has_post_thumbnail()):?>
                                
                <?php $imageurl = wp_get_attachment_url( get_post_thumbnail_id($post->ID), 'thumbnail' ); ?>
                 <img class="img-circle" src="<?php echo $imageurl; ?>" alt="">
              <?php else: ?>
                <img class="img-circle" src="<?php echo site_url();?>/wp-content/uploads/2022/01/download.jpeg "  />
                <?php endif; ?>
                        <span><?php echo $title;?></span>
                      </div></a>
                    </td>
                    <td><?php if ( $district = get_field( 'district' ) ) : ?>
               <?php echo $district; ?>
             <?php endif; ?> </td>
                    <td class="text-center"><?php if ( $party_labels = get_field( 'choose_party_label' ) ) : ?>
               <?php echo ( $party_labels[0] ); ?>
            
             <?php endif; ?></td>
         <td class="text-center"> 
         <?php  
            
             global $wpdb, $table_prefix;
                $results = $wpdb->get_results('SELECT vote_support FROM '.$table_prefix.'vote2 WHERE legislator_Id = '.$id.' AND bill_Id='.$bid );
              if (!empty($results))
                  { 
                      foreach ($results as $row){
                      $support= $row->vote_support;
                        if ($support == '1'){?>
                    <img src="<?php echo site_url(); ?>/wp-content/uploads/2022/02/vote_green.png" alt="">
                     <?php   }
                          else{?>
                    <img src="<?php echo site_url(); ?>/wp-content/uploads/2022/02/vote_red.png" alt="">     <?php  }
                      }

                    }
                    else{?>
                      <input type="hidden" class="ab_vote">
                      <img src="<?php echo site_url(); ?>/wp-content/uploads/2022/02/dash.png" alt=""> 
              <?php      }

         ?>
         </td>
 
     </tr>             
                  
                  <?php endwhile;
                wp_reset_postdata(); ?>
                <?php else:  ?>
           
                <?php endif; ?>
                


                </tbody>
              </table>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
  
<?php
get_footer();
