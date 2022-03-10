<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

?>
			</main><!-- #main -->
		</div><!-- #primary -->
	</div><!-- #content -->

	

	<footer id="colophon" class="footer" role="contentinfo">
 <div class="container">
          <div class="footerInner">
            <div class="footerInner_peace">
	<?php	if ( is_active_sidebar( 'footer_sidebar1' ) ) : ?>

		<?php dynamic_sidebar( 'footer_sidebar1' ); ?>	

<?php endif; ?>
			  </div>
			    <div class="footerinner__last">
	<?php	if ( is_active_sidebar( 'footer_sidebar2' ) ) : ?>

		<?php dynamic_sidebar( 'footer_sidebar2' ); ?>	

<?php endif; ?>
			  </div>
	 </div>
		</div>
		<div class="site-info">
			
			

		</div><!-- .site-info -->
	</footer><!-- #colophon -->

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
