<link rel="stylesheet" href="{{ asset('assets/plugins/adminlte/plugins/datatables/dataTables.bootstrap.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/adminlte/plugins/datatables/responsive.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/adminlte/plugins/iCheck/flat/blue.css') }}">

<style>
    .table-actions-menu {
        display: none;
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        z-index: 20;
        background-color: #fff;
        height: 60px;
        padding: 10px 15px;
        width: 100%;
    }

    .table tbody td {
        position: relative
    }

    .table tbody td div.icheckbox_flat-blue {
        position: absolute;
        top: 50%;
        margin-top: -12px;
    }

    .dataTables_empty{
        width: 500px;
        max-width: 100%;
        padding: 30px 10px;
        display: flex;
    }
</style>
