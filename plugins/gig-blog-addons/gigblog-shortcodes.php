<?php

// I got this basic information from this page:
// https://www.inkthemes.com/learn-how-to-create-shortcodes-in-wordpress-plugin-with-examples/
// remember the include in the startpage.php

// ------------------------------------------------------------
// this function should format the output of the event's description
// input values:
// gbEventString should be the post_title
// gboutputType is one of several options:
// - random: returns "zufälliger Artikel"
// - latest: returns "Neuester Artikel"
// - fullinfo: returns "Band1 [, Band2, Band3]

function formatGBEventName($gbEventString, $gboutputType)
{
    if($gboutputType == "random")
    {
        $output = 'zuf&auml;lliger Artikel';
    }
    if ($gboutputType == "latest")
    {
        $output = "Neuester Artikel";
    }
    if ($gboutputType == "fullinfo")
    {
        $commaPosition = strpos($gbEventString, ",");
        if ($commaPosition)
        {
            $output = strtok($gbEventString, ",");
        }
        else
        {
            $output = $gbEventString;
        }
    }
    return $output;
}
// ------------------------------------------------------------

// ------------------------------------------
// This function gets an archive of all existing posts. Limited with some exceptions.
// ------------------------------------------
function gb_archive()
{
    $gb_exclude_categories = getExcludedCategories();

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
    'author'           => $gbAutorID,
    'post_status'      => 'publish',
    'category'         => $gb_exclude_categories,
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

// ------------------------------------------
// This function simply gets the latest of all existing posts.
// And then it returns a link to this post, named "Neuster Beitrag".
// ------------------------------------------
function gb_latestPost()
{
    $gb_exclude_categories = getExcludedCategories();
    //$gb_exclude_categories = "-224,-505,-686,-826,-2746,-3289";
    
    $latestArguments = array(
        'posts_per_page'   => 1,
        'order'            => 'date',
        'orderby'          => 'desc',
        'post_type'        => 'post',
        'post_status'      => 'publish',
        'category'         => $gb_exclude_categories,
        'suppress_filters' => true
        );

    $latestPost_array = get_posts( $latestArguments ); 
    $gb_output = '';

    foreach ($latestPost_array as $latestPost)
    {
        $latestTitle = get_post_field('post_title', $latestPost);
        $latestLink = get_permalink($latestPost);
        $gb_output .= '<a href="' . $latestLink .'">';
        $gb_output .= "Neuster Beitrag: ";
        $gb_output .= trim(substr($latestTitle, 0, (strpos($latestTitle, ','))));
        $gb_output .= '</a>';
        // $postTitle = $returnPost->post_title;
        // $postLink = get_permalink($returnPost);

        // $gb_output .= "<li><a href=\".$postLink.\">$postTitle</a><br></li>\n";
    }

    wp_reset_postdata();

    return $gb_output;
}

// ------------------------------------------
// This function simply gets a random post of all existing posts.
// And then it returns a link to this post, named "zufälliger Artikel".
// This could be used for the insta-page.
// ------------------------------------------
function gb_randomPost2()
{
    $gb_exclude_categories = getExcludedCategories();
    //$gb_exclude_categories = "-224,-505,-686,-826,-2746,-3289";
    $queryArguments = array(
        'posts_per_page'   => 1,
        'orderby'          => 'rand',
        'post_type'        => 'post',
        'post_status'      => 'publish',
        'category'         => $gb_exclude_categories,
        'suppress_filters' => true
        );

    $randomPost_array = get_posts( $queryArguments ); 
    $gb_output = '';

    foreach ($randomPost_array as $randomPost)
    {
        //$latestTitle = $latestPost->post_title;
        $randomLink = get_permalink($randomPost);
        $gb_output .= '<a href="' . $randomLink .'">';
        $gb_output .= 'Zuf&auml;lliger Artikel</a>';
    }

    wp_reset_postdata();

    return $gb_output;
}


// ------------------------------------------
// This function simply gets a random post of all existing posts.
// And then it returns a link to this post, named with "Zufälliger Beitrag aus dem Archiv".
// ------------------------------------------

function gb_randomPost()
{
    $gb_exclude_categories = getExcludedCategories();
    //$gb_exclude_categories = "-224,-505,-686,-826,-2746,-3289";
    $randomArguments = array(
    'posts_per_page'   => 1,
    'orderby'          => 'rand',
    'post_type'        => 'post',
    'post_status'      => 'publish',
    'category'         => $gb_exclude_categories,
    'suppress_filters' => true, 
    );
    
    $randomPost_array = get_posts( $randomArguments ); 

    $gb_output = '';

    foreach ($randomPost_array as $randomPost)
    {
        $randomLink = get_permalink($randomPost);
        $gb_output .= '<a href="' . $randomLink .'">';
        $gb_output .= "Zuf&auml;lliger Beitrag";
        $gb_output .= '</a> aus dem Archiv';
    }
    
    wp_reset_postdata();

    return $gb_output; 
}

// ------------------------------------------
// This function gets the number of all posts.
// And then rounds it down to the lower hundreds.
// Can be used to show a rough number of published posts.
// Formats with a "." as the thousands separator
// ------------------------------------------
function gb_postCount()
{
    $numberOfPosts = wp_count_posts()->publish;
    $roundDown = number_format((int)($numberOfPosts / 100) *100 , 0 , ",", ".");

    wp_reset_postdata();

    return $roundDown; 
}

// ------------------------------------------
// This function gets the number of all posts.
// Divides them with the number of posts per page.
// Gets a random number of all pages.
// ------------------------------------------
function gb_randomPage()
{
    $numberOfPosts = wp_count_posts()->publish;
    $optionsPostonPage = get_option('posts_per_page');
    $pagesCounter = (int) ($numberOfPosts / $optionsPostonPage);

    if ($numberOfPosts % $optionsPostonPage != 0)
    {
        $pagesCounter++;
    }

    //wp_reset_postdata();

    $returnRandomNumber = rand(1,$pagesCounter);

    wp_reset_postdata();

    return $returnRandomNumber; 
}


// more information about it:
// https://developer.wordpress.org/plugins/shortcodes/shortcodes-with-parameters/
function gb_archive_person( $person = array(), $content = null, $tag = '' )
{
    
}

//----------------------------------------------------
// This is the shortcode to show all entries from a specific year.
// The shortcode has an array like "year=2021".
// You can get the value of year by accessing the $parameter["year"].
//----------------------------------------------------
function gb_archive_year($parameter)
{

    $gb_year_parameter = $parameter["year"];

    $gb_error ="Please provide a proper year in the shortcode!";

    if (! is_numeric($gb_year_parameter))
    {
        $gb_output = $gb_error;
        return $gb_output;
    }

    $gb_current_year = date("Y");

    if (! (($gb_year_parameter <= $gb_current_year) && ($gb_year_parameter >= 2009)) )
    {
        $gb_output = $gb_error;
        return $gb_output;
    }

    $gb_output = "";

    // There are just some categories, which you don't want to see in your archive:
    $gb_exclude_categories = getExcludedCategories();

    global $post;
    $queryArguments = array(
    'posts_per_page'   => 1000,
    'date_query' => array(
		array(
			'after'    => array(
				'year'  => $gb_year_parameter,
				'month' => 1,
				'day'   => 1,
			),
			'before'    => array(
				'year'  => $gb_year_parameter,
				'month' => 12,
				'day'   => 31,
			),
			'inclusive' => true,
        ),
    ),
    'orderby'          => 'date',
    'order'            => 'desc',
    'post_type'        => 'post','page',
    'post_status'      => 'publish',
    'category'         => $gb_exclude_categories,
    'suppress_filters' => true 
    );
    
    $returnPost_array = get_posts($queryArguments); 

    $gb_output .= "<ul>\n";
    foreach ($returnPost_array as $returnPost)
    {
        $postTitle = $returnPost->post_title;
        $postLink = get_permalink($returnPost);

        $gb_output .= "<li><a href=\".$postLink.\">$postTitle</a><br></li>\n";
    }
    $gb_output .= "</ul>\n";

    // foreach ( $myPosts_array as $post ) : setup_postdata( $post );
    // $eventTitle = the_title('','',false);
    // $publishDate = the_date('Y','','',false);
    // $postpermalink = get_permalink();
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
    // Weiß der Teufel, warum diese Funktion nicht innerhalb von PHP Code läuft.

    // $gb_output .= '<li> <a href="' . $postpermalink;

    // $gb_output .= '">' . $currentBand . '</a><br>';
    // $gb_output .= $eventDate . ', ' . $eventLocation . '</li>';
    // endforeach; 
    // wp_reset_postdata();

    // $gb_output .= "my output!";
    
    wp_reset_postdata();

    return $gb_output; 
}

//----------------------------------------------------
// This is the shortcode to show a quick statistic from a specific year for all cities.
// The shortcode has an array like "year=2021".
// You can get the value of year by accessing the $parameter["year"].
//----------------------------------------------------
function gb_statistics_city($parameter)
{

    $gb_year_parameter = $parameter["year"];
    $gb_error ="Please provide a proper year in the shortcode!";

    if (! is_numeric($gb_year_parameter))
    {
        $gb_output = $gb_error;
        return $gb_output;
    }

    $gb_current_year = date("Y");

    if (! (($gb_year_parameter <= $gb_current_year) && ($gb_year_parameter >= 2009)) )
    {
        $gb_output = $gb_error;
        return $gb_output;
    }

    $gb_output = "<p>";

    // There are just some categories, which you don't want to see in your statistic:
    $gb_exclude_categories = getExcludedCategories();

    global $post;
    $queryArguments = array(
    'posts_per_page'   => 1000,
    'date_query' => array(
		array(
			'after'    => array(
				'year'  => $gb_year_parameter,
				'month' => 1,
				'day'   => 1,
			),
			'before'    => array(
				'year'  => $gb_year_parameter,
				'month' => 12,
				'day'   => 31,
			),
			'inclusive' => true,
        ),
    ),
    'orderby'          => 'date',
    'order'            => 'desc',
    'post_type'        => 'post','page',
    'post_status'      => 'publish',
    'category'         => $gb_exclude_categories, 
    'suppress_filters' => true 
    );

    $returnPost_array = get_posts($queryArguments); 

    //$gb_LocationArray = array("location" => "","city" => "");
    $gb_LocationArray = array();
    $gb_CityArray = array();
    $concertCounter = 0;

    //getting all locations from the post_title and put into an array:
    foreach ($returnPost_array as $returnPost)
    {
        $postTitle = $returnPost->post_title;

        $post_Length = strlen($postTitle);
        $commaLocation1 = strrpos($postTitle, ",");
        //calculation for little strange offset for strrpos
        $commaLocation1neg = (-1)*($post_Length - $commaLocation1+1);
        $commaLocation2 = strrpos($postTitle, ",", $commaLocation1neg)+1;

        //this includes the LOCATION, CITY, i.e. everything behind the second last comma:
        $gb_LocationOnly = substr($postTitle,$commaLocation2);
        $gb_CityOnly = substr($postTitle,$commaLocation1 + 1);
        $concertCounter = array_push($gb_LocationArray, $gb_LocationOnly);
        $cityCounter = array_push($gb_CityArray, $gb_CityOnly);

    }
    //just by accident we received the number of all concerts with this method
    $gb_output .= $concertCounter . " Konzerte im Jahr " . $gb_year_parameter ."<br>";

    //this is, where the magic (i.e. counting) happens
    $gb_Counted1 = array_count_values($gb_LocationArray);
    //and here is some sorting 
    arsort($gb_Counted1);

    $gbcounter1 = 0;

    //this outputs the whole statistic:
    $gb_output .= "<h3>nach Location</h3><ul>\n";
    foreach($gb_Counted1 as $gb_countLoc => $gb_countNum)
    {
        //just check, if it ends with ", Stuttgart" and remove this
        if (substr($gb_countLoc,strpos($gb_countLoc,",")) == ", Stuttgart")
        {
            $gb_countLoc = substr($gb_countLoc,0,strpos($gb_countLoc,","));
        }
        $gb_output .= "<li>".$gb_countLoc . ": " . $gb_countNum."x</li>\n";
        $gbcounter1++;
    }
    $gb_output .= "</ul>";

    //doing it again for the second array:
    //this is, where the magic (i.e. counting) happens, again
    $gb_Counted2 = array_count_values($gb_CityArray);
    //and here is some sorting 
    arsort($gb_Counted2);

    $gbcounter2 = 0;

    //this outputs the whole statistic:
    $gb_output .= "<h3>nach Stadt</h3><ul>\n";
    foreach($gb_Counted2 as $gb_countCity => $gb_countNum)
    {
        //just check, if it ends with ", Stuttgart" and remove this
        //if (substr($gb_countCity,strpos($gb_countCity,",")) == ", Stuttgart")
        //{
        //    $gb_countCity = substr($gb_countLoc,0,strpos($gb_countCity,","));
        //}
        $gb_output .= "<li>".$gb_countCity . ": " . $gb_countNum."x</li>\n";
        $gbcounter2++;
    }
    $gb_output .= "</ul>";
    
    $gb_output .= "</p>\n";
    
    wp_reset_postdata();
    
    return $gb_output; 
    
}

//this function executes a hard SQL statement, which is probably very hard to find with all the categories, we don't want in the statistics
function gb_statistics_year_2( $parameter )
{

    $gb_year_parameter = $parameter["year"];

    $gb_error ="Please provide a proper year in the shortcode!";

    if (! is_numeric($gb_year_parameter))
    {
        $gb_output = $gb_error;
        return $gb_output;
    }

    $gb_current_year = date("Y");

    if (! (($gb_year_parameter <= $gb_current_year) && ($gb_year_parameter >= 2009)) )
    {
        $gb_output = $gb_error;
        return $gb_output;
    }

    $gb_output = "";

    $gb_min_date = $gb_year_parameter . "-01-01 00:00:00";
    $gb_max_date = $gb_year_parameter . "-12-31 23:59:59";
    
    global $wpdb;
    $results = $wpdb->get_results("SELECT *, post_title as GB_POST,
            REVERSE(SUBSTRING_INDEX(REVERSE(post_title), ',' , 2)) as gb_location,
            count(REVERSE(SUBSTRING_INDEX(REVERSE(post_title), ',' , 2))) as gb_counter
        FROM {$wpdb->prefix}posts 
        WHERE   ( post_type = 'post' 
                AND post_status = 'publish'
                AND post_date >= '$gb_min_date'
                AND post_date <= '$gb_max_date'
                )
            GROUP BY gb_location
            ORDER BY COUNT(gb_location) desc
        ");

    //print_r($results);

    foreach ($results as $result)
    {
        $gb_output .= $result->gb_counter ."x @ ";

        //cut off "Stuttgart", but keep the city for everything else
        $mod_location = $result->gb_location;
        if (strstr($mod_location,",") == ", Stuttgart")
        {
            $gb_output .= substr($mod_location,0, strpos($mod_location,","));
        }
        else
        {
            $gb_output .= $mod_location;
        }
        $gb_output .=  "<br>";
    }

    return($gb_output);

}



// ==============================================0
//  Begin of helper Functions
// ==============================================0

function gb_format_post($gb_post_title = '', $outputType = '')
{
    $gb_exclude_categories = getExcludedCategories();

    switch ($gb_post_title)
    {
        case "latest":
            $queryArguments = array(
                'posts_per_page'   => 1,
                'order'            => 'date',
                'orderby'          => 'desc',
                'post_type'        => 'post',
                'post_status'      => 'publish',
                'category'         => $gb_exclude_categories,
                'suppress_filters' => true
                );
            break; 
        case "random":
            $queryArguments = array(
                'posts_per_page'   => 1,
                'orderby'          => 'rand',
                'post_type'        => 'post',
                'post_status'      => 'publish',
                'category'         => $gb_exclude_categories,
                'suppress_filters' => true 
                );
            break;
        case "all":
            $queryArguments = array(
                'posts_per_page'   => -1,
                'orderby'          => 'date',
                'order'            => 'desc',
                'post_type'        => 'post',
                'post_status'      => 'publish',
                'category'         => $gb_exclude_categories,
                'suppress_filters' => true 
                );
            break;
    }

    $returnPost_array = get_posts($queryArguments); 

    foreach ($returnPost_array as $returnPost)
    {
        $postTitle = $returnPost->post_title;
        $postLink = get_permalink($returnPost);
        
        if ($outputType == "NameLink")
        {
            $gb_output = '<a href="' . $postLink .'">';
            $gb_output .= formatGBEventName($postTitle, "fullinfo");
            $gb_output .= '</a>';
        }
        if ($outputType == "randomLink")
        {
            $gb_output = '<a href="' . $postLink .'">';
            $gb_output .= formatGBEventName($postTitle, "random");
            $gb_output .= '</a>';
        }
        if ($outputType == "latestLink")
        {
            $gb_output = '<a href="' . $postLink .'">';
            $gb_output .= formatGBEventName($postTitle, "latest");
            $gb_output .= '</a>';
        }
    }
    return $gb_output;
}


function gb_get_post($postInfo = '', $outputType = '')
{
    $gb_exclude_categories = getExcludedCategories();
    //$gb_exclude_categories = "-224,-505,-686,-826,-2746,-3289";

    switch ($postInfo)
    {
        case "latest":
            $queryArguments = array(
                'posts_per_page'   => 1,
                'order'            => 'date',
                'orderby'          => 'desc',
                'post_type'        => 'post',
                'post_status'      => 'publish',
                'category'         => $gb_exclude_categories,
                'suppress_filters' => true
                );
            break; 
        case "random":
            $queryArguments = array(
                'posts_per_page'   => 1,
                'orderby'          => 'rand',
                'post_type'        => 'post',
                'post_status'      => 'publish',
                'category'         => $gb_exclude_categories,
                'suppress_filters' => true 
                );
            break;
        case "all":
            $queryArguments = array(
                'posts_per_page'   => -1,
                'orderby'          => 'date',
                'order'            => 'desc',
                'post_type'        => 'post',
                'post_status'      => 'publish',
                'category'         => $gb_exclude_categories,
                'suppress_filters' => true 
                );
            break;
    }

    $returnPost_array = get_posts($queryArguments); 

    foreach ($returnPost_array as $returnPost)
    {
        $postTitle = $returnPost->post_title;
        $postLink = get_permalink($returnPost);
        
        if ($outputType == "NameLink")
        {
            $gb_output = '<a href="' . $postLink .'">';
            $gb_output .= formatGBEventName($postTitle, "fullinfo");
            $gb_output .= '</a>';
        }
        if ($outputType == "randomLink")
        {
            $gb_output = '<a href="' . $postLink .'">';
            $gb_output .= formatGBEventName($postTitle, "random");
            $gb_output .= '</a>';
        }
        if ($outputType == "latestLink")
        {
            $gb_output = '<a href="' . $postLink .'">';
            $gb_output .= formatGBEventName($postTitle, "latest");
            $gb_output .= '</a>';
        }
    }
    return $gb_output;
}

// ==============================================0
//  some more helper Functions
// ==============================================0

function getExcludedCategories()
{
    $excludeCategorys = array
    (
    "Interview",
    "Vorankündigung",
    "Nachruf",
    "Verlosung",
    "Top Liste",
    "Adventskalender");

    $gb_exclude_categories = "";

    for($i = 0; $i < count($excludeCategorys); ++$i)
    {
        if (get_categoryID_from_Name($excludeCategorys[$i]) <> 0)
            $gb_exclude_categories .= "-" . get_categoryID_from_Name($excludeCategorys[$i]).",";
    }
    //$gb_exclude_categories = "-55,-5,-221,";
    //$gb_exclude_categories = "-224,-505,-686,-826,-2746,-3289";
    return $gb_exclude_categories;
}

function get_categoryID_from_Name($categoryName)
{
    $wpCategory = get_term_by('name', $categoryName, 'category');
    if ($wpCategory)
    {
        return $wpCategory->term_id;
    }
    else
    {
        return 0;
    }
}

?>