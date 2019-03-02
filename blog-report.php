<?php
/**
*Plugin Name: Blog Reporter
*Description: Creates a report of your blogs
**/

function get_blog_info()
{
  $db_array = [];
  array_push($db_array, array("Title", "Date", "Permalink", "Categories", "Excerpt"));
  $content = "";
  $path = trailingslashit(wp_upload_dir()['basedir']) . "blog-reports/" ;
  if (!file_exists($path)) {
      mkdir($path, 0755, true);
  }

  $file_name = "blog_data.csv";
  $css = "style='border: 3px solid blue;border-radius: 5px;padding: 15px;background-color:coral;'";
  $content .= "<a ".$css." href='" . trailingslashit(wp_upload_dir()['baseurl']) ."blog-reports/".  $file_name . "' > Download CSV File</a><br>";

      $args = array( 'posts_per_page' => -1 );
      $the_query = new WP_Query( $args );
      //$content .= wp_list_categories();
      $content .= "<h2>Total Posts: " . $the_query->found_posts . "</h2>";

      if ( $the_query->have_posts() ) :


          while ( $the_query->have_posts() ) :
              $the_query->the_post();

              $the_title = get_the_title();
              $the_permalink = get_permalink();
              $the_date = get_the_date();
              $the_categories = "";
              $categories = get_the_category();
              foreach ($categories as $cat) {
                  $the_categories .= "/" . $cat->name;
              }
              $the_content = substr(trim(strip_tags(get_the_content())),0, 100) . "...";



              $content .= "<a href ='" . $the_permalink . "'>" . $the_title . "</a><br>";
              $content .= $the_date . "<br>";
              $content .= "(";
              $content .= $the_categories;
              $content .= ")<br>";
              $content .= $the_content;
              $content .= "<br><br>";

              array_push($db_array, array($the_title,$the_date,$the_permalink,$the_categories, $the_content));
          endwhile;

          wp_reset_postdata();

      else :
          _e( 'Sorry, no posts matched your criteria.' );
      endif;
$test_array = [[1,2,3], [4,5,6]];
$path = trailingslashit(wp_upload_dir()['basedir']) . "blog-reports/" ;
if (!file_exists($path)) {
    mkdir($path, 0755, true);
}
$handle = fopen($path . $file_name, "w");
foreach ($db_array as $line) {
  fputcsv($handle, $line);
}
fclose($handle);

return $content;
}

add_shortcode('blog-report', 'get_blog_info');
 ?>
