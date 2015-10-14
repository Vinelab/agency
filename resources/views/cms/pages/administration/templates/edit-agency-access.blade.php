<div class="col-sm-6 widget-container-span">
    <div class="widget-box widget-color-blue">

        <div class="widget-header widget-title">
            <h4 class="lighter">{{Lang::get('posts/form.agency_cms')}}</h4>
        </div>

        <div class="widget-body">
            <div class="widget-main no-padding">

                <table class="table table-striped table-bordered table-hover">

                    <thead class="thin-border-bottom">
                        <tr>
                            <th>
                                <i class="ace-icon  fa fa-circle-o"></i>
                                Section
                            </th>
                            <th>
                                <i class="ace-icon  fa fa-bookmark"></i>
                                Role
                            </th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($agency_sections as $section)

                        <tr>
                            <td>{{ $section->title }}</td>
                            <td>
                                <?php $selected_role = isset($edit_admin_agency_roles[$section->id]) ?
                                                            $edit_admin_agency_roles[$section->id] : 0; ?>
                                {{ Form::select("agency_sections[$section->alias]",
                                    $roles,
                                    $selected_role) }}
                            </td>
                        </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>
        </div>

    </div>
</div>
