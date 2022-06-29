<?php

// I got this basic information from this page:
// https://www.inkthemes.com/learn-how-to-create-shortcodes-in-wordpress-plugin-with-examples/
// remember the include in the startpage.php

function gb_archive()
{

    global $post;
    $postArguments = array(
    'posts_per_page'   => 1000,
    'orderby'          => 'ID',
    'order'            => 'DESC',
    'post_type'        => 'post',
    'author'    => $gbAutorID,
    'post_status'      => 'publish',
    'category'         => '-224,-505,-686,-826,-2746,-3289',
    'suppress_filters' => true 
    );
    // Interview 224
    // Vorankündigung 505
    // Nachruf 686
    // Verlosung 826
    // Top Liste 2746
    // Adventskalender 3289

    $myPosts_array = get_posts( $postArguments ); 
    $compareYear = '1'; 
    echo '<ul>';
    foreach ( $myPosts_array as $post ) : setup_postdata( $post );
    $eventTitle = the_title('','',false);
    $publishDate = the_date('Y','','',false);
    $firstComma = strpos($eventTitle, ',') +1;
    $eventTitleNoBand = trim(substr($eventTitle,$firstComma));
    $nextLetter = substr($eventTitleNoBand,0,1);
    if ($compareYear != $publishDate)
    {
        // hier müsste man überlegen, ob man die <ul></ul> Sache noch entfernen könnte, so nicht:
        //if ($compareYear != 1)
        //    echo '</ul>';
        if (is_numeric($publishDate))
        {
            $compareYear = $publishDate;
            echo '</ul><h3>' . $publishDate . '</h3><ul>';
        }
    }
    if (is_numeric($nextLetter))
    {
    //  Bandname ist schon fertig:
        $currentBand = trim(substr($eventTitle,0, $firstComma -1));
    }
    else
    {
    //  Bandnamen zusammenbasteln - hier müsste noch ein besserer Algorithmus her
        $nextComma = strpos($eventTitleNoBand, ',') +1;
        $eventTitleNoBand = trim(substr($eventTitleNoBand, $nextComma));
        $currentBand = substr($eventTitle, 0 , $firstComma + $nextComma);
    }
    $secondComma= strpos($eventTitleNoBand, ',');
    $eventDate = substr($eventTitleNoBand ,0, $secondComma);
    $eventLocation = substr($eventTitleNoBand , $secondComma+1);
    // Weiß der Teufel, warum diese Funktion nicht innerhalb von PHP Code läuft.
    ?>
    <li> <a href="<?php the_permalink();?>
    <?php
    echo '">' . $currentBand . '</a><br>';
    echo $eventDate . ', ' . $eventLocation . '</li>';
    endforeach; 
    wp_reset_postdata();

    $gb_output = "my output!";
    
    wp_reset_postdata();

    return $gb_output; 
}

?>
