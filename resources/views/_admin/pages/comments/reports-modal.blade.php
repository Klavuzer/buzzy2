<div class="modal modal-info in" id="modal{{$modal_id}}" style="display: block; padding-left: 0px;">
    <div class="modal-dialog" style="width:500px">
        <div class="modal-content" >
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="fa fa-remove"></i></button>
                <h4 class="modal-title">{!! __('Reports') !!}</h4>
            </div>

            <div class="table-responsive" style="overflow: auto;">
                <table class="table no-margin" style="overflow: auto;">
                    <thead>
                        <tr>
                            <th width="20%">{{ trans('admin.User') }}</th>
                            <th width="55%">{{ __('Report Reason') }}</th>
                            <th width="25%">{{ trans('admin.Dates') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($reports as $report)
                        <tr>
                            <td>
                                <a href="{{url('/profile/' . $report->user->username_slug)}}" target="_blank" class="product-img">
                                    <img src="{{ makepreview($report->user->thumb, 's', 'members/avatar') }}" width="30px" style="margin-right:5px">
                                    <span>{{$report->user->username}}</span>
                                </a>
                            </td>
                            <td>
                                {{ $report->body ?? '-' }}
                            </td>
                            <td>
                                {{ $report->created_at->format('Y-m-d H:i:s') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div><!-- /.table-responsive -->
             <div class="clearfix"></div>
        </div>
    </div>
</div>
