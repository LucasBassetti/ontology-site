<?php
/*
Template Name: Today's Workout Template
*/
get_header('avatar'); 

if(empty(get_user_meta($user_id, 'isCoach', true))) {
   //do nothing 
} else {
    header('Location: http://traineffective.com/team-dashboard/');
}
changeNotestoJSONforAllUsers();

?>

    <?php
                $current_date = date("jS F Y");
                $day = date('l');
                $user_id = get_current_user_id();
                $notesArray = get_user_meta( $user_id, 'notesArray', true );
                if (empty($notesArray)) {
                    $notesArray = [];
                } 
                $today = date("m/d/Y");

    ?>
    <input type='button' data-infinite='10' data-loading='false' data-reply='0' id='load-more-notes-dashboard' class='hidden'/>


    <?php
        $allNotesCombined = array();
        $notesCategories = array();
        $id = 0;
        foreach($notesArray as $key => $value) {
            if(is_array($value)) {
                $length = count($value);
                array_push($notesCategories, $key);
                for($x = 0; $x < $length; $x++) {
                    $id++;
                    $date = $value[$x][0];
                    $note = $value[$x][1];
                    $currentWorkout =  $value[$x][2];
                    $inSeries =  $value[$x][3];
                    $trainedFor = $value[$x][4];
                    $rating = $value[$x][5];
                    $tmp =  array($x,$date, $key, $note , $currentWorkout, $inSeries, $trainedFor, $rating);
                    $allNotesCombined[$id] = $tmp;
                 }
            }
        } /* koniec iterowania po Notesarray */






    ?>

<?php

/* Call the ThickBox function for lightbox purposes*/
add_thickbox();

date_default_timezone_set('Europe/Berlin');

/* ====================================
=======  VARIABLES ====================
======================================*/

$current_date = date("jS F Y");
$day = date('l');
$user_id = get_current_user_id();
$today = date("m/d/Y");
$activeWorkouts = get_user_meta( $user_id, 'activeWorkouts', true );
//$activeWorkoutsArray =  json_decode($activeWorkouts, TRUE);
//$activeWorkoutsOriginal = json_decode($activeWorkouts, TRUE);
$tomorrow = new DateTime('tomorrow');
$tomorrow = $tomorrow->format('m/d/Y');
$user_info = get_userdata($user_id);
$registerdate = $user_info->user_registered;
$time = strtotime($registerdate);
$now = time(); // or your date as well
$datediff = $now - $time;
$datediffDays = floor($datediff/(60*60*24));
$tomorrowDayName = date("l", time()+86400);
$tomorrow_date = date("jS F Y", time()+86400);
$exp = get_user_meta( $user_id, 'exp', true );
$touches = get_user_meta( $user_id, 'touches', true );
$shots = get_user_meta( $user_id, 'shots', true );
$passes = get_user_meta( $user_id, 'passes', true );
$distance = get_user_meta( $user_id, 'distance', true );
$gamebrained = get_user_meta( $user_id, 'gamebrained', true );
$classroomed = get_user_meta( $user_id, 'classroomed', true );
$notesposted = get_user_meta( $user_id, 'notesposted', true );
$commented = get_user_meta( $user_id, 'commented', true );
$level = get_user_meta( $user_id, 'level', true );
$levelLimit = get_user_meta( $user_id, 'levelLimit', true );
$levelStarts = get_user_meta( $user_id, 'levelStarts', true );
$badges = get_user_meta( $user_id, 'badges', true );
$current_points = $exp-$levelStarts;
$progress_bar = ($current_points/($levelLimit-$levelStarts))*100;
$activities = get_user_meta( $user_id, 'activity', true );
if (empty($activities)) { $activities = 0;
} else { $activities= JSON_decode($activities);
}
/* GETTING Badges COUNTER */

    $badgesCounter = get_user_meta( $user_id, 'badgesCounter', true );
    if (empty($badgesCounter)) {
    $badgesCounter = 0;
    } else {
    $badgesCounter= JSON_decode($badgesCounter);
    }
$streak = get_user_meta( $user_id, 'streak', true );

    if (empty($streak)) {
    $streak = 0;
    } else {
    $streak= JSON_decode($streak);
    }
$response = get_user_meta( $user_id, 'scheduledata', true );
$activeWorkout = get_user_meta( $user_id, 'activeWorkouts', true );
$notificationsFeedArray = get_user_meta( $user_id, 'notificationsFeed', true );
$newNotificationCounter = get_user_meta( $user_id, 'newNotificationCounter', true );
if (empty($notificationsFeedArray)) { $notificationsFeedArray = Array(); }
if (empty($newNotificationCounter)) { $newNotificationCounter = 0; }

//INICIALIZE GAMIFICATION STATS IF THEY ARE EMPTY
if (!$exp) { $exp=0;  $level= 1; $levelLimit = 500; $levelStarts = 0;
update_user_meta($user_id, 'exp', $exp);
update_user_meta( $user_id, 'level', 1 );
update_user_meta( $user_id, 'levelLimit', 500 );
update_user_meta( $user_id, 'levelStarts', 0 );}
if (!$touches) { $touches=array (0,0,0,0,0); update_user_meta($user_id, 'touches', $touches);}
if (!$shots) { $shots=array (0,0,0,0); update_user_meta($user_id, 'shots', $shots);}
if (!$passes) { $passes=array (0,0,0); update_user_meta($user_id, 'passes', $passes);}
if (!$distance) { $distance=array (0,0,0,0); update_user_meta($user_id, 'distance', $distance);}
if (!$gamebrained) { $gamebrained=0; update_user_meta($user_id, 'gamebrained', $gamebrained);}
if (!$classroomed) { $classroomed=0; update_user_meta($user_id, 'classroomed', $classroomed);}
if (!$notesposted) { $notesposted=0; update_user_meta($user_id, 'notesposted', $notesposted);}
if (!$commented) { $commented=0; update_user_meta($user_id, 'commented', $commented);}

if (is_string($touches)) $touches= JSON_decode($touches);
if (is_string($shots)) $shots=JSON_decode($shots);
if (is_string($passes))$passes=JSON_decode($passes);
if (is_string($distance)) $distance=JSON_decode($distance);

// BADGES
$bargs = array(
'posts_per_page' => -1,
'post_type' => 'badges',
);
$badges_loop = new WP_Query($bargs);

$recentBadges = get_user_meta( $user_id, 'recentBadges', true );
if (!$recentBadges) $recentBadges = Array("0","0","0","0","0","0","0","0");

$badgesArray = get_user_meta( $user_id, 'badgesArray', true );
?>
<div id='badgesResponse'><?php if ($badgesArray){echo($badgesArray);} ?></div>

<?php
if (is_string($badgesArray)){ $badgesArray= JSON_decode($badgesArray); }
if (is_string($recentBadges)){ $recentBadges= JSON_decode($recentBadges); }

$recentBadgesImages = Array("0","0","0","0","0","0","0","0");
$recentBadgesDesc = Array("0","0","0","0","0","0","0","0");
$i=0;

/* GETTING RECENT BADGES IMAGES */
foreach ($recentBadges as $recent) {
    while ($badges_loop->have_posts()) : $badges_loop->the_post();
        $badgeID = get_the_ID();
        $badge_title = get_the_title();
        if ($badge_title == $recent){
            $thumb_id = get_post_thumbnail_id();
            $thumb_url = wp_get_attachment_image_src($thumb_id,'thumbnail-size', true);
            $recentBadgesImages[$i] = $thumb_url[0];
            $recentBadgesDesc[$i] = get_the_content();
             $i++;
         }
    endwhile;
}


?>


<!--  setting up Arrays of categories filters  -->
<?php
$programsargs = array(
  'type' => 'post',
  'orderby' => 'name',
  'order' => 'ASC',
  'depth' => 1,
  'hierarchical' => true,
  'taxonomy' => 'category',
  'child_of' => '41'
);
$workoutsargs = array(
    'type' => 'post',
    'orderby' => 'name',
    'order' => 'ASC',
    'depth' => 1,
    'hierarchical' => true,
    'taxonomy' => 'category',
    'child_of' => '43'
);
?>


<?php get_template_part('notesLightbox');?>


<!-- BLOCKS HOVER FOR SCHEDULE HISTORY -->
    <div id="block-hover-stats" class="notes-shadow hidden">
        <div class="block-lightbox-header how-long-header"><span class="block-name"></span></div>
        <div class="chart-row row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 chart-col">
                <div class="touches-chart-block chart-container"></div>
                <div class="ribbon">
                    <div class="ribbon-stitches-top"></div>
                    <strong class="ribbon-content"><h4>Touches</h4></strong>
                    <div class="ribbon-stitches-bottom"></div>
                </div>
                <span class="chart-plus block-touches-stats"></span>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 chart-col">
                <div class="shots-chart-block chart-container"></div>
                <div class="ribbon">
                    <div class="ribbon-stitches-top"></div>
                    <strong class="ribbon-content"><h4>Shots</h4></strong>
                    <div class="ribbon-stitches-bottom"></div>
                </div>
                <span class="chart-plus block-shots-stats"></span>
            </div>
        </div>
        <div class="chart-row row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 chart-col">
                <div class="passes-chart-block chart-container"></div>
                <div class="ribbon">
                    <div class="ribbon-stitches-top"></div>
                    <strong class="ribbon-content"><h4>Passes</h4></strong>
                    <div class="ribbon-stitches-bottom"></div>
                </div>
                <span class="chart-plus block-passes-stats"></span>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 chart-col">
                <div class="distance-chart-block chart-container"></div>
                <div class="ribbon">
                    <div class="ribbon-stitches-top"></div>
                    <strong class="ribbon-content"><h4>Distance</h4></strong>
                    <div class="ribbon-stitches-bottom"></div>
                </div>
                <span class="chart-plus block-distance-stats"><span class="down-index">km</span></span>
            </div>
        </div>
    </div>

    <div id="day-hover-stats" class="notes-shadow hidden">
        <div class="how-long-header day-lightbox-header "><span >Day Summary</span></div>
        <h4 class="day-exp"></h4>
        <div class="chart-row row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 chartnter-col">
                <div class="chart">
                    <div class="touches-chart-day chart-container"></div>
                    <div class="ribbon">
                        <div class="ribbon-stitches-top"></div>
                        <strong class="ribbon-content"><h4>Touches</h4></strong>
                        <div class="ribbon-stitches-bottom"></div>
                    </div>
                    <span class="chart-plus day-touches-stats"></span>
                </div>
                 <ul class="chart-ul">
                    <li class="li-a">Dribble <span class="grey day-dribble-stats"></span> </li>
                    <li class="li-b">First <span class="grey day-first-stats"></span> </li>
                    <li class="li-c">Aerial <span class="grey day-aerial-stats"></span> </li>
                    <li class="li-d">Juggle <span class="grey day-juggle-stats"></span> </li>
                </ul>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 chart-col">
                <div class="chart">
                    <div class="shots-chart-day chart-container"></div>
                    <div class="ribbon">
                        <div class="ribbon-stitches-top"></div>
                        <strong class="ribbon-content"><h4>Shots</h4></strong>
                        <div class="ribbon-stitches-bottom"></div>
                    </div>
                    <span class="chart-plus day-shots-stats"></span>
                </div>
                <ul class="chart-ul">
                    <li class="li-a">Short range <span class="grey day-short-shots-stats"></span> </li>
                    <li class="li-b">Long range <span class="grey day-long-shots-stats"></span> </li>
                    <li class="li-c">Aerial Shots <span class="grey day-aerial-shots-stats"></span> </li>
                </ul>
            </div>
        </div>
        <div class="chart-row row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 chart-col">
                <div class="chart">
                    <div class="passes-chart-day chart-container"></div>
                    <div class="ribbon">
                        <div class="ribbon-stitches-top"></div>
                        <strong class="ribbon-content"><h4>Passes</h4></strong>
                        <div class="ribbon-stitches-bottom"></div>
                    </div>
                    <span class="chart-plus day-passes-stats"></span>
                </div>
                <ul class="chart-ul">
                    <li class="li-a">Short passes <span class="grey day-short-passes-stats"></span> </li>
                    <li class="li-b">Long passes <span class="grey day-long-passes-stats"></span> </li>
                </ul>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 chart-col">
                <div class="chart">
                    <div class="distance-chart-day chart-container"></div>
                    <div class="ribbon">
                        <div class="ribbon-stitches-top"></div>
                        <strong class="ribbon-content"><h4>Distance</h4></strong>
                        <div class="ribbon-stitches-bottom"></div>
                    </div>
                    <span class="chart-plus day-distance-stats"><span class="down-index">km</span></span>
                </div>
                <ul class="chart-ul">
                    <li class="li-a">Jogging <span class="grey day-jogging-stats"><span class="down-index">km</span></span> </li>
                    <li class="li-b">Hight Tempo <span class="grey day-high-tempo-stats"><span class="down-index">km</span></span> </li>
                    <li class="li-c">Sprints <span class="grey day-sprints-stats"><span class="down-index">km</span></span> </li>
                </ul>
            </div>
        </div>
    </div>



<!-- LOADING CIRCLE  -->
    <div id="temp_load" class="hidden" style="text-align:center"> <img src="../wp-content/themes/Effective/img/ajax-loader.gif" /></div>
    <div class="container"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="quickStartTrigger"><i class="fa fa-question-circle"></i></div></div></div>
    <div class="container ">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="dashboard-top">
                <div class="pad-bot pad-top col-lg-12 col-md-12 col-sm-12 col-xs-12 overlay comp-parent">
                    <h4>Workout's for: <span class="row-date"><?php echo(date('l'))?>, <?php echo(date("jS F Y"))?></span></h4>
                    <div class="no-workouts hidden">
                        <h4 style="margin-top:40px; font-size:18px;">You have no activities scheduled today.</br> Reschedule below to add a new block!</h4>
                    </div>
                    <div class="completed-workouts completed-day">
                        <h2>WORKOUTS COMPLETE!</h2>
                        <h4>Enjoy the rest of the day</h4>
                        <i class="fa fa-check completed-mark"></i>
                        <span class="completed-bot">Want to do more? <a href="http://www.traineffective.com/game-brain/">Browse the Game Brain</a> OR Learn and be motivated from the <a href="http://www.traineffective.com/classroom/">Classroom</a></span>
                    </div>

                    <div class="completed-workouts rest-workouts">
                        <h2>REST DAY</h2>
                        <h4>Recharge your Body and Mind</h4>
                        <i class="fa fa-check completed-mark"></i>
                        <span class="rest-description">Relax. Take a walk. Hang out with friends. Watch a movie. Play video games. Stretch. Take a hot bath. Listen to music. Clean your room. Read. Plan your holidays. Write your thoughts in a diary. Treat yourself. Focus on the present moment.</span>
                        <span class="completed-bot">Want to do more? <a href="http://www.traineffective.com/game-brain/">Browse the Game Brain</a> OR Learn and be motivated from the <a href="http://www.traineffective.com/classroom/">Classroom</a></span>
                    </div>
                    <div class="today-container">

                    </div>
                </div>
            </div>
        </div>
    </div>
<!--------------------------------------------------------- SCHEDULE  ------------------------------------------------------>
<div class="container">
<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 remove-padding-all dashboard-left-column">
    <div class="dashboard-categories-slider draganddrop-schedule-only pad-bot" id="draganddrop">
        <!-- <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 schedule-col schedule-col-centered schedule-col-overflow-hidden"> -->
        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 schedule-col schedule-col-centered">
            <div class="categories-filter">
                <a class="btn btn-primary program-cat-btn" href="#">Programs</a>
                <a class="btn btn-primary workout-cat-btn" href="#">Workouts</a>
                <a class="btn btn-primary cross-cat-btn" href="#">Cross Training</a>
                <a class="btn btn-primary team-cat-btn" href="#">Team</a>
                <a class="btn btn-primary rest-cat-btn" href="#">Rest Days</a>
                <a class="btn btn-primary others-cat-btn" href="#">All</a>
            </div>


            <div id="planner-slider" class="planner-slider-overflow-hidden">
                <div class="planner-slider-outer ">
                    <div class="planner-slider-inner">
                    <?php
                        $categories = get_categories($catargs);
                        $workoutsNum = array();
                        $crossTrainingsArray = array();
                        $programsProgressArray = array();
                        $workoutsBackgrounds = array();
                        $workoutsColors = array();
                        $workoutsPhotos = array();
                        $workoutsIcons = array();
                        $workoutTitle = array();
                        foreach($categories as $category) {
                            if ($category->term_id != 29 and $category->name != 'Programs' and $category->name != 'Normal-Workouts' and $category->parent == 43) {
                            ?>

                            <?php
                                // Creating array about workouts blocks date (color, block poto etc)
                                $category_name = $category->name ;
                                $num_workouts = get_field( "number_of_workouts",$category );
                                if (empty($num_workouts)) { $num_workouts= "1";}
                                $workoutsNum[html_entity_decode($category_name)] = $num_workouts;
                                $programsProgressArray[html_entity_decode($category_name)] = [1, $num_workouts];
                                $block_background_color = get_field( "block_background_color",$category );
                                $block_text_color = get_field( "block_text_color",$category );
                                $block_background_photo = get_field( "block_background_photo",$category );
                                $block_custom_icon = get_field( "block_custom_icon",$category );
                                $workoutsBackgrounds[html_entity_decode($category_name)] = $block_background_color;
                                $workoutsColors[html_entity_decode($category_name)] = $block_text_color;
                                $workoutsPhotos[html_entity_decode($category_name)] = $block_background_photo;
                                $workoutsIcons[html_entity_decode($category_name)] = $block_custom_icon;
                            ?>
                            <div class="slider-item
                                <?php /*adding proper class dependent on category */
                                     if ($category->parent == 43){ echo('program-item');}
                                ?> "
                                style=" <?php if ($block_background_photo === false) {
                                    echo 'background:'.$block_background_color;
                                } else {
                                    echo 'background-image:url('.$block_background_photo['url'].');';
                                }?>;   background-size: contain; ">


                                <?php if ($block_background_photo !== false) { echo '<div class="slider-item-overlay"></div>'; } ?>


                                <?php 
                                    //display new label for new workouts for all users 
                                    // 212 - Strength Program Block 
                                   // if ($category->term_id == 212){  ?>
                                     <!--    <div class="new-block-label">NEW!</div> -->

                                <?php // }
                                ?>

                                <div class="slider-item-inner">
                                    <div class="item-link" >
                                        <?php if ($block_custom_icon === false) { ?>
                                            <span style="color: <?php echo $block_text_color?>;"  ><?php echo $category_name;?></span>
                                        <?php } else { ?>
                                            <span class="block-with-icon" style=" <?php echo 'background-image:url('.$block_custom_icon['url'].');'; ?>;" ><?php echo $category_name;?></span>

                                        <?php } ?>
                                    </div>
                                </div>

                                  <a class="block-preview item-link thickbox" href="<?php echo get_category_link( $category ); ?>?TB_iframe=true&width=full&height=full" data-workoutnum="<?php echo($num_workouts)?>" data-name="<?php  echo $category_name;?>">
                                    <i class="fa fa-question-circle"></i></a>
                            </div>   <!-- end of slider item -->


                           <?php } // end of if
                        } //end of for each ?>

                        <!-- LOADING WORKOUTS BLOCKS  -->

                        <?php $args = array(
                            'cat'            => '85',
                            'posts_per_page' => -1
                        ); ?>
                        <?php query_posts($args);?>

                            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

                                <?php // here determining the $display variable . Getting workout id and the days difference
                                    $display = true;                 

                                    /* HERE HIDE NEW BLOCKS FOR NEW USERS */

                                    /* workouts to delay
                                     1 DAY The Anywhere Workout B           | ID = 1902 
                                     3 DAYS Bodystrength Workout (B) -      | ID = 5291
                                     5 DAYS Wall Workout A                  | ID = 5984
                                     7 DAYS High Velocity Workout           | ID = 1899
                                     10 DAYS Power Up Workout               | ID = 5292
                                     15 DAYS Anywhere Workout C             | ID = 5980
                                     22 DAYS Crafty Controller Match-Prep   | ID = 5996
                                     30 DAYS The Striker Match-Prep         | ID = 6002
                                     40 DAYS The Wall Workout B             | ID = 5988
                                     50 DAYS The Aerial Workout             | ID = 1906
                                     58 DAYS The Space Maker Workout        | ID = 5952          
                                     65 DAYS The Dominator Match-Prep       | ID = 5999
                                     75 DAYS General Dribbling Workout A    | ID = 5945
                                     85 DAYS Freestyle Footwork Workout     | ID = 5949
                                     95 DAYS Forward Bomber Workout         | ID = 5993
                                     105 DAYS 2-Player Tight Spaces Workout | ID = 5990
                                     120 DAYS General Dribbling Workout B   | ID = 5977
                                    */
                                                 
                                    if ( ($datediffDays<1) and ($post->ID == 1902) ){ $display = false; } 
                                    if ( ($datediffDays<3) and ($post->ID == 5291) ){ $display = false; } 
                                    if ( ($datediffDays<5) and ($post->ID == 5984) ){ $display = false; } 
                                    if ( ($datediffDays<7) and ($post->ID == 1899) ){ $display = false; } 
                                    if ( ($datediffDays<10) and ($post->ID == 5292) ){ $display = false; } 
                                    if ( ($datediffDays<15) and ($post->ID == 5980) ){ $display = false; } 
                                    if ( ($datediffDays<22) and ($post->ID == 5996) ){ $display = false; } 
                                    if ( ($datediffDays<30) and ($post->ID == 6002) ){ $display = false; } 
                                    if ( ($datediffDays<40) and ($post->ID == 5988) ){ $display = false; } 
                                    if ( ($datediffDays<50) and ($post->ID == 1906) ){ $display = false; } 
                                    if ( ($datediffDays<58) and ($post->ID == 5952) ){ $display = false; } 
                                    if ( ($datediffDays<65) and ($post->ID == 5999) ){ $display = false; } 
                                    if ( ($datediffDays<75) and ($post->ID == 5945) ){ $display = false; } 
                                    if ( ($datediffDays<85) and ($post->ID == 5949) ){ $display = false; } 
                                    if ( ($datediffDays<95) and ($post->ID == 5993) ){ $display = false; } 
                                    if ( ($datediffDays<105) and ($post->ID == 5990) ){ $display = false; } 
                                    if ( ($datediffDays<120) and ($post->ID == 5977) ){ $display = false; } 

                                    //check if user registered before that amend was released , in that case don't hide the content
                                    //$registerdate = '2016-06-17';
                                    $registrationDate = new DateTime($registerdate);
                                    $releaseDate = new DateTime('2016-11-15');

                                    if($registrationDate <= $releaseDate) {
                                        $display = true;
                                    }


                                ?>

                                <?php // 3 workouts will be hidden when user is freshly registerd. Here the flag variable that determines if we should display workout or not.

                                if ($display){ ?>
                                    <?php // Tworzenie tablicy workoutow
                                        $workoutsNum[html_entity_decode(get_the_title())] = '2000';

                                        //getting block colors
                                        $block_background_color = get_field( "block_background_color",$post);
                                        $block_text_color = get_field( "block_text_color",$post);
                                        $block_background_photo = get_field( "block_background_photo",$post );
                                        $block_custom_icon = get_field( "block_custom_icon",$post );
                                        $workoutsBackgrounds[html_entity_decode(get_the_title())] = $block_background_color;
                                        $workoutsColors[html_entity_decode(get_the_title())] = $block_text_color;
                                        $workoutsPhotos[html_entity_decode(get_the_title())] = $block_background_photo;
                                        $workoutsIcons[html_entity_decode(get_the_title())] = $block_custom_icon;
                                        $newLabel = false ;
                                    ?>

                                    <div class="slider-item workout-item"
                                        style=" <?php if ($block_background_photo === false) {
                                                    echo 'background:'.$block_background_color;
                                                } else {
                                                    echo 'background-image:url('.$block_background_photo['url'].'); background-size: contain;';
                                                }?>">
                                        <?php
                                            /* HERE DETERMINE IF SHOW THE NEW LABEL OR NOT*/    

                                                /* workouts to delay
                                                 7 DAY The Anywhere Workout B           | ID = 1902 
                                                 10 DAYS Bodystrength Workout (B) -      | ID = 5291
                                                 12 DAYS Wall Workout A                  | ID = 5984
                                                 14 DAYS High Velocity Workout           | ID = 1899
                                                 17 DAYS Power Up Workout               | ID = 5292
                                                 22 DAYS Anywhere Workout C             | ID = 5980
                                                 29 DAYS Crafty Controller Match-Prep   | ID = 5996
                                                 37 DAYS The Striker Match-Prep         | ID = 6002
                                                 47 DAYS The Wall Workout B             | ID = 5988
                                                 57 DAYS The Aerial Workout             | ID = 1906
                                                 65 DAYS The Space Maker Workout        | ID = 5952          
                                                 72 DAYS The Dominator Match-Prep       | ID = 5999
                                                 82 DAYS General Dribbling Workout A    | ID = 5945
                                                 92 DAYS Freestyle Footwork Workout     | ID = 5949
                                                 102 DAYS Forward Bomber Workout         | ID = 5993
                                                 112 DAYS 2-Player Tight Spaces Workout | ID = 5990
                                                 127 DAYS General Dribbling Workout B   | ID = 5977
                                                */

                                                 if (($datediffDays<7) and ($post->ID == 1902)){   $newLabel = true; }
                                                 if (($datediffDays<10) and ($post->ID == 5291)){   $newLabel = true; }
                                                 if (($datediffDays<12) and ($post->ID == 5984)){   $newLabel = true; }
                                                 if (($datediffDays<14) and ($post->ID == 1899)){   $newLabel = true; }
                                                 if (($datediffDays<17) and ($post->ID == 5292)){   $newLabel = true; }
                                                 if (($datediffDays<22) and ($post->ID == 5980)){   $newLabel = true; }
                                                 if (($datediffDays<29) and ($post->ID == 5996)){   $newLabel = true; }
                                                 if (($datediffDays<37) and ($post->ID == 6002)){   $newLabel = true; }
                                                 if (($datediffDays<47) and ($post->ID == 5988)){   $newLabel = true; }
                                                 if (($datediffDays<57) and ($post->ID == 1906)){   $newLabel = true; }
                                                 if (($datediffDays<65) and ($post->ID == 5952)){   $newLabel = true; }
                                                 if (($datediffDays<72) and ($post->ID == 5999)){   $newLabel = true; }
                                                 if (($datediffDays<82) and ($post->ID == 5945)){   $newLabel = true; }
                                                 if (($datediffDays<92) and ($post->ID == 5949)){   $newLabel = true; }
                                                 if (($datediffDays<102) and ($post->ID == 5993)){   $newLabel = true; }
                                                 if (($datediffDays<112) and ($post->ID == 5990)){   $newLabel = true; }
                                                 if (($datediffDays<127) and ($post->ID == 5977)){   $newLabel = true; }


                                                if($registrationDate <= $releaseDate) {
                                                    $newLabel = false;
                                                }

                                                //display new label for new workouts for all users 
                                                // 2-Player Tight Spaces Workout | ID = 5990
                                                if ($post->ID == 5990 ){  
                                                    $newLabel = true; 
                                                }
                                
                                                /*
                                                // 7 - 14 DAYS AFTER REGISTRATION 
                                                // Power up workout with new Label - ID: 5292  
                                                if (($datediffDays<14) and ($post->ID == 5292)){   $newLabel = true; }

                                                // 14 - 21 DAYS AFTER REGISTRATION 
                                                // Bodystrength Workout (B) with new Label - ID: 5291   
                                                if (($datediffDays<21) and ($post->ID == 5291)){   $newLabel = true; }

                                                // 21 - 28 DAYS AFTER REGISTRATION 
                                                // Aerial Workout - with new Label ID: 1906    
                                                if (($datediffDays<28) and ($post->ID == 1906)){   $newLabel = true; }

                                                // 28 - 35 DAYS AFTER REGISTRATION 
                                                /// High Velocity Workout hidden - ID: 1899     
                                                if (($datediffDays<35) and ($post->ID == 1899)){   $newLabel = true; }

                                                // 35 - 42 DAYS AFTER REGISTRATION 
                                                // The Anywhere Workout B hidden  - ID: 1902        
                                                if (($datediffDays<42) and ($post->ID == 1902)){   $newLabel = true; }
                                                */
                                        ?>
                                      

                                        <?php if ($newLabel){  ?>
                                            <div class="new-block-label">NEW!</div>
                                        <?php } ?>

                                        <?php if ($block_background_photo !== false) { echo '<div class="slider-item-overlay"></div>'; } ?>

                                        <div class="slider-item-inner">
                                            <div class="item-link">
                                                <?php if ($block_custom_icon === false) { ?>
                                                    <span style="color: <?php echo $block_text_color?>;"  ><?php the_title();?></span>
                                                <?php } else { ?>
                                                    <span class="block-with-icon" style=" <?php echo 'background-image:url('.$block_custom_icon['url'].');'; ?>;" ><?php the_title();?></span>

                                                <?php } ?>
                                            </div>
                                        </div>

                                        <a class="block-preview item-link thickbox" href="<?php the_permalink(); ?>?TB_iframe=true&width=full&height=full" data-workoutnum="1" data-name="<?php the_title();?>">
                                            <i class="fa fa-question-circle"></i>
                                        </a>

                                    </div>
                                <?php  } //end of if display ?>

                            <?php endwhile; else: endif;

                            // Reset Query
                            wp_reset_query();
                            ?>

                        <!-- LOADING OTHER BLOCKS OTHER THAN WORKOUTS AND PROGRAMS- POSTS THAT HAVE CATEGORY OTHER DETAILS -  -->
                                <?php $args = array(
                                    'cat'            => '73',
                                    'posts_per_page' => -1
                                ); ?>
                                <?php query_posts($args);?>

                                    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

                                        <?php // Tworzenie tablicy workoutow
                                            $workoutsNum[html_entity_decode(get_the_title())] = '1000';
                                            array_push($crossTrainingsArray,html_entity_decode(get_the_title()));
                                            //getting block colors
                                            $block_background_color = get_field( "block_background_color",$post);
                                            $block_text_color = get_field( "block_text_color",$post);
                                            $block_background_photo = get_field( "block_background_photo",$post );
                                            $block_custom_icon = get_field( "block_custom_icon",$post );
                                            $workoutsBackgrounds[html_entity_decode(get_the_title())] = $block_background_color;
                                            $workoutsColors[html_entity_decode(get_the_title())] = $block_text_color;
                                            $workoutsPhotos[html_entity_decode(get_the_title())] = $block_background_photo;
                                            $workoutsIcons[html_entity_decode(get_the_title())] = $block_custom_icon;
                                        ?>

                                    <div class="slider-item <?php if ($post->ID == 1188 OR $post->ID == 1170 OR $post->ID == 2021){ echo 'team-item';} else { echo 'cross-item';}?>"
                                        style=" <?php if ($block_background_photo === false) {
                                                    echo 'background:'.$block_background_color;
                                                } else {
                                                    echo 'background-image:url('.$block_background_photo['url'].'); background-size: contain;';
                                                }?>">

                                        <?php if ($block_background_photo !== false) { echo '<div class="slider-item-overlay"></div>'; } ?>

                                        <div class="slider-item-inner">
                                            <div class="item-link" >
                                                <?php if ($block_custom_icon === false) { ?>
                                                            <span style="color: <?php echo $block_text_color?>;"  ><?php the_title();?></span>
                                                <?php } else { ?>
                                                    <span class="block-with-icon" style=" <?php echo 'background-image:url('.$block_custom_icon['url'].');'; ?>;" ><?php the_title();?></span>
                                                <?php } ?>
                                            </div>
                                        </div>

                                        <a class="block-preview item-link thickbox" href="<?php the_permalink(); ?>?TB_iframe=true&width=full&height=full" data-workoutnum="1" data-name="<?php the_title();?>"><i class="fa fa-question-circle"></i></a>

                                    </div>

                                    <?php endwhile; else: endif;

                                    // Reset Query
                                    wp_reset_query();
                                    ?>

                                <!-- LOAD REST BLOCK -->
                                <div class="slider-item rest-item rest-block">
                                    <div class="slider-item-inner">
                                        <span>Rest</span>
                                    </div>
                                    <div class="block-preview" data-workoutnum="1" ><i class="fa fa-question-circle"></i></div>

                                </div>
                    </div> <!-- end of planner slider inner -->
                </div> <!-- end of planner slider outer -->

                <div class="slider-nav">
                    <div class="left"><div class="left-arrow"></div></div>
                    <div class="right"><div class="right-arrow"></div></div>
                </div>
            </div> <!-- end of #planner-slider -->
            <div class="clr"></div>

            <?php if ( !wp_is_mobile() ) { ?>
                <?php get_template_part('templates/schedule');?>
            <?php } ?>






        </div> <!-- end of schedule-col -->

        <!------------------------------ SIDEBAR ---------------------------------->
         <?php
            $qargs = array(
                'posts_per_page' => 1,
                'orderby' => 'rand',
                'post_type' => 'tips',
            );
            $tips_loop = new WP_Query($qargs); ?>

            <?php while ($tips_loop->have_posts()) : $tips_loop->the_post(); ?>

            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 schedule-sidebar">
                <div class="tips-container">
                    <h6>Tips</h6>
                    <p class="tip"><?php the_content(); ?> </p>
                </div>
            <?php endwhile; ?>

                <?php wp_reset_query(); ?>
                <?php $videoID = get_field('video_of_the_week_id');
                    $video = get_post($videoID); ?>
                <div class="tips-container videoOfTheWeek-container">
                    <h6>Video of the Week</h6>
                    <div class="thumbnail-img download-img ">
                        <?php if (get_post_thumbnail_id( $videoID)){
                                        $video_thumb = wp_get_attachment_image_src( get_post_thumbnail_id($videoID) , 'category');
                                        $video_thumbnail = $video_thumb[0];
                                    } else if( ( get_video_thumbnail( $videoID) ) != null ) {
                                        $video_thumbnail = get_video_thumbnail( $videoID);
                        } ?>
                        <a href="<?php echo get_permalink($videoID); ?>">
                            <img class="video-thumb" src="<?php echo $video_thumbnail; ?>" alt="img2">
                        </a>
                        <div class="media-title">
                            <a href="<?php echo get_permalink($videoID); ?>" class="video-title"><?php echo $video->post_title; ?></a>
                        </div>
                    </div>
                </div>

                <div class="tips-container weekPoints-container">
                    <h6>Points this Week</h6>
                    <p class="tip"><span class="weekExp">0</span> XP</p>
                </div>

        </div> <!-- end of schedule-sidebar -->

    </div> <!-- end of SCHEDULE -->



    <!-- MOBILE SCHEDULE -->
    <!-- THATS SEPARATE VERSION OF THE SCHEDULE DESIGNED FOR MOBILE AND SMALLER RESOLUTION SCREENS -->
    <div class="pad-bot mobile schedule mobile-schedule">
        <div class="mobile-schedule">
            <div class="mobile-month-row">
                <div class="mobile-month-left"><i class="fa fa-angle-left"></i></div>
                <span class="mobile_month">July</span>
                <div class="mobile-month-right"><i class="fa fa-angle-right"></i></div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 mobile-week-container">
                <div class="mobile-week-left"><i class="fa fa-angle-left"></i></div>
                <span class="mobile_week">Week1</span>
                <div class="mobile-week-right"><i class="fa fa-angle-right"></i></div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 mobile-day-container">
                <div class="mobile-day-left"><i class="fa fa-angle-left"></i></div>
                <span class="mobile_day">Monday</span>
                <div class="mobile-day-right"><i class="fa fa-angle-right"></i></div>
            </div>

            <?php $categories = get_categories($catargs); ?>
            <div class="clr"></div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 block1-container block-container inactive-block-container">
                <div class="category-list btn-list hidden">
                    <div class="row"><a class="btn list-btn program-cat-list-btn" href="#">Program</a></div>
                    <div class="row"><a class="btn list-btn workout-cat-list-btn" href="#">Workout</a></div>
                    <div class="row"><a class="btn list-btn cross-cat-list-btn" href="#">Cross Training</a></div>
                    <div class="row"><a class="btn list-btn team-cat-list-btn" href="#">Team Training</a></div>
                    <div class="row"><a class="btn list-btn match-cat-list-btn" href="#">Match</a></div>
                    <div class="row"><a class="btn list-btn rest-cat-list-btn" href="#">Rest Day</a></div>
                </div>

                <div class="program-list btn-list hidden">
                        <div class="row"><a class="btn list-btn program-cat-list-open" href="#">Program</a></div>
                            <?php foreach($categories as $category) {
                                if ($category->term_id != 29 and $category->name != 'Programs' and $category->name != 'Normal-Workouts' and $category->parent == 43) {

                                    // Tworzenie tablicy workoutow i dane o kolorze bloczku
                                    $category_name = $category->name ;
                                    $block_background_color = get_field( "block_background_color",$category );
                                    $block_text_color = get_field( "block_text_color",$category );
                                    ?>
                                <div class="row"><a class="btn list-btn sub-list-btn" href="<?php echo get_category_link( $category ); ?>?TB_iframe=true&width=full&height=full" style="color: <?php echo $block_text_color?>; background: <?php echo $block_background_color?>;"><?php echo $category_name;?></a></div>
                                <?php }
                            } ?>
                </div>


                <div class="workout-list btn-list hidden">
                    <div class="row"><a class="btn list-btn workout-cat-list-open" href="#">Workout</a></div>

                        <!-- LOADING WORKOUTS BLOCKS  -->
                        <?php $args = array(
                            'cat'            => '85',
                            'posts_per_page' => -1
                        ); ?>
                        <?php query_posts($args);?>

                            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

                                <?php // here determining the $display variable . Getting workout id and the days difference
                                    $display = true;                 

                                    /* HERE HIDE NEW BLOCKS FOR NEW USERS */

                                    /* workouts to delay
                                     1 DAY The Anywhere Workout B           | ID = 1902 
                                     3 DAYS Bodystrength Workout (B) -      | ID = 5291
                                     5 DAYS Wall Workout A                  | ID = 5984
                                     7 DAYS High Velocity Workout           | ID = 1899
                                     10 DAYS Power Up Workout               | ID = 5292
                                     15 DAYS Anywhere Workout C             | ID = 5980
                                     22 DAYS Crafty Controller Match-Prep   | ID = 5996
                                     30 DAYS The Striker Match-Prep         | ID = 6002
                                     40 DAYS The Wall Workout B             | ID = 5988
                                     50 DAYS The Aerial Workout             | ID = 1906
                                     58 DAYS The Space Maker Workout        | ID = 5952          
                                     65 DAYS The Dominator Match-Prep       | ID = 5999
                                     75 DAYS General Dribbling Workout A    | ID = 5945
                                     85 DAYS Freestyle Footwork Workout     | ID = 5949
                                     95 DAYS Forward Bomber Workout         | ID = 5993
                                     105 DAYS 2-Player Tight Spaces Workout | ID = 5990
                                     120 DAYS General Dribbling Workout B   | ID = 5977
                                    */
                                                 
                                    if ( ($datediffDays<1) and ($post->ID == 1902) ){ $display = false; } 
                                    if ( ($datediffDays<3) and ($post->ID == 5291) ){ $display = false; } 
                                    if ( ($datediffDays<5) and ($post->ID == 5984) ){ $display = false; } 
                                    if ( ($datediffDays<7) and ($post->ID == 1899) ){ $display = false; } 
                                    if ( ($datediffDays<10) and ($post->ID == 5292) ){ $display = false; } 
                                    if ( ($datediffDays<15) and ($post->ID == 5980) ){ $display = false; } 
                                    if ( ($datediffDays<22) and ($post->ID == 5996) ){ $display = false; } 
                                    if ( ($datediffDays<30) and ($post->ID == 6002) ){ $display = false; } 
                                    if ( ($datediffDays<40) and ($post->ID == 5988) ){ $display = false; } 
                                    if ( ($datediffDays<50) and ($post->ID == 1906) ){ $display = false; } 
                                    if ( ($datediffDays<58) and ($post->ID == 5952) ){ $display = false; } 
                                    if ( ($datediffDays<65) and ($post->ID == 5999) ){ $display = false; } 
                                    if ( ($datediffDays<75) and ($post->ID == 5945) ){ $display = false; } 
                                    if ( ($datediffDays<85) and ($post->ID == 5949) ){ $display = false; } 
                                    if ( ($datediffDays<95) and ($post->ID == 5993) ){ $display = false; } 
                                    if ( ($datediffDays<105) and ($post->ID == 5990) ){ $display = false; } 
                                    if ( ($datediffDays<120) and ($post->ID == 5977) ){ $display = false; } 

                                    //check if user registered before that amend was released , in that case don't hide the content
                                    //$registerdate = '2016-06-17';
                                    $registrationDate = new DateTime($registerdate);
                                    

                                    if($registrationDate <= $releaseDate) {
                                        $display = true;
                                    }
                                ?>

                                

                                <?php // Tworzenie tablicy workoutow
                                    //getting block colors
                                    $block_background_color = get_field( "block_background_color",$post);
                                    $block_text_color = get_field( "block_text_color",$post);
                                    $newLabel = false ;
                                
                                if ($display){ ?>

                                <?php
                                    /* HERE DETERMINE IF SHOW THE NEW LABEL OR NOT*/    

                                        /* workouts to delay
                                         7 DAY The Anywhere Workout B           | ID = 1902 
                                         10 DAYS Bodystrength Workout (B) -      | ID = 5291
                                         12 DAYS Wall Workout A                  | ID = 5984
                                         14 DAYS High Velocity Workout           | ID = 1899
                                         17 DAYS Power Up Workout               | ID = 5292
                                         22 DAYS Anywhere Workout C             | ID = 5980
                                         29 DAYS Crafty Controller Match-Prep   | ID = 5996
                                         37 DAYS The Striker Match-Prep         | ID = 6002
                                         47 DAYS The Wall Workout B             | ID = 5988
                                         57 DAYS The Aerial Workout             | ID = 1906
                                         65 DAYS The Space Maker Workout        | ID = 5952          
                                         72 DAYS The Dominator Match-Prep       | ID = 5999
                                         82 DAYS General Dribbling Workout A    | ID = 5945
                                         92 DAYS Freestyle Footwork Workout     | ID = 5949
                                         102 DAYS Forward Bomber Workout         | ID = 5993
                                         112 DAYS 2-Player Tight Spaces Workout | ID = 5990
                                         127 DAYS General Dribbling Workout B   | ID = 5977
                                        */

                                         if (($datediffDays<7) and ($post->ID == 1902)){   $newLabel = true; }
                                         if (($datediffDays<10) and ($post->ID == 5291)){   $newLabel = true; }
                                         if (($datediffDays<12) and ($post->ID == 5984)){   $newLabel = true; }
                                         if (($datediffDays<14) and ($post->ID == 1899)){   $newLabel = true; }
                                         if (($datediffDays<17) and ($post->ID == 5292)){   $newLabel = true; }
                                         if (($datediffDays<22) and ($post->ID == 5980)){   $newLabel = true; }
                                         if (($datediffDays<29) and ($post->ID == 5996)){   $newLabel = true; }
                                         if (($datediffDays<37) and ($post->ID == 6002)){   $newLabel = true; }
                                         if (($datediffDays<47) and ($post->ID == 5988)){   $newLabel = true; }
                                         if (($datediffDays<57) and ($post->ID == 1906)){   $newLabel = true; }
                                         if (($datediffDays<65) and ($post->ID == 5952)){   $newLabel = true; }
                                         if (($datediffDays<72) and ($post->ID == 5999)){   $newLabel = true; }
                                         if (($datediffDays<82) and ($post->ID == 5945)){   $newLabel = true; }
                                         if (($datediffDays<92) and ($post->ID == 5949)){   $newLabel = true; }
                                         if (($datediffDays<102) and ($post->ID == 5993)){   $newLabel = true; }
                                         if (($datediffDays<112) and ($post->ID == 5990)){   $newLabel = true; }
                                         if (($datediffDays<127) and ($post->ID == 5977)){   $newLabel = true; }



                                        //display new label for new workouts for all users 
                                        // 2-Player Tight Spaces Workout | ID = 5990
                                        if ($post->ID == 5990 ){  
                                            $newLabel = true; 
                                        }
                                
                        
                                        /*
                                        // 7 - 14 DAYS AFTER REGISTRATION 
                                        // Power up workout with new Label - ID: 5292  
                                        if (($datediffDays<14) and ($post->ID == 5292)){   $newLabel = true; }

                                        // 14 - 21 DAYS AFTER REGISTRATION 
                                        // Bodystrength Workout (B) with new Label - ID: 5291   
                                        if (($datediffDays<21) and ($post->ID == 5291)){   $newLabel = true; }

                                        // 21 - 28 DAYS AFTER REGISTRATION 
                                        // Aerial Workout - with new Label ID: 1906    
                                        if (($datediffDays<28) and ($post->ID == 1906)){   $newLabel = true; }

                                        // 28 - 35 DAYS AFTER REGISTRATION 
                                        /// High Velocity Workout hidden - ID: 1899     
                                        if (($datediffDays<35) and ($post->ID == 1899)){   $newLabel = true; }

                                        // 35 - 42 DAYS AFTER REGISTRATION 
                                        // The Anywhere Workout B hidden  - ID: 1902        
                                        if (($datediffDays<42) and ($post->ID == 1902)){   $newLabel = true; }
                                        */
                                ?>

                                <div class="row"><a class="btn list-btn sub-list-btn"  href="<?php the_permalink(); ?>?TB_iframe=true&width=full&height=full" style="color: <?php echo $block_text_color?>; background: <?php echo $block_background_color?>;"><?php the_title();?> </a><?php if ($newLabel){  ?><div class="new-block-label">NEW!</div><?php } ?></div>
                                <?php  } //end of if display  ?>


                            <?php endwhile; else: endif;
                            // Reset Query
                            wp_reset_query();
                            ?>
                </div>

                <div class="cross-list btn-list hidden">
                    <div class="row"><a class="btn list-btn cross-cat-list-open" href="#">Cross Training</a></div>

                        <?php $args = array(
                            'cat'            => '73',
                            'posts_per_page' => -1
                        ); ?>
                        <?php query_posts($args);?>

                            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

                                <?php
                                    //getting block colors
                                    $block_background_color = get_field( "block_background_color",$post);
                                    $block_text_color = get_field( "block_text_color",$post);


                                if ($post->ID != 1188 AND $post->ID != 1170){ ?>
                                    <div class="row"><a class="btn list-btn sub-list-btn" href="<?php the_permalink(); ?>?TB_iframe=true&width=full&height=full" style="color: <?php echo $block_text_color?>; background: <?php echo $block_background_color?>;"><?php the_title();?></a></div>

                            <?php   }

                             endwhile; else: endif;

                            // Reset Query
                            wp_reset_query();
                        ?>

                </div>

                <div class="mobile-block empty-mobile-block row">
                    <span class="mobile-block-title"></span>
                    <a class="mobile-notes-link hidden" href="#"><i class="fa fa-calendar-o"></i></a>
                    <a class="mobile-preview-link thickbox hidden" href="#"><div class="block-preview"><i class="fa fa-question-circle"></i></div> </a>
                    <a class="mobile-close-link hidden" href="#"><i class="fa fa-times"></i></a>
                </div>
            </div>
            <div class="clr"></div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 block2-container block-container inactive-block-container">
                <div class="category-list  btn-list hidden">
                        <div class="row"><a class="btn list-btn program-cat-list-btn" href="#">Program</a></div>
                        <div class="row"><a class="btn list-btn workout-cat-list-btn" href="#">Workout</a></div>
                        <div class="row"><a class="btn list-btn cross-cat-list-btn" href="#">Cross Training</a></div>
                        <div class="row"><a class="btn list-btn team-cat-list-btn" href="#">Team Training</a></div>
                        <div class="row"><a class="btn list-btn match-cat-list-btn" href="#">Match</a></div>
                        <div class="row"><a class="btn list-btn rest-cat-list-btn" href="#">Rest Day</a></div>
                </div>

                <div class="program-list btn-list hidden">
                        <div class="row"><a class="btn list-btn program-cat-list-open" href="#">Program</a></div>
                            <?php foreach($categories as $category) {
                                if ($category->term_id != 29 and $category->name != 'Programs' and $category->name != 'Normal-Workouts' and $category->parent == 43) {

                                    // Tworzenie tablicy workoutow i dane o kolorze bloczku
                                    $category_name = $category->name ;
                                    $block_background_color = get_field( "block_background_color",$category );
                                    $block_text_color = get_field( "block_text_color",$category );
                                    ?>
                                <div class="row"><a class="btn list-btn sub-list-btn" href="<?php echo get_category_link( $category ); ?>?TB_iframe=true&width=full&height=full" style="color: <?php echo $block_text_color?>; background: <?php echo $block_background_color?>;"><?php echo $category_name;?></a></div>
                                <?php }
                            } ?>
                </div>


                <div class="workout-list btn-list hidden">
                    <div class="row"><a class="btn list-btn workout-cat-list-open" href="#">Workout</a></div>

                        <!-- LOADING WORKOUTS BLOCKS  -->
                        <?php $args = array(
                            'cat'            => '85',
                            'posts_per_page' => -1
                        ); ?>
                        <?php query_posts($args);?>

                            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

                                <?php // here determining the $display variable . Getting workout id and the days difference
                                    $display = true;                 

                                    /* HERE HIDE NEW BLOCKS FOR NEW USERS */

                                    /* workouts to delay
                                     1 DAY The Anywhere Workout B           | ID = 1902 
                                     3 DAYS Bodystrength Workout (B) -      | ID = 5291
                                     5 DAYS Wall Workout A                  | ID = 5984
                                     7 DAYS High Velocity Workout           | ID = 1899
                                     10 DAYS Power Up Workout               | ID = 5292
                                     15 DAYS Anywhere Workout C             | ID = 5980
                                     22 DAYS Crafty Controller Match-Prep   | ID = 5996
                                     30 DAYS The Striker Match-Prep         | ID = 6002
                                     40 DAYS The Wall Workout B             | ID = 5988
                                     50 DAYS The Aerial Workout             | ID = 1906
                                     58 DAYS The Space Maker Workout        | ID = 5952          
                                     65 DAYS The Dominator Match-Prep       | ID = 5999
                                     75 DAYS General Dribbling Workout A    | ID = 5945
                                     85 DAYS Freestyle Footwork Workout     | ID = 5949
                                     95 DAYS Forward Bomber Workout         | ID = 5993
                                     105 DAYS 2-Player Tight Spaces Workout | ID = 5990
                                     120 DAYS General Dribbling Workout B   | ID = 5977
                                    */
                                                 
                                    if ( ($datediffDays<1) and ($post->ID == 1902) ){ $display = false; } 
                                    if ( ($datediffDays<3) and ($post->ID == 5291) ){ $display = false; } 
                                    if ( ($datediffDays<5) and ($post->ID == 5984) ){ $display = false; } 
                                    if ( ($datediffDays<7) and ($post->ID == 1899) ){ $display = false; } 
                                    if ( ($datediffDays<10) and ($post->ID == 5292) ){ $display = false; } 
                                    if ( ($datediffDays<15) and ($post->ID == 5980) ){ $display = false; } 
                                    if ( ($datediffDays<22) and ($post->ID == 5996) ){ $display = false; } 
                                    if ( ($datediffDays<30) and ($post->ID == 6002) ){ $display = false; } 
                                    if ( ($datediffDays<40) and ($post->ID == 5988) ){ $display = false; } 
                                    if ( ($datediffDays<50) and ($post->ID == 1906) ){ $display = false; } 
                                    if ( ($datediffDays<58) and ($post->ID == 5952) ){ $display = false; } 
                                    if ( ($datediffDays<65) and ($post->ID == 5999) ){ $display = false; } 
                                    if ( ($datediffDays<75) and ($post->ID == 5945) ){ $display = false; } 
                                    if ( ($datediffDays<85) and ($post->ID == 5949) ){ $display = false; } 
                                    if ( ($datediffDays<95) and ($post->ID == 5993) ){ $display = false; } 
                                    if ( ($datediffDays<105) and ($post->ID == 5990) ){ $display = false; } 
                                    if ( ($datediffDays<120) and ($post->ID == 5977) ){ $display = false; } 

                                    //check if user registered before that amend was released , in that case don't hide the content
                                    //$registerdate = '2016-06-17';
                                    $registrationDate = new DateTime($registerdate);
                                    

                                    if($registrationDate <= $releaseDate) {
                                        $display = true;
                                    }
                                ?>

                                

                                <?php // Tworzenie tablicy workoutow
                                    //getting block colors
                                    $block_background_color = get_field( "block_background_color",$post);
                                    $block_text_color = get_field( "block_text_color",$post);
                                    $newLabel = false ;
                                
                                if ($display){ ?>

                                <?php
                                    /* HERE DETERMINE IF SHOW THE NEW LABEL OR NOT*/    

                                        /* workouts to delay
                                         7 DAY The Anywhere Workout B           | ID = 1902 
                                         10 DAYS Bodystrength Workout (B) -      | ID = 5291
                                         12 DAYS Wall Workout A                  | ID = 5984
                                         14 DAYS High Velocity Workout           | ID = 1899
                                         17 DAYS Power Up Workout               | ID = 5292
                                         22 DAYS Anywhere Workout C             | ID = 5980
                                         29 DAYS Crafty Controller Match-Prep   | ID = 5996
                                         37 DAYS The Striker Match-Prep         | ID = 6002
                                         47 DAYS The Wall Workout B             | ID = 5988
                                         57 DAYS The Aerial Workout             | ID = 1906
                                         65 DAYS The Space Maker Workout        | ID = 5952          
                                         72 DAYS The Dominator Match-Prep       | ID = 5999
                                         82 DAYS General Dribbling Workout A    | ID = 5945
                                         92 DAYS Freestyle Footwork Workout     | ID = 5949
                                         102 DAYS Forward Bomber Workout         | ID = 5993
                                         112 DAYS 2-Player Tight Spaces Workout | ID = 5990
                                         127 DAYS General Dribbling Workout B   | ID = 5977
                                        */

                                         if (($datediffDays<7) and ($post->ID == 1902)){   $newLabel = true; }
                                         if (($datediffDays<10) and ($post->ID == 5291)){   $newLabel = true; }
                                         if (($datediffDays<12) and ($post->ID == 5984)){   $newLabel = true; }
                                         if (($datediffDays<14) and ($post->ID == 1899)){   $newLabel = true; }
                                         if (($datediffDays<17) and ($post->ID == 5292)){   $newLabel = true; }
                                         if (($datediffDays<22) and ($post->ID == 5980)){   $newLabel = true; }
                                         if (($datediffDays<29) and ($post->ID == 5996)){   $newLabel = true; }
                                         if (($datediffDays<37) and ($post->ID == 6002)){   $newLabel = true; }
                                         if (($datediffDays<47) and ($post->ID == 5988)){   $newLabel = true; }
                                         if (($datediffDays<57) and ($post->ID == 1906)){   $newLabel = true; }
                                         if (($datediffDays<65) and ($post->ID == 5952)){   $newLabel = true; }
                                         if (($datediffDays<72) and ($post->ID == 5999)){   $newLabel = true; }
                                         if (($datediffDays<82) and ($post->ID == 5945)){   $newLabel = true; }
                                         if (($datediffDays<92) and ($post->ID == 5949)){   $newLabel = true; }
                                         if (($datediffDays<102) and ($post->ID == 5993)){   $newLabel = true; }
                                         if (($datediffDays<112) and ($post->ID == 5990)){   $newLabel = true; }
                                         if (($datediffDays<127) and ($post->ID == 5977)){   $newLabel = true; }



                                        //display new label for new workouts for all users 
                                        // 5990 - Tight spaces
                                        if ($post->ID == 5990 ){  
                                            $newLabel = true; 
                                        }
                
                        
                                        /*
                                        // 7 - 14 DAYS AFTER REGISTRATION 
                                        // Power up workout with new Label - ID: 5292  
                                        if (($datediffDays<14) and ($post->ID == 5292)){   $newLabel = true; }

                                        // 14 - 21 DAYS AFTER REGISTRATION 
                                        // Bodystrength Workout (B) with new Label - ID: 5291   
                                        if (($datediffDays<21) and ($post->ID == 5291)){   $newLabel = true; }

                                        // 21 - 28 DAYS AFTER REGISTRATION 
                                        // Aerial Workout - with new Label ID: 1906    
                                        if (($datediffDays<28) and ($post->ID == 1906)){   $newLabel = true; }

                                        // 28 - 35 DAYS AFTER REGISTRATION 
                                        /// High Velocity Workout hidden - ID: 1899     
                                        if (($datediffDays<35) and ($post->ID == 1899)){   $newLabel = true; }

                                        // 35 - 42 DAYS AFTER REGISTRATION 
                                        // The Anywhere Workout B hidden  - ID: 1902        
                                        if (($datediffDays<42) and ($post->ID == 1902)){   $newLabel = true; }
                                        */
                                ?>

                                <div class="row"><a class="btn list-btn sub-list-btn"  href="<?php the_permalink(); ?>?TB_iframe=true&width=full&height=full" style="color: <?php echo $block_text_color?>; background: <?php echo $block_background_color?>;"><?php the_title();?> </a><?php if ($newLabel){  ?><div class="new-block-label">NEW!</div><?php } ?></div>
                                <?php  } //end of if display  ?>


                            <?php endwhile; else: endif;
                            // Reset Query
                            wp_reset_query();
                            ?>
                </div>

                <div class="cross-list  btn-list hidden">
                    <div class="row"><a class="btn list-btn cross-cat-list-open" href="#">Cross Training</a></div>

                        <?php $args = array(
                            'cat'            => '73',
                            'posts_per_page' => -1
                        ); ?>
                        <?php query_posts($args);?>

                            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

                                <?php
                                    //getting block colors
                                    $block_background_color = get_field( "block_background_color",$post);
                                    $block_text_color = get_field( "block_text_color",$post);


                                if ($post->ID != 1188 AND $post->ID != 1170){ ?>
                                    <div class="row"><a class="btn list-btn sub-list-btn" href="<?php the_permalink(); ?>?TB_iframe=true&width=full&height=full" style="color: <?php echo $block_text_color?>; background: <?php echo $block_background_color?>;"><?php the_title();?></a></div>

                            <?php   }

                             endwhile; else: endif;

                            // Reset Query
                            wp_reset_query();
                        ?>

                </div>
                <div class="mobile-block empty-mobile-block row">
                    <span class="mobile-block-title"></span>
                    <a class="mobile-notes-link hidden" href="#"><i class="fa fa-calendar-o"></i></a>
                    <a class="mobile-preview-link thickbox hidden" href="#"><div class="block-preview"><i class="fa fa-question-circle"></i></div> </a>
                    <a class="mobile-close-link hidden" href="#"><i class="fa fa-times"></i></a>
                </div>
            </div>

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 block3-container block-container inactive-block-container">
                <div class="category-list  btn-list hidden">
                        <div class="row"><a class="btn list-btn program-cat-list-btn" href="#">Program</a></div>
                        <div class="row"><a class="btn list-btn workout-cat-list-btn" href="#">Workout</a></div>
                        <div class="row"><a class="btn list-btn cross-cat-list-btn" href="#">Cross Training</a></div>
                        <div class="row"><a class="btn list-btn team-cat-list-btn" href="#">Team Training</a></div>
                        <div class="row"><a class="btn list-btn match-cat-list-btn" href="#">Match</a></div>
                        <div class="row"><a class="btn list-btn rest-cat-list-btn" href="#">Rest Day</a></div>
                </div>

                <div class="program-list btn-list hidden">
                        <div class="row"><a class="btn list-btn program-cat-list-open" href="#">Program</a></div>
                            <?php foreach($categories as $category) {
                                if ($category->term_id != 29 and $category->name != 'Programs' and $category->name != 'Normal-Workouts' and $category->parent == 43) {

                                    // Tworzenie tablicy workoutow i dane o kolorze bloczku
                                    $category_name = $category->name ;
                                    $block_background_color = get_field( "block_background_color",$category );
                                    $block_text_color = get_field( "block_text_color",$category );
                                    ?>
                                <div class="row"><a class="btn list-btn sub-list-btn" href="<?php echo get_category_link( $category ); ?>?TB_iframe=true&width=full&height=full" style="color: <?php echo $block_text_color?>; background: <?php echo $block_background_color?>;"><?php echo $category_name;?></a></div>
                                <?php }
                            } ?>
                </div>


                <div class="workout-list btn-list hidden">
                    <div class="row"><a class="btn list-btn workout-cat-list-open" href="#">Workout</a></div>

                        <!-- LOADING WORKOUTS BLOCKS  -->
                        <?php $args = array(
                            'cat'            => '85',
                            'posts_per_page' => -1
                        ); ?>
                        <?php query_posts($args);?>

                            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

                                <?php // here determining the $display variable . Getting workout id and the days difference
                                    $display = true;                 

                                    /* HERE HIDE NEW BLOCKS FOR NEW USERS */

                                    /* workouts to delay
                                     1 DAY The Anywhere Workout B           | ID = 1902 
                                     3 DAYS Bodystrength Workout (B) -      | ID = 5291
                                     5 DAYS Wall Workout A                  | ID = 5984
                                     7 DAYS High Velocity Workout           | ID = 1899
                                     10 DAYS Power Up Workout               | ID = 5292
                                     15 DAYS Anywhere Workout C             | ID = 5980
                                     22 DAYS Crafty Controller Match-Prep   | ID = 5996
                                     30 DAYS The Striker Match-Prep         | ID = 6002
                                     40 DAYS The Wall Workout B             | ID = 5988
                                     50 DAYS The Aerial Workout             | ID = 1906
                                     58 DAYS The Space Maker Workout        | ID = 5952          
                                     65 DAYS The Dominator Match-Prep       | ID = 5999
                                     75 DAYS General Dribbling Workout A    | ID = 5945
                                     85 DAYS Freestyle Footwork Workout     | ID = 5949
                                     95 DAYS Forward Bomber Workout         | ID = 5993
                                     105 DAYS 2-Player Tight Spaces Workout | ID = 5990
                                     120 DAYS General Dribbling Workout B   | ID = 5977
                                    */
                                                 
                                    if ( ($datediffDays<1) and ($post->ID == 1902) ){ $display = false; } 
                                    if ( ($datediffDays<3) and ($post->ID == 5291) ){ $display = false; } 
                                    if ( ($datediffDays<5) and ($post->ID == 5984) ){ $display = false; } 
                                    if ( ($datediffDays<7) and ($post->ID == 1899) ){ $display = false; } 
                                    if ( ($datediffDays<10) and ($post->ID == 5292) ){ $display = false; } 
                                    if ( ($datediffDays<15) and ($post->ID == 5980) ){ $display = false; } 
                                    if ( ($datediffDays<22) and ($post->ID == 5996) ){ $display = false; } 
                                    if ( ($datediffDays<30) and ($post->ID == 6002) ){ $display = false; } 
                                    if ( ($datediffDays<40) and ($post->ID == 5988) ){ $display = false; } 
                                    if ( ($datediffDays<50) and ($post->ID == 1906) ){ $display = false; } 
                                    if ( ($datediffDays<58) and ($post->ID == 5952) ){ $display = false; } 
                                    if ( ($datediffDays<65) and ($post->ID == 5999) ){ $display = false; } 
                                    if ( ($datediffDays<75) and ($post->ID == 5945) ){ $display = false; } 
                                    if ( ($datediffDays<85) and ($post->ID == 5949) ){ $display = false; } 
                                    if ( ($datediffDays<95) and ($post->ID == 5993) ){ $display = false; } 
                                    if ( ($datediffDays<105) and ($post->ID == 5990) ){ $display = false; } 
                                    if ( ($datediffDays<120) and ($post->ID == 5977) ){ $display = false; } 

                                    //check if user registered before that amend was released , in that case don't hide the content
                                    //$registerdate = '2016-06-17';
                                    $registrationDate = new DateTime($registerdate);
                                    

                                    if($registrationDate <= $releaseDate) {
                                        $display = true;
                                    }
                                ?>

                                

                                <?php // Tworzenie tablicy workoutow
                                    //getting block colors
                                    $block_background_color = get_field( "block_background_color",$post);
                                    $block_text_color = get_field( "block_text_color",$post);
                                    $newLabel = false ;
                                
                                if ($display){ ?>

                                <?php
                                    /* HERE DETERMINE IF SHOW THE NEW LABEL OR NOT*/    

                                        /* workouts to delay
                                         7 DAY The Anywhere Workout B           | ID = 1902 
                                         10 DAYS Bodystrength Workout (B) -      | ID = 5291
                                         12 DAYS Wall Workout A                  | ID = 5984
                                         14 DAYS High Velocity Workout           | ID = 1899
                                         17 DAYS Power Up Workout               | ID = 5292
                                         22 DAYS Anywhere Workout C             | ID = 5980
                                         29 DAYS Crafty Controller Match-Prep   | ID = 5996
                                         37 DAYS The Striker Match-Prep         | ID = 6002
                                         47 DAYS The Wall Workout B             | ID = 5988
                                         57 DAYS The Aerial Workout             | ID = 1906
                                         65 DAYS The Space Maker Workout        | ID = 5952          
                                         72 DAYS The Dominator Match-Prep       | ID = 5999
                                         82 DAYS General Dribbling Workout A    | ID = 5945
                                         92 DAYS Freestyle Footwork Workout     | ID = 5949
                                         102 DAYS Forward Bomber Workout         | ID = 5993
                                         112 DAYS 2-Player Tight Spaces Workout | ID = 5990
                                         127 DAYS General Dribbling Workout B   | ID = 5977
                                        */

                                         if (($datediffDays<7) and ($post->ID == 1902)){   $newLabel = true; }
                                         if (($datediffDays<10) and ($post->ID == 5291)){   $newLabel = true; }
                                         if (($datediffDays<12) and ($post->ID == 5984)){   $newLabel = true; }
                                         if (($datediffDays<14) and ($post->ID == 1899)){   $newLabel = true; }
                                         if (($datediffDays<17) and ($post->ID == 5292)){   $newLabel = true; }
                                         if (($datediffDays<22) and ($post->ID == 5980)){   $newLabel = true; }
                                         if (($datediffDays<29) and ($post->ID == 5996)){   $newLabel = true; }
                                         if (($datediffDays<37) and ($post->ID == 6002)){   $newLabel = true; }
                                         if (($datediffDays<47) and ($post->ID == 5988)){   $newLabel = true; }
                                         if (($datediffDays<57) and ($post->ID == 1906)){   $newLabel = true; }
                                         if (($datediffDays<65) and ($post->ID == 5952)){   $newLabel = true; }
                                         if (($datediffDays<72) and ($post->ID == 5999)){   $newLabel = true; }
                                         if (($datediffDays<82) and ($post->ID == 5945)){   $newLabel = true; }
                                         if (($datediffDays<92) and ($post->ID == 5949)){   $newLabel = true; }
                                         if (($datediffDays<102) and ($post->ID == 5993)){   $newLabel = true; }
                                         if (($datediffDays<112) and ($post->ID == 5990)){   $newLabel = true; }
                                         if (($datediffDays<127) and ($post->ID == 5977)){   $newLabel = true; }



                                        //display new label for new workouts for all users 
                                        // 5990 - Tight spaces
                                        if ($post->ID == 5990 ){  
                                            $newLabel = true; 
                                        }
                                
                        
                                        /*
                                        // 7 - 14 DAYS AFTER REGISTRATION 
                                        // Power up workout with new Label - ID: 5292  
                                        if (($datediffDays<14) and ($post->ID == 5292)){   $newLabel = true; }

                                        // 14 - 21 DAYS AFTER REGISTRATION 
                                        // Bodystrength Workout (B) with new Label - ID: 5291   
                                        if (($datediffDays<21) and ($post->ID == 5291)){   $newLabel = true; }

                                        // 21 - 28 DAYS AFTER REGISTRATION 
                                        // Aerial Workout - with new Label ID: 1906    
                                        if (($datediffDays<28) and ($post->ID == 1906)){   $newLabel = true; }

                                        // 28 - 35 DAYS AFTER REGISTRATION 
                                        /// High Velocity Workout hidden - ID: 1899     
                                        if (($datediffDays<35) and ($post->ID == 1899)){   $newLabel = true; }

                                        // 35 - 42 DAYS AFTER REGISTRATION 
                                        // The Anywhere Workout B hidden  - ID: 1902        
                                        if (($datediffDays<42) and ($post->ID == 1902)){   $newLabel = true; }
                                        */
                                ?>

                                <div class="row"><a class="btn list-btn sub-list-btn"  href="<?php the_permalink(); ?>?TB_iframe=true&width=full&height=full" style="color: <?php echo $block_text_color?>; background: <?php echo $block_background_color?>;"><?php the_title();?> </a><?php if ($newLabel){  ?><div class="new-block-label">NEW!</div><?php } ?></div>
                                <?php  } //end of if display  ?>


                            <?php endwhile; else: endif;
                            // Reset Query
                            wp_reset_query();
                            ?>
                </div>

                <div class="cross-list  btn-list hidden">
                    <div class="row"><a class="btn list-btn cross-cat-list-open" href="#">Cross Training</a></div>

                        <?php $args = array(
                            'cat'            => '73',
                            'posts_per_page' => -1
                        ); ?>
                        <?php query_posts($args);?>

                            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

                                <?php
                                    //getting block colors
                                    $block_background_color = get_field( "block_background_color",$post);
                                    $block_text_color = get_field( "block_text_color",$post);


                                if ($post->ID != 1188 AND $post->ID != 1170){ ?>
                                    <div class="row"><a class="btn list-btn sub-list-btn" href="<?php the_permalink(); ?>?TB_iframe=true&width=full&height=full" style="color: <?php echo $block_text_color?>; background: <?php echo $block_background_color?>;"><?php the_title();?></a></div>

                            <?php   }

                             endwhile; else: endif;

                            // Reset Query
                            wp_reset_query();
                        ?>

                </div>
                <div class="mobile-block empty-mobile-block row">
                    <span class="mobile-block-title"></span>
                    <a class="mobile-notes-link hidden" href="#"><i class="fa fa-calendar-o"></i></a>
                    <a class="mobile-preview-link thickbox hidden" href="#"><div class="block-preview"><i class="fa fa-question-circle"></i></div> </a>
                    <a class="mobile-close-link hidden" href="#"><i class="fa fa-times"></i></a>
                </div>
            </div>


        </div>

    </div> <!-- end of MOBILE SCHEDULE -->
    <div class="row">
        <div class="content-profile-dashboard">
        <div class="profile-dashboard row margin-bot-small">
            <div class="profile-info-container profile-dropdown-dashboard profile-dropdown-active">
                <div class="profile-dropdown-col">
                    <div class="row score-row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 score-col">
                            <p>Score: <b><?php echo $exp; ?></b></p>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 score-col">
                            <a href="#" class="tooltip fade bottom in" data-toggle="tooltip" data-placement="bottom" title="Number of active days in a row"><span class="icon icon-streak-small "></span></a>
                            <span class="streak-counter"><?php echo $streak; ?></span>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 score-col">
                            <p style="text-align:right">Lvl. <b class="lvl_value"><?php echo $level; ?></b></p>
                        </div>
                    </div>

                    <div class="row profile-avatar-row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 profile-avatar">
                            <a href="#">
                                <div class="avatar-img avatar-change">
                                    <?php  $current_user = wp_get_current_user();
                                    if ( ($current_user instanceof WP_User) ) {
                                        echo get_avatar( $current_user->user_email, 99 );
                                    }
                                    ?>
                                    <div class="level-bubble"><span class="lvl_value"><?php echo $level; ?></span></div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8 exp-info">
                            <span class="user-name"><?php echo $current_user->user_firstname ; ?> </span>
                            <div class="level-counter">
                                <div id="progress-container">
                                    <div id="progress-bar" style="width:<?php echo $progress_bar; ?>%;"></div>
                                </div>
                                <div class="level-info">
                                    <span class="current-points"><?php echo ($exp-$levelStarts); ?> /</span>
                                    <span class="next-level"><?php echo ($levelLimit-$levelStarts); ?></span>
                                </div>
                            </div>

                        </div>
                        <a href="#" class="tooltip fade bottom in" data-toggle="tooltip" data-placement="bottom" title="Number of active days in a row">
                        <a href="<?php echo get_site_url(); ?>/account/" class="profile-settings-link tooltip fade bottom in" data-toggle="tooltip" data-placement="bottom" title="Profile settings"><i class="fa fa-cog"></i></a>
                    </div>


                                <div class="finish-up-stats-row row">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 activities-col">
                                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                            <img src="../wp-content/themes/Effective/img/activities.png">
                                        </div>
                                        <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                                            <h6 class="activities_counter"><?php echo $activities;?></h6>
                                            <span>Activities</span>
                                        </div>
                                    </div>


                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 badges-col">
                                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                            <img src="../wp-content/themes/Effective/img/badges.png">
                                        </div>
                                        <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                                            <h6 class="badges_counter"><?php echo $badgesCounter;?></h6>
                                            <span>Badges</span>
                                        </div>
                                    </div>



                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 days-col">
                                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                            <img src="../wp-content/themes/Effective/img/days.png">
                                        </div>
                                        <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                                            <h6 class="days_counter"><?php echo $datediffDays;?></h6>
                                            <span>Days</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

        </div></div>

        <?php

        //GETTING GAMEBRAIN POSTS TO REPLACE WITH SLIDER
        $gamebrain1 = get_field('video_of_the_week_1');

        if( $gamebrain1 ): 
            // override $post
            $post = $gamebrain1;
            setup_postdata( $post ); 
            $gamebrain1_title = get_the_title(); 
            $gamebrain1_thumbnail = get_the_post_thumbnail( $post->ID, 'full'  );
            $gamebrain1_vimeoID =   get_field( "gamebrain_video", $post->ID ); 

            wp_reset_postdata(); // IMPORTANT - reset the $post object so the rest of the page works correctly 
        endif; 

        $gamebrain2 = get_field('video_of_the_week_2');

        if( $gamebrain2 ): 
            // override $post
            $post = $gamebrain2;
            setup_postdata( $post ); 
            $gamebrain2_title = get_the_title(); 
            $gamebrain2_thumbnail = get_the_post_thumbnail( $post->ID, 'full'  );
            $gamebrain2_vimeoID =   get_field( "gamebrain_video", $post->ID ); 

            wp_reset_postdata(); // IMPORTANT - reset the $post object so the rest of the page works correctly 
        endif; 

        $gamebrain3 = get_field('video_of_the_week_3');

        if( $gamebrain3 ): 
            // override $post
            $post = $gamebrain3;
            setup_postdata( $post ); 
            $gamebrain3_title = get_the_title(); 
            $gamebrain3_thumbnail = get_the_post_thumbnail( $post->ID, 'full'   );
            $gamebrain3_vimeoID =   get_field( "gamebrain_video", $post->ID ); 

            wp_reset_postdata(); // IMPORTANT - reset the $post object so the rest of the page works correctly 
        endif; 



        ?>


        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 gb-class-vids margin-bot-small remove-padding ">
            <h4 class="latest-vids-h4">Game Brain & Classroom</h4>
            <div class="dashboard-gray-bg gb_classroom_vid">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 top-video-dashboard">
                    <button type="button" class="btn" data-toggle="modal" data-target="#myModal1">
                        <?php echo $gamebrain1_thumbnail; ?>
                        <div class="img-wrap">
                            <img src="<?php echo get_site_url(); ?>/wp-content/themes/Effective/images/play.png"/>
                            <div class="img-wrap-inner">
                                <p><p>
                                <p class="wrap-title"><?php echo $gamebrain1_title; ?></p>
                            </div>
                        </div>
                    </button>
                    <div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <h4 class="modal-title" id="myModalLabel"><?php echo $gamebrain1_title; ?></h4>
                                </div>
                                <div class="modal-body">
                                    <div class="videoWrapper">
                                        <iframe src="http://player.vimeo.com/video/<?php echo $gamebrain1_vimeoID; ?>?title=0&amp;byline=0&amp;portrait=0&amp;color=ffffff"></iframe>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 left-video-dashboard">
                    <button type="button" class="btn" data-toggle="modal" data-target="#myModal2">
                        <?php echo $gamebrain2_thumbnail; ?>
                        <div class="img-wrap">
                            <img src="<?php echo get_site_url(); ?>/wp-content/themes/Effective/images/play.png"/>
                            <div class="img-wrap-inner">
                                <p><p>
                                <p class="wrap-title"><?php echo $gamebrain2_title; ?></p>
                            </div>
                        </div>
                    </button>
                    <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <h4 class="modal-title" id="myModalLabel"><?php echo $gamebrain2_title; ?></h4>
                                </div>
                                <div class="modal-body">
                                    <div class="videoWrapper">
                                        <iframe src="http://player.vimeo.com/video/<?php echo $gamebrain2_vimeoID; ?>?title=0&amp;byline=0&amp;portrait=0&amp;color=ffffff"></iframe>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 right-video-dashboard">
                    <button type="button" class="btn" data-toggle="modal" data-target="#myModal3">
                        <?php echo $gamebrain3_thumbnail; ?>
                        <div class="img-wrap">
                            <img src="<?php echo get_site_url(); ?>/wp-content/themes/Effective/images/play.png"/>
                            <div class="img-wrap-inner">
                                <p><p>
                                <p class="wrap-title"><?php echo $gamebrain3_title; ?></p>
                            </div>
                        </div>
                    </button>
                    <div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <h4 class="modal-title" id="myModalLabel"><?php echo $gamebrain3_title; ?></h4>
                                </div>
                                <div class="modal-body">
                                    <div class="videoWrapper">
                                        <iframe src="http://player.vimeo.com/video/<?php echo $gamebrain3_vimeoID; ?>?title=0&amp;byline=0&amp;portrait=0&amp;color=ffffff"></iframe>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <h4>Latest Exercises</h4>
            <div class="dashboard-gray-bg gb_classroom_vid latest_blog_container">
                <!-- // LATEST POSTS QUERY -->
                <div class="latest-blog-dashboard floatfix">
                <?php

                $qargs = array(
                            'posts_per_page' => 40,
                            'post_type' => 'post',
                            'post_status' => 'publish',
                            'tag' => 'new-exercise'
                        );

                $blog_query = new WP_Query($qargs);
                $i = 0;

                // Start our WP Query
                 while ($blog_query -> have_posts()) : $blog_query -> the_post(); 
                    if( ( get_video_thumbnail() ) != null ) { 
                        $video_thumbnail = get_video_thumbnail();
                    } elseif (get_post_thumbnail_id()){
                        $video_thumb = wp_get_attachment_image_src( get_post_thumbnail_id($myid) , 'category');
                        $video_thumbnail = $video_thumb[0];
                   }?>

                <div class='item'>
                    <a href="<?php the_permalink() ?>"><div class="popular_thumb left-thumb"><img src="<?php echo $video_thumbnail; ?>" alt="img"/></div></a>
                </div>

                <?$i++;?>

                <?php
                endwhile;
                wp_reset_postdata();
                ?>
                </div>
                <div class='floatfix slider-nav'>
                <div class='prev-button'><div class='left-arrow'></div></div>
                <div class='next-button'><div class='right-arrow'></div></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 margin-bot-small remove-padding ">
            <h4>Recent Notes</h4>

            <div class="recent-notes-dashboard dashboard-gray-bg" id='recent-notes-container'>
                <div class="">
                    <?php

                        $qargs = array(
                            'posts_per_page' => 10,
                            'post_type' => 'notes',
                            'post_status' => 'publish'

                        );
                        $notes_loop = new WP_Query($qargs); ?>

                        <?php while ($notes_loop->have_posts()) : $notes_loop->the_post(); ?>
                            <?php
                                //getting note variables
                                $date =  get_the_date('F d, Y', FALSE);
                                $title = get_the_title();
                                $note = get_the_content();
                                $note_id = get_the_ID();
                                $ID = (int)$note_id;
                               // $trainedFor = get_field('how_long', $ID );
                                $trainedFor = get_post_meta( $note_id, 'how_long');
                                //$rating = get_field('rating', $ID );
                                $rating = get_post_meta( $note_id, 'rating');
                               // $current = get_field('which_workout_in_program',  $ID );
                                $current = get_post_meta( $note_id, 'which_workout_in_program');
                                //$series = get_field('number_of_workouts_in_program',  $ID );
                                $series = get_post_meta( $note_id, 'number_of_workouts_in_program');
                                $feeling = get_post_meta( $note_id, 'feeling');
                                $authorID = get_the_author_id();
                                $terms = get_the_terms( $ID, 'note' );
                                $notePhoto = $workoutsPhotos[$title];
                                $blockColor = $workoutsColors[$title];


                                ?>
                                <div class="single-comm">
                                    <?php
                                        $authorLevel = get_user_meta( $authorID, 'level', true );
                                        $author_profile_url = get_author_posts_url($authorID);
                                    ?>
                                    <a href="<?php echo $author_profile_url; ?>" >
                                        <div class="notes-author_avatar"><?php echo get_avatar( $authorID, 46 ); ?>
                                            <div class="level-bubble"><span class="lvl_value"><?php echo $authorLevel; ?></span></div>

                                        </div>
                                    </a>
                                    <div class="notes-con">
                                        <div class="note-header">
                                            <div class="comm-author"><?php the_author();?></div>
                                            <div class="notes-date"><?php echo $date ?></div>
                                            <?php if ($rating[0] != -1) { ?>
                                            <span class="notes-stars stars-<?php echo $rating[0] ?> "><i class="fa fa-star star1"></i> <i class="fa fa-star star2"></i> <i class="fa fa-star star3"></i> <i class="fa fa-star star4"></i> <i class="fa fa-star star5"></i></span>
                                            <?php } ?>
                                            <div class="notes-place"><?php echo $title; ?></div>
                                            <?php if ($trainedFor[0] !=0) { ?>
                                            <div class="notes-time"><?php echo $trainedFor[0] ?> minutes</div>
                                            <?php 
                                                } if ($feeling) { 
                                                    switch($feeling[0]) {
                                                        case 'positive':
                                                            $feeling[0] = '<i class="fa fa-smile-o" aria-hidden="true"></i>';
                                                            break;
                                                        case 'sad':
                                                            $feeling[0] = '<i class="fa fa-frown-o" aria-hidden="true"></i>';
                                                            break;
                                                        case 'neutral':
                                                            $feeling[0] = '<i class="fa fa-meh-o" aria-hidden="true"></i>';
                                                            break;
                                                    } ?>
                                            <div class='notes-time'><?php echo $feeling[0]; ?> </div> 
                                        <? } ?>
                                            <div class="clr"></div>
                                        </div>

                                        <div class="notes-text">
                                            <?php if ( $notePhoto ) { ?>
                                                <div class="table-block table-block-overlay note-block" style="background-image:url(' <?php echo $notePhoto['url'] ?> '); background-size:contain; position:relative"><div class="table-block-inner"><span style="color:'<?php echo  $blockColor?> ';"><?php echo $title ?></span></div></div>
                                            <?php } ?>

                                            <?php echo $note ?>

                                        </div>

                                        <div class="clr"></div>

                                        <?php  $comments_number = get_comments_number( $note_id ); ?>
                                        <a href="#"><div class="notes-reply">Reply (<?php echo $comments_number;?>)</div></a>

                                        <div class="reply-notes-form ">
                                          <?php  $args = array(
                                              'class_form'      => 'note-input',
                                              'class_submit'      => 'btn btn-primary add-note-reply',
                                              'label_submit'      => __( 'Reply' ),
                                              'format'            => 'xhtml',

                                              'comment_field' =>  '<p class="comment-form-comment"><label for="comment">' . _x( 'Comment', 'noun' ) .
                                                '</label><textarea class="notes-textarea" id="comment" name="comment" rows="4" aria-required="true"  placeholder="Type reply here...">' .
                                                '</textarea></p>',
                                              'logged_in_as' => '',
                                              'comment_notes_before' => '',
                                              'comment_notes_after' => '',
                                            );

                                            comment_form(  $args, $post_id ); ?>


                                        </div>

                                   </div>
                                </div>


                                <?php
                                //comments

                                    //Gather comments for a specific page/post
                                      $Note_comments = get_comments(array(

                                         'status' => 'approve',
                                         'number' => 3,
                                         'order' => 'ASC',
                                         'post_id' => get_the_ID() ,
                                      ));
                                     foreach($Note_comments as $note_comm) :?>
                                        <div class="single-comm comment-reply">
                                            <?php $authorLevel = get_user_meta( $note_comm->user_id, 'level', true );
                                                 $author_profile_url = get_author_posts_url($note_comm->user_id);

                                            ?>
                                            <a href="<?php echo $author_profile_url; ?>" >
                                                <div class="comm-author_avatar"><?php echo get_avatar( $note_comm->comment_author_email );?>
                                                     <div class="level-bubble"><span class="lvl_value"><?php echo $authorLevel; ?></span></div>

                                                </div>
                                            </a>
                                            <div class="comm-con">
                                                <div class="comm-author"><?php echo($note_comm->comment_author);?></div>
                                                <div class="comm-date" style="color:#b3b3b3;"><?php echo get_comment_date( 'F n, Y', $note_comm->comment_ID ); ?></div>
                                                <div class="comm-content"><?php echo($note_comm->comment_content);?></div>



                                            </div>
                                        </div>
                                    <?php endforeach;?>



                        <?php endwhile; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 sidebar-profile-dashboard">
    <div class="profile-dashboard row margin-bot-small sidebar-profile">
        <div class="profile-info-container profile-dropdown-dashboard profile-dropdown-active">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 profile-dropdown-col">
                <div class="row score-row">
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 score-col">
                        <p>Score: <b><?php echo $exp; ?></b></p>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 score-col">
                        <a href="#" class="tooltip fade bottom in" data-toggle="tooltip" data-placement="bottom" title="Number of active days in a row"><span class="icon icon-streak-small "></span></a>
                        <span class="streak-counter"><?php echo $streak; ?></span>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 score-col">
                        <p style="text-align:right">Lvl. <b class="lvl_value"><?php echo $level; ?></b></p>
                    </div>
                </div>

                <div class="row profile-avatar-row">
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 profile-avatar">
                        <a href="#">
                            <div class="avatar-img avatar-change">
                                <?php  $current_user = wp_get_current_user();
                                if ( ($current_user instanceof WP_User) ) {
                                    echo get_avatar( $current_user->user_email, 99 );
                                }
                                ?>
                                <div class="level-bubble"><span class="lvl_value"><?php echo $level; ?></span></div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8 exp-info">
                        <span class="user-name"><?php echo $current_user->user_firstname ; ?> </span>
                        <div class="level-counter">
                            <div id="progress-container">
                                <div id="progress-bar" style="width:<?php echo $progress_bar; ?>%;"></div>
                            </div>
                            <div class="level-info">
                                <span class="current-points"><?php echo ($exp-$levelStarts); ?> /</span>
                                <span class="next-level"><?php echo ($levelLimit-$levelStarts); ?></span>
                            </div>
                        </div>

                    </div>
                    <a href="#" class="tooltip fade bottom in" data-toggle="tooltip" data-placement="bottom" title="Number of active days in a row">
                    <a href="<?php echo get_site_url(); ?>/account/" class="profile-settings-link tooltip fade bottom in" data-toggle="tooltip" data-placement="bottom" title="Profile settings"><i class="fa fa-cog"></i></a>
                </div>


                            <div class="finish-up-stats-row row">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 activities-col">
                                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                        <img src="../wp-content/themes/Effective/img/activities.png">
                                    </div>
                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                                        <h6 class="activities_counter"><?php echo $activities;?></h6>
                                        <span>Activities</span>
                                    </div>
                                </div>


                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 badges-col">
                                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                        <img src="../wp-content/themes/Effective/img/badges.png">
                                    </div>
                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                                        <h6 class="badges_counter"><?php echo $badgesCounter;?></h6>
                                        <span>Badges</span>
                                    </div>
                                </div>



                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 days-col">
                                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                        <img src="../wp-content/themes/Effective/img/days.png">
                                    </div>
                                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                                        <h6 class="days_counter"><?php echo $datediffDays;?></h6>
                                        <span>Days</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

    </div>
    <div class="tips-dashboard row margin-bot-small">
        <h5>Effective 3.0 is coming!</h5>
        <img src="<?php echo get_site_url(); ?>/wp-content/themes/Effective/images/tips.png"/>
        What's the #1 thing you'd like to see more of in Effective?
        <a class="btn btn-primary" href="http://www.traineffective.com/features/yourvoice/" style="margin-top: 10px;">Click Here to Submit Your Idea</a>
    </div>

    <div class="points-dashboard row margin-bot-small">
        <h5>Points last 30 days</h5>
        <div id="month-chart"></div>
    </div>
    <div class="tips-dashboard row">
        <h5>Tips</h5>
        <img src="<?php echo get_site_url(); ?>/wp-content/themes/Effective/images/tips.png"/>

        <?php $posts = get_posts( array( 'orderby' => 'rand', 'numberposts' => 1, 'post_type' => 'tips' ) );
        foreach($posts as $post) : ?>
        <div>
        <? echo $post->post_content; ?>
            <div>
            <?php endforeach; ?>

            </div>
        </div>
    </div>
    <div class="latestcomments-dashboard margin-bot-small content">
        <h4>Latest Comments</h4>
        <div class="latest-comments-dashboard dashboard-gray-bg">
            <div class="">
            <?php
            $comments = get_comments(array(
               'status' => 'approve',
               'number' => 5,
               'post_type' => array( 'gamebrains' , 'classrooms','blogposts')
            ));
            foreach($comments as $comm) :?>
                <div class="single-comm">
                    <?php
                        $authorLevel = get_user_meta( $comm->user_id, 'level', true );
                        $author_profile_url = get_author_posts_url($comm->user_id);
                        $comment_post_ID = $comm->comment_post_ID;
                    ?>
                    <a href="<?php echo $author_profile_url; ?>" >
                        <div class="comm-author_avatar"><?php echo get_avatar( $comm->comment_author_email );?>
                        <div class="level-bubble"><span class="lvl_value"><?php echo $authorLevel; ?></span></div></div>

                    </a>

                    <div class="comm-con">

                        <div class="comm-header">
                            <div class="comm-author"><?php echo($comm->comment_author);?></div>
                            <div class="comm-date"><?php echo get_comment_date( 'F d, Y', $comm->comment_ID ); ?></div>
                        </div>
                        <div class="comm-content">
                            <a href="<?php echo get_permalink($comment_post_ID); ?>">
                                <div class="comm-thumb">
                                    <?php echo get_the_post_thumbnail( $comment_post_ID, 'thumbnail' ); ?>
                                    <img  class="comm-thumb-play" src="<?php echo get_site_url(); ?>/wp-content/themes/Effective/images/play.png"/>
                            </div>

                            </a>
                            <?php echo($comm->comment_content);?>
                        </div>
                        <div class="comm-reply"><?php
                        //get the setting configured in the admin panel under settings discussions "Enable threaded (nested) comments  levels deep"
                        $max_depth = get_option('thread_comments_depth');
                        //add max_depth to the array and give it the value from above and set the depth to 1
                        $default = array(
                            'add_below'  => 'comment',
                            'respond_id' => 'respond',
                            'reply_text' => __('Reply'),
                            'login_text' => __('Log in to Reply'),
                            'depth'      => 1,
                            'before'     => '',
                            'after'      => '',
                            'max_depth'  => $max_depth,
                        );
                        comment_reply_link($default,$comm->comment_ID);
                        ?>
                        </div>
                        <div class="comm-zaki"><?php  if (class_exists('ZakiLikeDislike')) ZakiLikeDislike::getLikeDislikeHtml();  ?></div>

                    </div>
                </div>
            <?php endforeach;?>
        </div>
    </div>
</div>
<!-- TEST BUTTONS -->


<a id='clearDatabase' class="btn btn-primary hidden" href="#">Clear Database</a>
<a id='clearGamification' class="btn btn-primary hidden" href="#">Clear BADGES</a>
<input type='button' value='test' id="notification-trigger" class="btn btn-primary hidden"/>






</div>

<div id='response'><?php if ($response){echo($response);} ?></div>
<div id='activeArrayResponse'><?php if ($activeWorkout){echo($activeWorkout);} ?></div>
<div id='response2'></div>
<div id="inSchedule"></div>
<div id="workoutNumber"></div>


<?php 
    $programsProgressArrayFromDB = get_user_meta( $user_id, 'programsProgress', true );
    if ($programsProgressArrayFromDB) { $programsProgressArray = $programsProgressArrayFromDB; } else {
        $programsProgressArrayEncoded = json_encode($programsProgressArray);
        update_user_meta($user_id, 'programsProgress', $programsProgressArrayEncoded);
    }
                       
?>
<script type="text/javascript">
    var programNumbersArray = <?php echo json_encode($workoutsNum); ?>;
    var blocksBackgroundsArray = <?php echo json_encode($workoutsBackgrounds); ?>;
    var blocksColorsArray = <?php echo json_encode($workoutsColors); ?>;
    var blocksPhotosArray = <?php echo json_encode($workoutsPhotos); ?>;
    var blocksIconsArray = <?php echo json_encode($workoutsIcons); ?>;

    var programsProgressArray = <?php echo json_encode($programsProgressArray); ?>;
    console.log('programsProgressArray',programsProgressArray);
    try {
      programsProgressArray = JSON.parse(programsProgressArray);
      console.log('programsProgressArray',programsProgressArray);
    }
    catch(e) {
      // forget about it :)
      console.log('Forget about it');
    }
    var crossTrainingsArray = <?php echo json_encode($crossTrainingsArray); ?>;

    var notesArray = {};
    notesArray = <?php echo json_encode($notesArray); ?> ;
    console.log('nmotes array ',notesArray);
    
</script>


<script type="text/javascript">
    var exp = <?php echo json_encode($exp); ?>;
    var distance = <?php echo json_encode($distance); ?>;
    var touches = <?php echo json_encode($touches); ?>;
    var passes = <?php echo json_encode($passes); ?>;
    var shots = <?php echo json_encode($shots); ?>;

    var gamebrained = <?php echo json_encode($gamebrained); ?>;
    var classroomed = <?php echo json_encode($classroomed); ?>;

    var dribble = <?php echo json_encode($touches[1]); ?>;
    var aerial = <?php echo json_encode($touches[3]); ?>;
    var first = <?php echo json_encode($touches[2]); ?>;
    var juggles = <?php echo json_encode($touches[4]); ?>;

    var long_shots = <?php echo json_encode($shots[2]); ?>;
    var short_shots = <?php echo json_encode($shots[1]); ?>;

    var long_passes = <?php echo json_encode($passes[2]); ?>;
    var short_passes = <?php echo json_encode($passes[1]); ?>;

    var jogging = <?php echo json_encode($distance[1]); ?>;
    var high_tempo = <?php echo json_encode($distance[2]); ?>;
    var sprints = <?php echo json_encode($distance[3]); ?>;
    var activities = <?php echo $activities; ?>;

    var user_ID =   <?php echo json_encode($user_id); ?>;
    var user_dribble = <?php echo json_encode($touches[1]); ?>;
    var user_aerial = <?php echo json_encode($touches[3]); ?>;
    var user_first = <?php echo json_encode($touches[2]); ?>;
    var user_juggles = <?php echo json_encode($touches[4]); ?>;

    var user_aerial_shots = <?php echo json_encode($shots[3]); ?>;
    var user_long_shots = <?php echo json_encode($shots[2]); ?>;
    var user_short_shots = <?php echo json_encode($shots[1]); ?>;

    var user_long_passes = <?php echo json_encode($passes[2]); ?>;
    var user_short_passes = <?php echo json_encode($passes[1]); ?>;

    var user_jogging = <?php echo json_encode($distance[1]); ?>;
    var user_high_tempo = <?php echo json_encode($distance[2]); ?>;
    var user_sprints = <?php echo json_encode($distance[3]); ?>;


    var notificationsFeedArray = <?php echo json_encode($notificationsFeedArray); ?>;
    //console.log('array',notificationsFeedArray);
    //notificationsFeedArray = JSON.parse(notificationsFeedArray);


    try {
      notificationsFeedArray = JSON.parse(notificationsFeedArray);
    }
    catch(e) {
      // forget about it :)
      //console.log('Forget about it');
    }

      //console.log('notificationsFeedArray',notificationsFeedArray);

    var newNotificationCounter = <?php echo json_encode($newNotificationCounter); ?>;
</script>

<!-- New Carousel Scripts, making height working good -->
<script>
        var owl = $('.latest-blog-dashboard');
        owl.owlCarousel({
 
            slideSpeed : 300,
            paginationSpeed : 400,
            singleItem:true,
            autoPlay:4000
 
        });
        $(".next-button").click(function(){
            owl.trigger('owl.next');
        })
        $(".prev-button").click(function(){
          owl.trigger('owl.prev');
        })
</script>
<!-- END OF CAROUSEL -->

<?php get_template_part('avatar-prompt');?>

<?php get_footer(); ?>
