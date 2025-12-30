<?php
/**
 * The template for displaying single posts
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
    <div class="content-area" style="max-width: 900px; margin: 0 auto;">
        <?php
        while ( have_posts() ) :
            the_post();
            
            if ( has_post_thumbnail() ) :
                ?>
                <div class="featured-image" style="margin-bottom: 40px;">
                    <?php the_post_thumbnail( 'large' ); ?>
                </div>
            <?php endif; ?>
            
            <article <?php post_class(); ?>>
                <div class="entry-content">
                    <?php the_content(); ?>
                </div>
            </article>
            
            <div class="post-navigation" style="margin-top: 60px; padding-top: 40px; border-top: 1px solid #e0e0e0;">
                <?php
                the_post_navigation( array(
                    'prev_text' => '<span class="nav-subtitle">' . __( 'Anterior:', 'tecnologia-del-color' ) . '</span> <span class="nav-title">%title</span>',
                    'next_text' => '<span class="nav-subtitle">' . __( 'Siguiente:', 'tecnologia-del-color' ) . '</span> <span class="nav-title">%title</span>',
                ) );
                ?>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<?php
get_footer();
