### v1.4

## Why?
[seaofclouds / tweet](https://github.com/seaofclouds/tweet) has always been one of my preferred methods of client-side twitter integration. With the advent of Twitter's 1.1 API and the deprecation of 1.0, client side timeline fetching and parsing is no longer feasable due to the authentication restrictions imposed by OAuth. This exists to serve as a layer in between, serving the JSON directly to Tweet.js and caching it in a flat .json file to compensate for the v1.1 API's access limitations on sites which require more than ~12 loads of a user's timeline per minute.

This mod works as an intermediary between [seaofclouds / tweet](https://github.com/seaofclouds/tweet) and the twitter JSON api which it uses to fetch tweets. It handles the server-side authentication process and (optionally) caches the JSON file locally to avoid being throttled, which it then passes off to [seaofclouds / tweet](https://github.com/seaofclouds/tweet) as normal. Simple yet effective.


## Features
* Works with Twitter API v1.1.
* Caches resources server-side in flat JSON files.
* Easily configurable.
* Supports all features of [seaofclouds / tweet](https://github.com/seaofclouds/tweet).
* Supports OAuth Token Authentication (via Twitter App)


## How to use
#### For existing [seaofclouds / tweet](https://github.com/seaofclouds/tweet) users:
1. Acquire /twitter/. How you wish to structure the files is up to you, but by default it is designed to be placed in the docroot of your website as a whole. (IE, domain.com/twitter).

2. Replace your existing JS reference to jquery.tweet.js to the modified one provided.
```<script type="text/javascript" charset="utf-8" src="/twitter/jquery.tweet.js"></script>```

3. You need to have a twitter App for your usage in order to obtain OAuth credentials, see https://dev.twitter.com/apps for help.

4. After creating your app, configure index.php with your OAuth credentials, and enable caching if you desire. Your cache directory of choice must be writable by PHP.

5. If you keep index.php somewhere besides /twitter/, specify the path to it via the modpath option, relative to your domain. For example:
```
<script type="text/javascript">
$(document).ready(function() {
    $('.twitterfeed').tweet({
        modpath: '/assets/twitter/',
        count: 5,
        loading_text: 'loading twitter feed...',
        /* etc... */
    });
</script>
```

6. That's all.


## Caveats
* Does not spontaneously spawn kittens. [in progress]


## Support
If you are having issues, set $debug = true; in index.php. Errors _should_ be logged to the JS console on execution.

If you still can't figure it out, feel free to contact me or submit an issue.

Unfortunately, I can not guarantee any support for this. I will, however, provide help and support within the constraints of my schedule.
