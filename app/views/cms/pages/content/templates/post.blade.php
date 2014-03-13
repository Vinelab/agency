<li class="posts-container">
    <div class="dd-handle">
    	<div class="pull-left action-buttons">
            @if ($admin_permissions->has('update'))
                <a class="blue" href="{{URL::route('cms.post.edit',$post->slug)}}">
                    <i class="icon-pencil bigger-130"></i>
                </a>
            @endif
        </div>
        <a href="{{URL::route('cms.post.show',$post->slug)}}">
            <img src="{{$post->thumbnailURL()}}">
        </a>

        {{HTML::link(URL::route('cms.post.show',$post->slug),$post->title)}}
        <div class="author-status-container">
            <div class="status-container">
                @if($post->publish_state == 'published')
                    <span class="label label-success arrowed-right arrowed-in pull-left">
                        {{Lang::get('posts/form.published')}}: {{$post->publish_date}}
                    </span>
                @elseif ($post->publish_state == 'editing')
                    <span class="label label-info arrowed-right arrowed-in pull-left">
                        {{Lang::get('posts/form.editing')}} 
                    </span>
                @else
                    <span class="label label-danger arrowed-right arrowed-in pull-left">
                        {{Lang::get('posts/posts.scheduled_at')}} : 
                        {{$post->publish_date}}
                    </span>
                @endif
            </div>
            <div class="publisher-container">
                <h5 class="text-left"><i>{{Lang::get('posts/form.author')}}: {{ Str::title($post->admin()->first()->name)}}</i></h5>
            </div>
        </div>
    </div>
</li>