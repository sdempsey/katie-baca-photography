<?php 
	/* Template Name: Gallery Template */

?>
<?php get_header(); ?>
<?php get_template_part('body-header'); ?>
<section class="thumbs">
	<div class="container">
	<?php 
		if (have_rows('image_gallery')): 
			while (have_rows('image_gallery')): the_row(); ?>
		<article class="images">
		<?php if (get_sub_field('title')): ?>
			<h1><?php the_sub_field('title'); ?></h1>
		<?php endif; ?>
				<div class="gallery">
					<?php 
						$images = get_sub_field('gallery');

						if ($images): 
							foreach($images as $image): 
					?>
							<a href="<?php echo $image['url']; ?>">
								<img src="<?php echo $image['sizes']['thumbnail']; ?>" alt="<?php echo $image['alt']; ?>" height="<?php echo $image['thumbnail-height']; ?>" width="<?php echo $image['thumbnail-width']; ?>">
								
							</a>
						<?php endforeach; ?>
					<?php endif;?>
				</div>
		</article>	
	<?php endwhile; endif; ?>
	</div>
</section>


<?php get_footer(); ?>