<?php
/**
*Plugin Name: Blog Reporter
*Description: Creates a report of your blogs
**/

function get_blog_info()
{
  $content = "";

  $categories = get_categories();
  $category_slugs = [];
  //$content .= var_dump($categories);
  foreach ($categories as $key) {
    //$content .= $key->slug;
    array_push($category_slugs, $key->slug);
  }
  //$content .= var_dump($category_slugs);
  foreach ($category_slugs as $cat_slug) {

      $args = array( 'category_name' => $cat_slug, 'posts_per_page' => -1 );
      $the_query = new WP_Query( $args );
      //$content .= wp_list_categories();

      $content .= "<h2>" . $cat_slug . "(". $the_query->found_posts .")</h2>";

      if ( $the_query->have_posts() ) :


          while ( $the_query->have_posts() ) :
              $the_query->the_post();
              $content .= "<a href ='" . get_permalink() . "'>" .get_the_title() . "</a><br>" ;
              $content .= get_the_date() . "<br>";
              $categories = get_the_category();
              foreach ($categories as $cat) {
                  $content .= "/" . $cat->name;
              }

              $content .= "<br>---------<br>";
          endwhile;

          wp_reset_postdata();

      else :
          _e( 'Sorry, no posts matched your criteria.' );
      endif;

}
return $content;
}

add_shortcode('blog-report', 'get_blog_info');
 ?>
