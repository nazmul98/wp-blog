<?php 
    get_header();
    $default_width = 'col-md-9';
    if(!is_active_sidebar('sidebar-1')) {
        $default_width = 'col-md-10 offset-md-1';
    }
?>
<body <?php body_class(); ?>>
<!-- Hero Section -->
<?php get_template_part('template-parts/hero') ?>
<div class="posts">
    <div class="container">
        <div class="row">
            <div class="<?php echo $default_width; ?>">
                <?php if(have_posts()): 
                    while(have_posts()): 
                    the_post(); 
                ?>
                <div <?php post_class(['post']); ?>>
                    <div class="row">
                        <div class="col-md-12">
                            <h2 class="post-title text-center">
                                <?php the_title(); ?>
                            </h2>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <p class="text-center">
                                <strong>
                                    <?php the_author(); ?>
                                </strong>
                                <br/>
                                <?php echo get_the_date(); ?>
                            </p>
                            <?php 
                                the_tags(
                                    '<ul class="list-unstyled d-flex justify-content-center"><li class="px-2">', 
                                    '</li><li>', 
                                    '</li></ul>'
                                );
                            ?>
                            <p>
                                <?php 
                                    if (!class_exists('Attachments')) {
                                        if(has_post_thumbnail()) {
                                            the_post_thumbnail('large', ['class' => 'img-fluid', 'style'=>'width:100%']);
                                        }
                                    }
                                ?>
                                <div class="slider">
                                    <?php
                                        if (class_exists('Attachments')):
                                        $attachments = new Attachments('slider');
                                    ?>
                                    <?php if( $attachments->exist() ) : ?>
                                        <?php while( $attachment = $attachments->get() ) : ?>
                                            <div>
                                                <?php echo $attachments->image('large'); ?>
                                            </div>
                                        <?php endwhile; ?>
                                    <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </p>
                            <?php 
                                the_content();
                                if (get_post_format() == 'image' && function_exists('the_field')) {
                                    $location = get_field('location');
                                    $date = get_field('date');
                                    $img_thumbnail_id = get_field('thumbnail');
                                    $img_thumbnail_url = wp_get_attachment_image_url($img_thumbnail_id, 'thumbnail');
                                    $file_id = get_field('documents');
                                    $file_url = wp_get_attachment_url($file_id);
                                    $file_thumbnail_id = get_field('documents_preview', $file_id);
                                    $file_thumbnail_img = wp_get_attachment_image_url($file_thumbnail_id, 'thumbnail');

                                    if (get_field('required_text')) {
                                        $required_text = get_field('required_text');
                                    }

                                    echo esc_html($location, 'morning');
                                    echo "<br>";
                                    echo esc_html($date, 'morning');
                                    echo "<br>";
                                    echo esc_html($required_text, 'morning');
                                    echo "<br>";
                                    echo "<img src=".$img_thumbnail_url." >";
                                    echo "<br/>";
                                    echo "<a target='_blank' href='{$file_url}'>$file_url</a>";
                                    echo "<br/>";
                                    echo "<a target='_blank' href='{$file_url}'>
                                        <img src='{$file_thumbnail_img}' />
                                    </a>";
                                    echo "<br/>";
                                    $related_posts = get_field('related_posts');
                                    $_p = new WP_Query([
                                        'post__in' => $related_posts,
                                        'orderby' => 'post__in'
                                    ]);
                                    while($_p->have_posts()){
                                        $_p->the_post();
                                        echo "<h2>".the_title()."</h2>";
                                    }
                                    wp_reset_query();
                                }
                            ?>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php endif; ?>
            <div>
                <?php wp_link_pages(  ); ?>
            </div>
            
            <div>
                <?php 
                    if (!post_password_required() && get_comments_number()) {
                        comments_template();
                    }
                ?>
            </div>
            </div>
            <?php if(is_active_sidebar('sidebar-1')): ?>
                <div class="col-md-3 justify-content-end">
                    <?php dynamic_sidebar('sidebar-1'); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php get_template_part('template-parts/author');?>
    <div class="row mb-5">
    <div class="col-md-6 offset-md-3 bg-light py-3">
        <div class="d-flex justify-content-between">
            <?php 
                previous_post_link();
                next_post_link();
            ?>
        </div>
    </div>
</div>
<?php
    get_footer();
?>
