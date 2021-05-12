<?php
/*
YARPP Template: Thumbnails
Description: This template returns the related posts as thumbnails in an ordered list. Requires a theme which supports post thumbnails.
Author: YARPP Team
*/
?>

<?php
/*
Templating in YARPP enables developers to uber-customize their YARPP display using PHP and template tags.

The tags we use in YARPP templates are the same as the template tags used in any WordPress template. In fact, any WordPress template tag will work in the YARPP Loop. You can use these template tags to display the excerpt, the post date, the comment count, or even some custom metadata. In addition, template tags from other plugins will also work.

If you've ever had to tweak or build a WordPress theme before, you’ll immediately feel at home.

// Special template tags which only work within a YARPP Loop:

1. the_score()		// this will print the YARPP match score of that particular related post
2. get_the_score()		// or return the YARPP match score of that particular related post

Notes:
1. If you would like Pinterest not to save an image, add `data-pin-nopin="true"` to the img tag.

*/
?>

<?php
/* Pick Thumbnail */
global $_wp_additional_image_sizes;
if ( isset( $_wp_additional_image_sizes['yarpp-thumbnail'] ) ) {
	$dimensions['size'] = 'yarpp-thumbnail';
} else {
	$dimensions['size'] = 'medium'; // default
}
?>

<div class="related-posts">
<h2 class="related-posts__header">Читайте также</h2>
    <?php if ( have_posts() ) : ?>
        <ul class="related-posts__grid">
            <?php while ( have_posts() ) : the_post(); ?>
                <?php if ( has_post_thumbnail() ) : ?>
                <li class="related-item">
                    <a class="related-item__anchor" href="<?php the_permalink(); ?>" rel="bookmark norewrite" title="<?php the_title_attribute(); ?>">
                        <div class="related-item__media">
                            <?php the_post_thumbnail( $dimensions['size'], array( 'data-pin-nopin' => 'true', 'class' => 'related-item__image' ) ); ?>
                        </div>
                        <div class="related-item__date">
                            <?php the_date(); ?>
                        </div>
                        <div class="related-item__title">
		                    <?php the_title(); ?>
                        </div>
                    </a>
                </li>
                <?php endif; ?>
            <?php endwhile; ?>
        </ul>
    <?php else : ?>
        <p>No related photos.</p>
    <?php endif; ?>
</div>
<style>
    .related-posts {}
    .related-posts__header {
        text-transform: inherit;
        margin-bottom: 2rem;
    }
    .related-posts__grid {
        list-style: none;
        padding: 0;
        margin: 0;
        display: grid;
        grid-gap: 20px;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    }
    .related-item {}
    .related-item__anchor {
        font-weight: normal !important;
    }
    .related-item__media {
        position: relative;
        padding-bottom: 56.25%;
        overflow: hidden;
        background-color: #e7e7e7;
        margin-bottom: 0.5rem;
    }
    .related-item__image {
        position: absolute;
        width: 100%;
        height: auto;
        object-fit: cover;
        left: 0;
        top: 0;
    }
    .related-item__date {
        color: #b5b5b5;
        font-size: 0.8em;
        margin-bottom: 0.3rem;
    }
    .related-item__title {
        color: #333;
    }
</style>
