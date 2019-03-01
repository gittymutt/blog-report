<?php
/**
*Plugin Name: Blog Reporter
*Description: Creates a report of your blogs
**/

function get_blog_info()
{
  $db_array = [];
  $content = "";


      $args = array( 'posts_per_page' => -1 );
      $the_query = new WP_Query( $args );
      //$content .= wp_list_categories();
      $content .= "<h2>Total Posts: " . $the_query->found_posts . "</h2>";

      if ( $the_query->have_posts() ) :


          while ( $the_query->have_posts() ) :
              $the_query->the_post();
              $content .= "<a href ='" . get_permalink() . "'>" .get_the_title() . "</a><br>" ;
              $content .= get_the_date() . "<br>";
              $categories = get_the_category();
              $content .= "(";
              foreach ($categories as $cat) {
                  $content .= "/" . $cat->name;
              }
              $content .= ")<br>";
              $content .= substr(get_the_content(),0, 100) . "....<br>";
              $content .= "<br><br>";

              array_push($db_array, array(get_the_title(),get_the_date(),get_permalink()));
          endwhile;

          wp_reset_postdata();

      else :
          _e( 'Sorry, no posts matched your criteria.' );
      endif;
$test_array = [[1,2,3], [4,5,6]];
$dir = plugin_dir_path( __DIR__ );
$handle = fopen($dir . "blog_data.csv", "w");
foreach ($db_array as $line) {
  fputcsv($handle, $line);
}
fclose($handle);

return $content;
}

add_shortcode('blog-report', 'get_blog_info');
 ?>
