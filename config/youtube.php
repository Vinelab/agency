<?php

return [

        'key'  => env('YOUTUBE_KEY'),
        'uri'  => [
            'videos.list'       => 'https://www.googleapis.com/youtube/v3/videos',
            'search.list'       => 'https://www.googleapis.com/youtube/v3/search',
            'channels.list'     => 'https://www.googleapis.com/youtube/v3/channels',
            'playlists.list'    => 'https://www.googleapis.com/youtube/v3/playlists',
            'playlistItems'     => 'https://www.googleapis.com/youtube/v3/playlistItems',
        ]

];
