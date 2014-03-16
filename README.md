# Favourites Feed

This app combines your favourite tweets and saved reddit links into a single read-it-later list. No need for a separate bookmarking service.

## Setup

First, you'll need to [register an app with the Twitter API](https://apps.twitter.com/app/new) and enter the relevant details in `codebird.php`.

    /**
     * The OAuth consumer key of your registered app
     */
    private static $_oauth_consumer_key = 'OAUTH_KEY';

    /**
     * The corresponding consumer secret
     */
    private static $_oauth_consumer_secret = 'OAUTH_SECRET';

	[...]

    /**
     * The Request or access token. Used to sign requests
     */
    private $_oauth_token = 'OAUTH_TOKEN';

    /**
     * The corresponding request or access token secret
     */
    private $_oauth_token_secret = 'OAUTH_TOKEN_SECRET';

Then just point to `/index.php` and you're good to go.