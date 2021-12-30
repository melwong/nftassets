<?php
function myticket_shortcode_twitter( $atts, $content = null ) {
	extract( shortcode_atts( array(
		"image" => '',
		"title" => '',
		"title_twitter" => '',
		"placeholder" => '',
        "button_text" => '',
        "twitter_c_key" => '',
        "twitter_c_secret" => '',
        "twitter_a_token" => '',
        "twitter_a_key" => '',
        "twitter_username" => '',
		"twitter_max" => 6,
	), $atts ) );

	ob_start();
    if(!isset($after_widget))
        $after_widget='';

    //check settings and die if not set
    if ( empty( $atts['twitter_c_key'] ) || empty( $atts['twitter_c_secret'] ) || empty( $atts['twitter_a_token'] ) || empty( $atts['twitter_a_key'] ) || empty( $atts['twitter_username'] ) ){
        echo '<strong>'.__('Please fill all MyTicket Twitter settings!','myticket').'</strong>' . $after_widget;
        return;
    }
    
    //check if cache needs update
    $myticket_twitter = get_option( 'myticket_twitter' );
    $diff = time() - $myticket_twitter;
    $crt = get_theme_mod( 'twitter_cache' ) * 3600 * 24;
    
    //	yes, it needs update
    if ( $diff >= $crt || empty( $myticket_twitter ) ){

        if ( !require_once(  plugin_dir_path(__FILE__) . 'widgets/recent-tweets-widget/twitteroauth.php' ) ){
            echo '<strong>'.__('Couldn\'t find twitteroauth.php!','myticket').'</strong>' . $after_widget;
            return;
        }
        
         //    function getConnectionWithAccessToken( $cons_key, $cons_secret, $oauth_token, $oauth_token_secret ) {
         //        $connection = new TwitterOAuth( $cons_key, $cons_secret, $oauth_token, $oauth_token_secret );
         //        return $connection;
         //    }
         // echo "aaa";
         //    $connection = getConnectionWithAccessToken( $atts['twitter_c_key'], $atts['twitter_c_secret'], $atts['twitter_a_token'], $atts['twitter_a_key'] );

        //echo "aaa";

        $connection = new TwitterOAuth( $atts['twitter_c_key'], $atts['twitter_c_secret'], $atts['twitter_a_token'], $atts['twitter_a_key'] );

        $tweets = $connection->get( "https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=".$atts['twitter_username']."&count=".$atts['twitter_username']."&exclude_replies=true" );// or die( 'Couldn\'t retrieve tweets! Wrong username?' );

        if( !empty( $tweets->error ) ){
            echo '<strong>'.$tweets->error.'</strong><br />' . __( 'You\'ll need to regenerate it <a href="https://apps.twitter.com/" target="_blank">here</a>!', 'myticket' );
            return;
        }

        $tweets_array = array();
        if(count( $tweets )>1)
        for( $i = 0; $i <= count( $tweets ); $i++ ){
            if( !empty( $tweets[$i] ) ){
                $tweets_array[$i]['created_at'] = $tweets[$i]->created_at;
                //clean tweet text
                $tweets_array[$i]['text'] = preg_replace( '/[\x{10000}-\x{10FFFF}]/u', '', $tweets[$i]->text );
                
                if( !empty( $tweets[$i]->id_str ) ){
                    $tweets_array[$i]['status_id'] = $tweets[$i]->id_str;
                }
            }
        }

        //save tweets to wp option
        update_option( 'myticket_twitter_plugin_tweets', serialize( $tweets_array ) );
        update_option( 'myticket_twitter', time() );
        
        echo '<!-- twitter cache has been updated! -->';
    }
    
	
    $myticket_twitter_plugin_tweets = maybe_unserialize( get_option( 'myticket_twitter_plugin_tweets' ) );
    if( !empty( $myticket_twitter_plugin_tweets ) && is_array( $myticket_twitter_plugin_tweets ) ){  ?>

        <div class="section-content">
            <div class="twitter-header clearfix">
                <div class="twitter-name">
                    <a href="https://twitter.com/<?php echo $atts['twitter_username']; ?>">
                        <?php 
                        if ( $atts['twitter_logo'] != '' ){
                            $image_url = wp_get_attachment_image_src($atts['twitter_logo'],"full",false);
                        } ?>
                        <img src="<?php echo esc_url( $image_url[0] ); ?>" alt="image">
                        <strong><?php echo $atts['title_twitter']; ?></strong>
                        <span>@<?php echo $atts['twitter_username']; ?></span>
                    </a>
                </div>
                <div class="twitter-btn">
                    <a href="https://twitter.com/<?php echo $atts['twitter_username']; ?>"><?php esc_html_e('Follow', 'myticket'); ?></a>
                </div>
            </div>
            <div class="tweet-list clearfix">
                <ul class="clearfix">

                    <?php $fctr = 1;
                        foreach ( $myticket_twitter_plugin_tweets as $tweet ){
                            if ( !empty( $tweet['text'] ) ){
                                
                                if ( empty( $tweet['status_id'] ) ){ $tweet['status_id'] = ''; }
                                if ( empty( $tweet['created_at'] ) ){ $tweet['created_at'] = ''; } 

                                $now = new DateTime();
                                $future_date = new DateTime($tweet['created_at']);
                                
                                ?>

                                <li class="row tweet-item">
                                    <div class="col-sm-10">
                                        <p><?php echo tp_convert_links( $tweet['text'] ); ?></p>
                                    </div>
                                    <div class="col-sm-2">
                                        <span><?php echo myticket_human_timing( strtotime( $tweet['created_at'] ) ); //echo date_i18n( get_option( 'date_format' ), intval( strtotime( $tweet['created_at'] ) ) ); ?></span>
                                    </div>
                                </li>

                                <?php
                                if ( $fctr == intval( $atts['twitter_max'] ) ){ break; }
                                $fctr++;
                            }
                        }
                    ?>
                </ul>
            </div>
        </div>


    <?php
        
    }else{
        print '<div class="myticket_recent_tweets">' . __('Couldn\'t retrieve tweets! Verify your credentials.','myticket') . '</div>';
    }
        
    $content = ob_get_contents();
	ob_end_clean();
	return $content;
}