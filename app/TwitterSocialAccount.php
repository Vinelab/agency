<?php namespace Agency;

/**
 * @category Model
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */
class TwitterSocialAccount extends SocialAccount
{
    protected $label = ['Twitter', 'SocialAccount'];

    protected $fillable = [
        'provider',
        'social_id',
        'id_str',
        'name',
        'screen_name',
        'location',
        'profile_location',
        'description',
        'url',
        'protected',
        'followers_count',
        'friends_count',
        'listed_count',
        'favourites_count',
        'utc_offset',
        'time_zone',
        'geo_enabled',
        'verified',
        'statuses_count',
        'lang',
        'contributors_enabled',
        'is_translator',
        'is_translation_enabled',
        'profile_background_color',
        'profile_background_image_url',
        'profile_background_image_url_https',
        'profile_background_tile',
        'profile_image_url',
        'profile_image_url_https',
        'profile_banner_url',
        'profile_link_color',
        'profile_sidebar_border_color',
        'profile_sidebar_fill_color',
        'profile_text_color',
        'profile_use_background_image',
        'default_profile',
        'default_profile_image',
        'following',
        'follow_request_sent',
        'notifications',
        'avatar',
    ];
}
