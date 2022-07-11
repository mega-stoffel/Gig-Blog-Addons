<?php

// I got this basic information from this page:
// https://www.inkthemes.com/learn-how-to-create-shortcodes-in-wordpress-plugin-with-examples/
// remember the include in the startpage.php

// ------------------------------------------------------------
// this function should format the output of the event's description
// input values:
// gbEventString should be the post_title
// gbrandomTitle is boolean, true/false and will just format it differently
function formatGBEventName($gbEventString, $gbrandomTitle)
{
    if($gbrandomTitle)
    {
        $output = 'zuf&auml;lliger Artikel';
    }
    else
    {
        $commaPosition = strpos($gbEventString, ",");
        if ($commaPosition)
        {
            $output = strtok($gbEventString, ",");
        }
        //$output = '';
    }
    return $output;
}
// ------------------------------------------------------------

function gb_archive()
{
    if (!(isset($gbAutorID)))
    {
        $gbAutorID = 0;
    }
    
    global $post;
    $postArguments = array(
    'posts_per_page'   => 1000,
    'orderby'          => 'date',
    'order'            => 'desc',
    'post_type'        => 'post','page',
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
    $gb_output = '<ul>';

    foreach ( $myPosts_array as $post ) : setup_postdata( $post );
    $eventTitle = the_title('','',false);
    $publishDate = the_date('Y','','',false);
    $postpermalink = get_permalink();
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
            $gb_output .= '</ul><h3>' . $publishDate . '</h3><ul>';
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

    $gb_output .= '<li> <a href="' . $postpermalink;

    $gb_output .= '">' . $currentBand . '</a><br>';
    $gb_output .= $eventDate . ', ' . $eventLocation . '</li>';
    endforeach; 
    wp_reset_postdata();

    $gb_output .= "my output!";
    
    wp_reset_postdata();

    return $gb_output; 
}

function gb_randomPost()
{
    
    $randomArguments = array(
    'posts_per_page'   => 1,
    'orderby'          => 'rand',
    'post_type'        => 'post',
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

    $randomPost_array = get_posts( $randomArguments ); 
    $gb_output = '';

    foreach ( $randomPost_array as $randomPost )
    {
        $randomTitle = $randomPost->post_title;
        $randomPostLink = get_permalink($randomPost);
        $gb_output .= '<a href="' . $randomPostLink .'">';
        //$gb_output .= $randomTitle . '</a>';
        $gb_output .= formatGBEventName($randomTitle, false);
    }
    
    // $firstComma = strpos($eventTitle, ',') +1;
    // $eventTitleNoBand = trim(substr($eventTitle,$firstComma));
    // $nextLetter = substr($eventTitleNoBand,0,1);
    // if ($compareYear != $publishDate)
    // {
    //     // hier müsste man überlegen, ob man die <ul></ul> Sache noch entfernen könnte, so nicht:
    //     //if ($compareYear != 1)
    //     //    echo '</ul>';
    //     if (is_numeric($publishDate))
    //     {
    //         $compareYear = $publishDate;
    //         $gb_output .= '</ul><h3>' . $publishDate . '</h3><ul>';
    //     }
    // }
    // if (is_numeric($nextLetter))
    // {
    // //  Bandname ist schon fertig:
    //     $currentBand = trim(substr($eventTitle,0, $firstComma -1));
    // }
    // else
    // {
    // //  Bandnamen zusammenbasteln - hier müsste noch ein besserer Algorithmus her
    //     $nextComma = strpos($eventTitleNoBand, ',') +1;
    //     $eventTitleNoBand = trim(substr($eventTitleNoBand, $nextComma));
    //     $currentBand = substr($eventTitle, 0 , $firstComma + $nextComma);
    // }
    // $secondComma= strpos($eventTitleNoBand, ',');
    // $eventDate = substr($eventTitleNoBand ,0, $secondComma);
    // $eventLocation = substr($eventTitleNoBand , $secondComma+1);
    // // Weiß der Teufel, warum diese Funktion nicht innerhalb von PHP Code läuft.

    // $gb_output .= '<li> <a href="' . $postpermalink;

    // $gb_output .= '">' . $currentBand . '</a><br>';
    // $gb_output .= $eventDate . ', ' . $eventLocation . '</li>';
    // endforeach; 
    // wp_reset_postdata();

    //$gb_output .= "my output!";
    
    wp_reset_postdata();

    return $gb_output; 
}

?>
