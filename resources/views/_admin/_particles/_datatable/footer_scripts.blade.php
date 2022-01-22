<!-- iCheck -->
<script src="{{ asset('assets/plugins/adminlte/plugins/iCheck/icheck.min.js') }}"></script>
<script src="{{ asset('assets/plugins/adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/adminlte/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/plugins/adminlte/plugins/datatables/dataTables.responsive.min.js') }}"></script>

@php($only = (request()->query('only') ? '&only=' . request()->query('only') : '') )
<script>
    $(document).ready(function() {
        function toggle_table_actions() {
            var selected_count = $(".table").find('td :checked').length;

            if (selected_count > 0) {
                $(".table-actions-menu").show();
            } else {
                $(".table-actions-menu").hide();
            }
        }

        function do_table_action(data_url){
            $(".overlay").removeClass('hide');

            $.ajax({
                type: "GET",
                dataType: 'json',
                url: data_url , // This is the URL to the API
                success: function(data) {
                    setTimeout(function() {
                        table.api().ajax.reload();
                    }, 500);
                    setTimeout(function() {
                        $(".cho input[type='checkbox']").iCheck("uncheck");
                        $(".cho .fa").removeClass("fa-check-square-o").addClass('fa-square-o');
                        $(".table-actions-menu").removeClass('loading');
                        $(".overlay").addClass('hide');
                        toggle_table_actions();
                    }, 1000);
                },
                error: function(data) {
                    swal({
                        type: "warning",
                        title: data.statusText,
                        text: data.responseJSON.errors,
                        timer: 2000,
                        showConfirmButton: false
                    });
                    $(".overlay").addClass('hide');
                },
            });
        }
        $('.doaction').on('click', function() {
            var data_url = $(this).attr('data-url');

            var ids = '';
            $(".table").find('td :checked').each(function() {
                ids += $(this).val() + ',';
            });

            if (ids === '') {
                return;
            }
            do_table_action(data_url+ "&ids=" + ids.slice(0, -1))
        });


        var table = $('#table').dataTable({
            order: [
                [{{$order_count ?? 1}}, 'desc']
            ],
            processing: true,
            serverSide: true,
            autoWidth: false,
            language: {
                "sDecimal": ",",
                "infoEmpty": "{!! trans('admin.sEmptyTable')  !!}",
                "sInfo": "{!! trans('admin.sInfo')  !!}",
                "sInfoEmpty": "{!! trans('admin.sInfoEmpty')  !!}",
                "sInfoFiltered": "{!! trans('admin.sInfoFiltered')  !!}",
                "sInfoPostFix": "",
                "sInfoThousands": ".",
                "sLengthMenu": "{!! trans('admin.sLengthMenu')  !!}",
                "sLoadingRecords": "{!! trans('admin.sLoadingRecords')  !!}",
                "sProcessing": "{!! trans('admin.sProcessing')  !!}",
                "sSearch": "{!! trans('admin.sSearch')  !!}",
                "sZeroRecords": "{!! trans('admin.sZeroRecords')  !!}",
                "oPaginate": {
                    "sFirst": "{!! trans('admin.sFirst')  !!}",
                    "sLast": "{!! trans('admin.sLast')  !!}",
                    "sNext": "{!! trans('admin.sNext')  !!}",
                    "sPrevious": "{!! trans('admin.sPrevious')  !!}"
                },
                "oAria": {
                    "sSortAscending": "{!! trans('admin.sSortAscending')  !!}",
                    "sSortDescending": "{!! trans('admin.sSortDescending')  !!}"
                }
            },
            ajax: {
                "url": '{!! $data_url !!}',
                "data": function() {
                    setTimeout(function() {
                        BuzzyAdmin.init();
                    }, 2000);
                }
            },
            columns: {!!json_encode(array_values(array_filter($columns)))!!},
            drawCallback: function(settings) {
                $('.table input[type="checkbox"]').iCheck({
                    checkboxClass: 'icheckbox_flat-blue',
                    radioClass: 'iradio_flat-blue'
                }).on('ifChecked', function(event) {
                    toggle_table_actions();
                }).on('ifUnchecked', function(event) {
                    toggle_table_actions();
                });
                $('.cho input[type="checkbox"]').iCheck({
                    checkboxClass: 'icheckbox_flat-blue',
                    radioClass: 'iradio_flat-blue'
                }).on('ifChecked', function(event) {
                    toggle_table_actions();
                    $(".table input[type='checkbox']").iCheck("check");
                    $(".fa", this).removeClass("fa-square-o").addClass('fa-check-square-o');
                }).on('ifUnchecked', function(event) {
                    toggle_table_actions();
                    $(".table input[type='checkbox']").iCheck("uncheck");
                    $(".fa", this).removeClass("fa-check-square-o").addClass('fa-square-o');
                });
                $('.do_table_action').on('click', function() {
                    var data_url = $(this).attr('data-url');

                    do_table_action(data_url);
                });
            }
        });
    });
</script>
