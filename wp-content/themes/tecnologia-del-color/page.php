<?php
/**
 * The template for displaying pages
 *
 * @package Tecnologia_del_Color
 */

get_header();
?>

<section class="page-header">
    <div class="container">
        <h1><?php the_title(); ?></h1>
    </div>
</section>

<div class="container">
    <div class="content-area">
        <?php
        while ( have_posts() ) :
            the_post();
            ?>
            <article <?php post_class(); ?>>
                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="featured-image" style="margin-bottom: 40px;">
                        <?php the_post_thumbnail( 'large' ); ?>
                    </div>
                <?php endif; ?>
                
                <div class="entry-content">
                    <?php the_content(); ?>
                </div>
            </article>
        <?php endwhile; ?>
    </div>
</div>

<?php
get_footer();
