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
    <div class="candidateBanner" style="background: url(<?php echo site_url();?>/wp-content/uploads/2022/01/bill-detail-bg.jpg);" >
	    <div class="container">
            <div class="candidatebanner__inner">

            </div>
        </div>
    </div>
    <!-- /banner candidate page -->
  <!-- voterPerson__details -->
    <div class="voterPerson__details">
        <div class="container">
            <div class="voterPerson__detailsInner">
                <div class="voterPerson__detailsInnerLft">
                    <div class="voterPerson__detailsHolder">
						
                        <div class="voterPerson__detailsHolder__img">
							<?php if(has_post_thumbnail()):?>
						<?php $imageurl = wp_get_attachment_url( get_post_thumbnail_id($post->ID), 'thumbnail' ); ?>
                            <img class="img-circle-voter " src="<?php echo $imageurl; ?>" alt="" >
							
							 <?php else: ?>
							<img class="img-circle-voter" src="<?php echo site_url();?>/wp-content/uploads/2022/01/download.jpeg "  />
							<?php endif; ?>
                        </div>
						
                        <div class="voterPerson__detailsHolder__txt">
                            <h3 class="commnHeading30">Name: <?php the_title( ); ?></h3>
						<?php if ( $party_labels = get_field( 'choose_party_label' ) ) : ?>
                            <h4>  <?php echo esc_html($party_labels); ?> (<?php echo esc_html( $party_labels[0]   ); ?>)</h4>
						<?php endif; ?>
							
							<?php $legis_terms = get_the_terms( $post->ID , 'legislators_texonomy' ); ?>	
							
                 <h4>								
								<?php 
                   if($legis_terms != ''){
                foreach ( $legis_terms as $terms ) {
                                 echo $terms->name;
                                }
                             }   ?>

                               <?php the_field('district'); ?>
							</h4>
                        </div>
                    </div>
                    <h2 class="commnHeading30">
                        2021 Scorecard
                       </h2>

                            <?php 
            //Economic
                            $args = array(
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
                                $wp_query = new WP_Query( $args );

                                $post_count = $wp_query->post_count;

                                $number_of_economicbill = $post_count;  
                                // echo  $number_of_economicbill ;

                                $Lid = get_the_ID();

                                $the_query = new WP_Query( $args ); 
                                $Ebilid = wp_list_pluck( $the_query->posts, 'ID' );
 
                                $EconomicList = implode(', ', $Ebilid);

                                global $wpdb, $table_prefix;

             $countEconomicSupport = $wpdb->get_var('SELECT COUNT(*) FROM wp_vote2 WHERE legislator_Id = '.$Lid.' AND bill_Id IN ('.$EconomicList.')' );
                    
             $ecconomicsupport = round ($countEconomicSupport * 100 / $number_of_economicbill);               
           
//Social
                     $args2 = array(
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
                                $wp_query = new WP_Query( $args2 );

                                $post_count = $wp_query->post_count;

                                $number_of_socialbill = $post_count;  
                                $Lid = get_the_ID();

                                $the_query = new WP_Query( $args2 ); 
                                $Sbilid = wp_list_pluck( $the_query->posts, 'ID' );
                              

                                $SocialList = implode(', ', $Sbilid);

                                global $wpdb, $table_prefix;
                           
                  $countSocialSupport = $wpdb->get_var('SELECT COUNT(*) FROM wp_vote2 WHERE legislator_Id = '.$Lid.' AND bill_Id IN ('.$SocialList.') ' );

                            $socialsupport = round ($countSocialSupport * 100 / $number_of_socialbill);    

                            $overalltotalbill=  $number_of_economicbill + $number_of_socialbill;
                            $overalltotalsupport=  $countEconomicSupport + $countSocialSupport    ;

                            $overallSupport= round ($overalltotalsupport * 100 / $overalltotalbill); 

    ?>

                    <div class="circleBoxHldr">
                    
                        <div class="box" data-animate="false">
                          <div class="circle" data-dots="20" data-percent="<?php echo $overallSupport;?>" style="--bgColor: #20d13d"></div>
                          <div class="text">
                            <h2> <?php if($overallSupport != '0'){echo $overallSupport;}else{echo '0';}  ?>  </h2>
                           
                        </div>
                           <span class="box__textBtm">Overall score</span>
                        </div>
                        <div class="box" data-animate="false">
                          <div class="circle" data-dots="20" data-percent="<?php echo $socialsupport;?>" style="--bgColor: #f3c816"></div>
                          <div class="text">
                            <h2><?php if($socialsupport != '0'){echo $socialsupport;}else{echo '0';} ?>  </h2>
                            <!-- <small>JavaScript</small> -->
                          </div>
                          <span class="box__textBtm">Social score</span>
                        </div>
                        <div class="box" data-animate="false">
                          <div class="circle" data-dots="20" data-percent="<?php echo $ecconomicsupport;?>" style="--bgColor: #f3c816"></div>
                          <div class="text">
                            <h2><?php if($ecconomicsupport != '0'){echo $ecconomicsupport;}else{echo '0';}  ?>  </h2>
                            <!-- <small>PHP</small> -->
                          </div>
                          <span class="box__textBtm">Economic score</span>
                        </div>
                       </div>
                </div>
                <div class="voterPerson__detailsInnerRgt">
                   <div class="voterPerson__detailsInnerRgtmap">
                       <img src="<?php the_field('district_image'); ?>" alt="">
                   </div>
					<?php $legis_terms = get_the_terms( $post->ID , 'legislators_texonomy' ); ?>	
                   <h3 class="commnHeading30">
    <?php  if($legis_terms != ''){
                 foreach ( $legis_terms as $term ) {
                                 echo $term->name;
                                }}?>
					   District <?php the_field('district'); ?></h3>
                </div>
            </div>
        </div>
    </div>
<!-- / -->

 <div class="container">

         <!--  -->
	 <?php if ( have_rows( 'previous_year_rating' ) ) : ?>
         <div class="previousRatings">
             <div class="previousRatings__top">
                <h3 class="commnHeading30">Previous Ratings on the LPINSCORECARD</h3>
                
	<ul>
	<?php while ( have_rows( 'previous_year_rating' ) ) :
		the_row(); ?>
		
		<li>
        <?php if ( $rating = get_sub_field( 'rating' ) ) : ?>
			<?php echo esc_html( $rating ); ?><br>
		<?php endif; ?>
        
		<?php if ( $label = get_sub_field( 'label' ) ) : ?>
			<span>
		<?php echo esc_html( $label ); ?>
	</span>
		<?php endif; ?>

		
        </li>
	<?php endwhile; ?>
	</ul>


             </div>
         </div>
	 <?php endif; ?>
         <!-- / -->
         <!--  -->
         <div class="aboutVoters">
             <h3 class="commnHeading30">About <?php the_title( ); ?></h3>
			 <?php if ( have_rows( 'voters_info' ) ) : ?>
	            <?php while ( have_rows( 'voters_info' ) ) :
		         the_row(); ?>
			 <div class="aboutVoters__info">
                <?php if ( $heading = get_sub_field( 'heading' ) ) : ?>
			 <h4><?php echo esc_html( $heading ); ?></h4>
		        <?php endif; ?>
				 <?php if ( $subheading = get_sub_field( 'subheading' ) ) : ?>
             <h5><?php echo esc_html( $subheading ); ?></h5>
				 <?php endif; ?>
             </div>
			 	<?php endwhile; ?>
<?php endif; ?>
			 <div class="aboutVoters__infoTxt">
          <?php echo  the_content();?>
				 </div>
         </div>
         <!-- / -->
     </div>
     <div class="billbrakedown billbrakedown--voterdetails">
    <div class="container">
      <div class="billbreakedown__inner">
        <div class="billbreakedown__innerTop">
          <h3 class="commnHeading30">Rep.  <?php the_title(); ?></h3>
        </div>
        <div class="billbreakedown__innerBtm">
          <div class="table__holder">
          
            <ul class="tabs">
              <li class="tab-link current commnHeading24" data-tab="tab-1">Votes </li>
              <!-- <li class="tab-link commnHeading24" data-tab="tab-2"> Considered</li> -->

            </ul>
            <div class="tabs__badges tabs__badges--voters">
 <input type= "hidden" class= "ajaxurl" data-url="<?php echo get_admin_url().'admin-ajax.php'?>">
  <input type= "hidden" class="legid" value=" <?php echo get_the_ID();?> ">
              <div class="tabs__badgesHolderRgt">
                  <select name="" id="single_leg_bills_session">
<?php $byear = get_the_date( 'Y' );

for ($nYear = $byear;
 $nYear <= date('Y'); $nYear++) {?>
       <option value="<?php echo $nYear;?>"><?php echo $nYear;?></option>
     <?php
}
  
 ?>
            </select>   
              </div>

            </div>
            <div>
              <table class="tabs-table">
                <thead>
                  <tr>
                    <th><a href="#">Bill </a> </th>
                    <th class="text-center"><a href="#">Vote</a> </th>
                  </tr>
                </thead>
                <tbody class="single_leg_bill_data">

                     <?php
                        $legid = get_the_ID();
               
                 $args = array(
                         'post_type' => 'bill',
                         'posts_per_page' => -1 );
                           $the_query = new WP_Query( $args ); 
                           if ( $the_query->have_posts() ) : 
                           while ( $the_query->have_posts() ) : $the_query->the_post();   ?>
                      <tr class="border-b">
                    <td>
                      <div class="vote__consicder__td">
                    <?php $title = get_the_title();?>
                        <p><?php echo $title;?> </p>

                  <?php if ( $roll_call = get_field( 'roll_call' ) ) : ?>
                          <span>House Roll Call #:  <?php echo $roll_call; ?></span>   
                         <?php endif; ?>  
                  
                   <a href="<?php echo get_permalink()?>">Read More Details</a>
                      </div>

                    </td>
           <td class="text-center"> 
                   <?php global $wpdb, $table_prefix;
                    $results = $wpdb->get_results('SELECT vote_support FROM '.$table_prefix.'vote2 WHERE legislator_Id = '.$legid.' AND bill_Id= '.$id  );

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
endif; 

        $id = get_the_ID();
                
                global $wpdb, $table_prefix;
                $results = $wpdb->get_results('SELECT * FROM '.$table_prefix.'vote2 WHERE legislator_Id= '.$id.' ORDER BY id DESC');
                 // echo $wpdb->last_query;
                   if (!empty($results))
                  { 
             foreach ($results as $row){  
              $bill_Name = get_the_title($row->bill_Id); 

              ?>
                  <tr class="border-b">
                    <td>
                      <div class="vote__consicder__td">
                        <p><?php echo $bill_Name;?> </p>
                   
  <?php if ( $roll_call = get_field( 'roll_call',$row->bill_Id ) ) : ?>
                     <span>House Roll Call #: <?php echo $roll_call; ?> </span>    
                          <?php endif; ?>  
                      <a href="<?php echo get_permalink($row->bill_Id)?>">Read More Details</a>
                      </div>

                    </td>
                      <td class="text-center"> 
                       <?php
                        $support= $row->vote_support;
                        if ($support == '0'){?>
                    <img src="<?php echo site_url(); ?>/wp-content/uploads/2022/02/vote_green.png" alt="">
                     <?php   }
                          else{?>
                    <img src="<?php echo site_url(); ?>/wp-content/uploads/2022/02/vote_red.png" alt="">    
                        <?php  }
                       ?> 
                          
                      </td>
                  </tr>



               <?php      }}?> 
             
              
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
