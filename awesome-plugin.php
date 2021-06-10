<?php
/**
 * Plugin Name: Blog Report
 * Description: Create a downloadablespreadsheet
 * file that summarizes all of your blog posts
 */


 function blog_reporter_options_page_html()
 {


     ?>
     <div class="wrap">
         <h1><?= esc_html(get_admin_page_title()); ?></h1>
     <?php


     $db_array = [];
     array_push($db_array, array("Title", "Date", "Permalink", "Image URL", "Categories", "Tags", "Excerpt"));
     $content = "";
     $path = trailingslashit(wp_upload_dir()['basedir']) . "blog-reports/" ;
     if (!file_exists($path)) {
         mkdir($path, 0755, true);
     }

     $file_name = "blog_data.csv";
     $css = "style='border: 3px solid blue;border-radius: 5px;padding: 15px;margin-top: 90px;background-color:blue;'";
     $content .= "<br><a ".$css." href='" . trailingslashit(wp_upload_dir()['baseurl']) ."blog-reports/".  $file_name . "' > Download CSV File</a><br>";

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
                 $the_tags = "";
                 $the_image_url = "";
                 $categories = get_the_category();
                 
                 foreach ($categories as $cat) {
                     $the_categories .= "/" . $cat->name;
                 }

                 if (get_the_tags()) {
                    $tags = get_the_tags();

                    foreach($tags as $tag) {
                        $the_tags .= "/" . $tag->name;
                    }
                 }

                 if (get_the_post_thumbnail_url()) {
                    $the_image_url = get_the_post_thumbnail_url();
                 }
                 $the_content = substr(trim(strip_tags(get_the_content())),0, 100) . "...";




                 $content .= "<a href ='" . $the_permalink . "'>" . $the_title . "</a><br>";
                 $content .= $the_date . "<br>";
                 $content .= "(";
                 $content .= $the_categories;
                 $content .= ")<br>";
                 $content .= $the_tags;
                 $content .= "<br>";
                 $content .= $the_image_url;
                 $content .= "<br>";
                 $content .= $the_content;
                 $content .= "<br><br>";
                 $content .= get_the_tags();

                 array_push($db_array, array($the_title,$the_date,$the_permalink,$the_image_url, $the_categories, $the_tags, $the_content));

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

     echo $content;





      ?>
     </div>
     <?php
 }

 function blog_reporter_options_page()
 {
     add_submenu_page(
         'tools.php',
         'Blog Reporter',
         'Blog Reporter',
         'manage_options',
         'blog-reporter',
         'blog_reporter_options_page_html'
     );
 }
 add_action('admin_menu', 'blog_reporter_options_page');
