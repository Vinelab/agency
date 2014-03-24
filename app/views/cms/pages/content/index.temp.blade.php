@section("head")
    @parent

@stop
@section('content')
	<div class="col-xs-12">

        @if ($admin_permissions->has('create'))
        <div class="row">
            <a href="{{ URL::route('cms.content.create') }}" class="btn btn-primary">
                <span class="icon-plus"></span>
                New Content
            </a>
        </div>
        @endif

        <?php
            $previous="";
            $current="";
        ?>
        <div class="span6">
            <div class="dd" id="nestable">
                <ol class="dd-list">
                    @foreach($contents as $content )
                        @if(isset($content["parent"]) && $content["parent"]=="home")
                            <?php 
                                $previous = $content["id"];
                                $current = $content["id"];
                            ?>
                        @else
                            @if(key($content)=="parent")
                                <?php
                                    $previous = $current;
                                    $current = $content["id"];
                                ?>
                                @if($content["parent_id"]!=$previous)
                                    </ol>
                                    </li>
                                    <?php
                                        $previous = $content["parent_id"];
                                        $current = $content["id"];
                                    ?>
                                @endif

                                <li class="dd-item" data-id="{{$content['id']}}">
                                    <div class="dd-handle">
                                        {{HTML::link(URL::route('cms.content.posts',$content['id']),$content["parent"])}}

                                        <div class="pull-right action-buttons">
                                            <a class="green" href="/cms/content/assign?content={{$content['id']}}">
                                                <i class="fa fa-plus bigger-130"></i>
                                                Add Post
                                            </a>

                                            <a class="blue" href="{{URL::Route('cms.content.edit',['id'=>$content['id']])}}">
                                                <i class="icon-pencil bigger-130"></i>
                                            </a>

                                            <a class="red" href="{{URL::Route('cms.content.destroy',['id'=>$content['id']])}}">
                                                <i class="icon-trash bigger-130"></i>
                                            </a>
                                        </div>
                                    </div>
                                
                                    @if($previous==$content["parent_id"])
                                        <ol class="dd-list">
                                    @endif
                            @elseif(key($content)=="children")
                                @if($content["parent_id"]==$current)
                                    <li class="dd-item" data-id="{{$content['id']}}">
                                        <div class="dd-handle">
                                            
                                            {{HTML::link(URL::route('cms.content.posts',$content['id']),$content["children"])}}

                                            <div class="pull-right action-buttons">
                                                <a class="green" href="/cms/content/assign?content={{$content['id']}}">
                                                    <i class="fa fa-plus bigger-130"></i>
                                                    Add Post
                                                </a>

                                                <a class="blue" href="{{URL::Route('cms.content.edit',['id'=>$content['id']])}}">
                                                    <i class="icon-pencil bigger-130"></i>
                                                </a>

                                                <a class="red" href="{{URL::Route('cms.content.destroy',['id'=>$content['id']])}}">
                                                    <i class="icon-trash bigger-130"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </li>
                                @elseif($content["parent_id"]!=$current)
                                    </ol>
                                    <li class="dd-item" data-id="{{$content['id']}}">
                                        <div class="dd-handle">
                                            {{HTML::link(URL::route('cms.content.posts',$content['id']),$content["children"])}}
                                            <div class="pull-right action-buttons">
                                                <a class="green" href="/cms/content/assign?content={{$content['id']}}">
                                                    <i class="fa fa-plus bigger-130"></i>
                                                    Add Post
                                                </a>

                                                <a class="blue" href="{{URL::Route('cms.content.edit',['id'=>$content['id']])}}">
                                                    <i class="icon-pencil bigger-130"></i>
                                                </a>

                                                <a class="red" href="{{URL::Route('cms.content.destroy',['id'=>$content['id']])}}">
                                                    <i class="icon-trash bigger-130"></i>
                                                </a>
                                            </div>                                        
                                        </div>
                                    </li>
                                    <?php
                                        $previous = $current;
                                        $current = $content["parent_id"];
                                    ?>
                                @endif
                            @endif
                        @endif
                    @endforeach
                </ol>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    @parent
        <script src="/assets/js/jquery.nestable.min.js"></script>
        {{HTML::script("/cms/js/content/index.js")}}

        
@stop

@include('cms.layout.master')