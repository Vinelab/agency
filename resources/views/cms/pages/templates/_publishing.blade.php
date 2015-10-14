{{-- publish state and date wrapper section --}}
<div class="form-group">
    <label class="control-label"> {{Lang::get('news/news.publish_state')}}* </label>
</div>

<div class="form-group">
    <div class="col-sm-12">

        {{-- publish state section --}}
        <div class="publish-date-margin">


            <div class="radio">
                <label>
                    <input name="publishstate[]" type="radio" id="editing" value="editing" class="ace publish_state"
                           checked>
                    <span class="lbl"> {{Lang::get('news/news.editing')}}</span>
                </label>
            </div>
            @if (Auth::hasPermission('publish'))
                <div class="radio">
                    <label>
                        <input name="publishstate[]" type="radio" id="published" value="published"
                               class="ace publish_state" {{($updating && $model->publish_state == 'published' ? 'checked' : '')}}>
                        <span class="lbl"> {{Lang::get('news/news.publish')}}</span>
                    </label>
                </div>
            @endif


        </div>

    </div>

    {{-- publish date section --}}
    @if (Auth::hasPermission('publish'))

        <div id="date-time-picker-holder"
             class="{{ ($updating ? ($model->publish_state == 'editing' ? 'hidden' : '') : 'hidden' )}}">
            <div class="form-group margin-right-zero">
                <label class="control-label"> {{Lang::get('news/news.publish_date')}} </label>
            </div>
            <div class="col-sm-12">
                <div class="time-picker" dir="ltr">
                    <div class="input-group">
                        <input id="publish_date_timepicker" name="publish_date" type="text" class="form-control"
                               value="{{($updating) ? (($model->publish_state == 'published') ? $model->publish_date : '') : ''}}">
                            <span class="input-group-addon">
                                <i class="fa fa-clock-o bigger-110"></i>
                            </span>
                    </div>
                </div>
            </div>

            {{-- timezone selector --}}
            <label class="control-label"> {{Lang::get('news/news.timezone')}}* </label>
            <br>
            <div class="col-sm-12">
                <select class="special-elector select2 tag-input-style" style="width: 300px;" id="timezone" name="timezone">
                    <option value=""></option>
                    @foreach($timezones as $timezone)
                        <option {{(!$updating && $timezone == 'Asia/Beirut') ? 'selected' : ''}}  value="{{$timezone}}">{{$timezone}}</option>
                    @endforeach
                </select>
            </div>

        </div>

    @endif

</div>


<script>


    $(document).ready(function() {
        // call the timezone selector
        $(".special-elector").select2();


        // showing and hiding the date picker based on the selected radio button
        $(".publish_state").change(function () {
            showHide(this);
        });

        function showHide(ele) {
            if ($(ele).val() == "published") {
                $('#date-time-picker-holder').removeClass('hidden');
            } else if ($(ele).val() == "editing") {
                $('#date-time-picker-holder').addClass('hidden');
            }
        }

    });


</script>
