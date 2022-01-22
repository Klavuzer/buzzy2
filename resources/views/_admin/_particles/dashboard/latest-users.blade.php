  <div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">{{ trans('admin.latestmembers') }}</h3>
        <div class="box-tools pull-right">
            <span class="label label-danger">{{ $todayusers }} {{ trans('admin.newmemberstoday') }}</span>
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
    </div><!-- /.box-header -->
    <div class="box-body no-padding">

        <ul class="users-list clearfix">
            @foreach($lastusers as $user)
            <li style=" width: 20%;">
                <img src="{{ makepreview($user->icon, 'b', 'members/avatar') }}" width="110" height="110"
                    style="max-height:110px;min-height:110px" alt="User Image">
                <a class="users-list-name" target="_blank" href="/profile/{{ $user->username_slug }}">{{
                    $user->username }}</a>
                <span class="users-list-date">{{ $user->created_at->diffForHumans() }}</span>
            </li>
            @endforeach
        </ul><!-- /.users-list -->
    </div><!-- /.box-body -->
    <div class="box-footer text-center">
        <a href="/admin/users" class="uppercase">{{ trans('admin.viewall') }}</a>
    </div><!-- /.box-footer -->
</div>
