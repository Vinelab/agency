<script type="text/javascript">
	var Routes={};
	Routes.cms_post_destroy = "{{URL::route('cms.content.posts.destroy','')}}";
	Routes.cms_post_create = "{{URL::route('cms.content.posts.store')}}";
	Routes.cms_content_show = "{{URL::route('cms.content.show','')}}"
	Routes.cms_post_update = "{{URL::route('cms.content.posts.update','')}}";
	Routes.cms_post_tmp = "{{URL::route('cms.content.posts.photos.store')}}";
	Routes.cms_post_photos_destroy = "{{URL::route('cms.content.posts.photos.destroy','')}}"
	Routes.cms_tags = "{{URL::route('cms.content.posts.tags')}}";
	Routes.photo_location = "{{Config::get('media.location')}}";
</script>