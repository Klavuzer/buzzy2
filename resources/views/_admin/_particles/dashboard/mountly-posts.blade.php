<!-- Custom tabs (Charts with tabs)-->
<!-- solid sales graph -->
<div class=" nav-tabs-custom box box-solid bg-green-gradient">
    <div class="box-header">
        <i class="fa fa-th"></i>
        <h3 class="box-title">{{ trans('admin.Postsonlast30days') }}</h3>
        <div class="box-tools pull-right">
            <ul class="nav nav-tabs pull-right" style="border:0">
                <li class="active"><a href="#news-chart" style="color:#000;border-radius: 5px;"
                        class="btn btn-box-tool box-line-get" data-type="news" data-toggle="tab">{{
                        trans('admin.news') }}</a></li>
                <li><a href="#lists-chart" style="color:#000;border-radius: 5px;"
                        class="btn btn-box-tool box-line-get" data-type="lists" data-toggle="tab">{{
                        trans('admin.lists') }}</a></li>
                <li><a href="#quizzes-chart" style="color:#000;border-radius: 5px;"
                        class="btn btn-box-tool box-line-get" data-type="quizzes" data-toggle="tab">{{
                        trans('admin.quizzes') }}</a></li>
                <li><a href="#polls-chart" style="color:#000;border-radius: 5px;"
                        class="btn btn-box-tool box-line-get" data-type="polls" data-toggle="tab">{{
                        trans('admin.polls') }}</a></li>
                <li><a href="#videos-chart" style="color:#000;border-radius: 5px;"
                        class="btn btn-box-tool box-line-get" data-type="videos" data-toggle="tab">{{
                        trans('admin.videos') }}</a></li>
            </ul>
        </div>
    </div>
    <div class="tab-content box-body border-radius-none" style="background-color: transparent">
        <!-- Morris chart - Sales -->
        <div class="chart tab-pane active" id="news-chart" style="position: relative;height: 250px;"></div>

        <div class="overlay lineloader">
            <i class="fa fa-refresh fa-spin" style="color:#fff"></i>
        </div>
    </div><!-- /.box-body -->
</div><!-- /.box -->
<style>
    #sales-chart path {
        stroke: rgba(255, 255, 255, 0.4);
        stroke-width: 3px;
    }
</style>
