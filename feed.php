<?

include('header.php');
?>

<h1 class="feed">Favourites Feed</h1>

<?
function linkify($text){
	// linkify URLs
	$text = preg_replace('/(https?:\/\/\S+)/','<a href="\1">\1</a>',$text);
	// linkify twitter users
	$text = preg_replace('/(^|\s)@(\w+)/','\1<a href="http://twitter.com/\2">@\2</a>',$text);
	// linkify tags
	$text = preg_replace('/(^|\s)#(\w+)/','\1<a href="http://search.twitter.com/search?q=%23\2">#\2</a>',$text);
	return $text;
}

// Twitter
if(!empty($_GET['twitter'])) {
	foreach ($_GET['twitter'] as $twitterName) {
		// Check for valid twitter username
		if(preg_match('/[^a-zA-Z0-9_]/', $twitterName) == false && strlen($twitterName) > 0) {
			$display_names['twitter'] = $twitterName;
			require_once('codebird.php');
			$cb = Codebird::getInstance();
			$twitter_query = $cb->favorites_list(array('screen_name' => $twitterName, 'count' => '200'));
			foreach($twitter_query as $tweet){
				$item = '<li class="clearfix">
							<p class="authorinfo clearfix">									
								<img src="'.$tweet->user->profile_image_url.'" />
								<span><a class="username" href="https://twitter.com/'.$tweet->user->screen_name.'">@'.$tweet->user->screen_name.'</a>	
								<a class="timestamp" href="https://twitter.com/'.$tweet->user->screen_name.'/status/'.$tweet->id_str.'">'.date('j M Y H:i',strtotime($tweet->created_at)).'</a></span>
							</p>
							<p class="tweet">'.linkify($tweet->text).'</p>
						</li>';
				$favs_array[strtotime($tweet->created_at)] = $item;
			}
		}
		else {
			$errors[] = 'Invalid Twitter username';
		}
	}
}

// Reddit
if(!empty($_GET['reddit'])) {
	foreach ($_GET['reddit'] as $redditURL) {
		preg_match('/reddit\.com\/saved\.\w+\?feed=([a-zA-Z0-9_-]+)&user=([a-zA-Z0-9_-]+)/', $redditURL, $matches);
		$redditName = $matches[2];
		$redditFeed = $matches[1];
		// Check for proper character set
		if(preg_match('/[^a-zA-Z0-9_-]/', $redditName) == false && preg_match('/[^a-z0-9]/', $redditFeed) == false) {
	        $ch = curl_init();
	        curl_setopt($ch, CURLOPT_URL, 'http://www.reddit.com/user/'.$redditName.'/saved.json?feed='.$redditFeed.'&user='.$redditName); 
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	        $output = curl_exec($ch);
	        curl_getinfo($ch);
	        curl_close($ch); 
			$reddit_query = json_decode($output,true);
			// Check that the query retrns a valid response
			if($reddit_query != NULL && !isset($reddit_query['error'])) {
				$display_names['reddit'] = $_GET['reddit_name'];
				foreach($reddit_query[data][children] as $post){
					$item = '<li><p class="authorinfo clearfix">';
					if($post[data][thumbnail] != 'self' && $post[data][thumbnail] != 'default'){
						$item .= '<img src="'.$post[data][thumbnail].'" />';
					}
					$item .= '<span><a class="username" href="http://reddit.com/r/'.$post[data][subreddit].'">/r/'.$post[data][subreddit].'</a><a class="timestamp" href="http://reddit.com'.$post[data][permalink].'">'.date('j M Y H:i',$post[data][created]).'</a></span></p><p class="tweet"><a href="'.$post[data][url].'">'.$post[data][title].'</a></p></li>';
					$favs_array[$post[data][created]] = $item;
				}
			}
			else {
				$errors[] = 'Invalid Reddit URL';
			}
		}
		else {
			$errors[] = 'Invalid Reddit URL';
		}
	}
}

if(isset($errors)){
	?>
		<ul class="errors">
			<?php
				foreach($errors as $error){
					echo '<li>'.$error.'</li>';
				}
			?>
		</ul>
	<?php
}
if(isset($_SERVER['HTTP_REFERER'])){
	?>
	<ul class="errors">
		<li>Tip: Bookmark this page to avoid filling out the form again &mdash; your settings are all contained within the page URL.</li>
	</ul>
	<?
}
?>

<ul class="fav_tweets">
	<?php
		// sort and print the feed
		krsort($favs_array);
		foreach($favs_array as $post){
			echo $post."\n";
		}
	?>
</ul>

<? include('footer.php') ?>