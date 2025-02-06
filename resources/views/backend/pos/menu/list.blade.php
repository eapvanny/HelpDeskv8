<!-- Master page  -->
@extends('backend.layouts.master')

<!-- Page title -->
@section('pageTitle') Menu @endsection
<!-- End block -->

<!-- Page body extra class -->
@section('bodyCssClass') @endsection
<!-- End block -->

<!-- BEGIN PAGE CONTENT-->
@section('pageContent')
    <!-- Section header -->
    <section class="content-header">
        <h1>
            {{ __('Menu') }}
            <small> {{ __('List') }} </small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{URL::route('user.dashboard')}}"><i class="fa fa-dashboard"></i> {{ __('Dashboard') }} </a></li>
            <li> {{ __('POS') }} </li>
            <li class="active">{{ __('Menu') }}</li>
        </ol>
    </section>
    <!-- ./Section header -->
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header">
                        <form method="get" id="frm_search">
                            {!! AppHelper::selectOrgOfUser($seleted_org) !!}
                        </form>
                        <div class="col-md-3 pull-right">
                            @can('pos.menu.store')
                            <div class="form-group box-tools pull-right">
                                <a class="btn btn-info text-white" href="{{ URL::route('pos.menu.create') }}"><i class="fa fa-plus-circle"></i> {{ __('Add New') }} </a>
                            </div>
                            @endcan
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="table-responsive">
                        <table id="datatabble" class="table table-bordered table-striped list_view_table display responsive no-wrap datatable-server" width="100%">
                            <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="10%"> {{ __('Organization') }} </th>
                                <th width="10%"> {{ __('Icon') }} </th>
                                <th width="10%"> {{ __('Name') }} </th>
                                <th width="25%"> {{ __('Description') }} </th>
                                <th width="10%"> {{ __('Status') }} </th>
                                <th class="notexport" width="15%"> {{ __('Action') }} </th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>

                        </table>
                    </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="btn-group">
                            <form id="myAction" method="POST">
                                @csrf
                                <input name="_method" type="hidden" value="DELETE">
                            </form>
                        </div>
                </div>
            </div>
        </div>

    </section>
    <!-- /.content -->
@endsection
<!-- END PAGE CONTENT-->

<!-- BEGIN PAGE JS-->
@section('extraScript')
    <script type="text/javascript">
        $(document).ready(function () {
            window.postUrl = '{{URL::Route("pos.menu.status", 0)}}';
            window.changeExportColumnIndex = 5;
            window.excludeFilterComlumns = [0,1,6,7];
            Generic.initCommonPageJS();
            Generic.initDeleteDialog();
            window.filter_org = 1;
            Generic.initFilter();

            $('select[name="org_id"]').on('change', function () {
                let org_id = $(this).val();
                if (org_id.trim()) {
                    $('#frm_search').submit();
                }
            });

            $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }

        });
        var t = $('#datatabble').DataTable({
            processing: false,
            serverSide: true,
            bLengthChange: false,
            ajax:{
                url: "{!!  route('pos.menu.index',request()->all()) !!}",
            },
            columns:[
                {
                    data: 'id',
                    name: 'id',
                },
                {
                    data: 'org_id',
                    name: 'org_id'
                },
                {
                    data: 'icon',
                    name: 'icon',
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'description',
                    name: 'description'
                },
                {
                    data: 'status',
                    name: 'status',
                    orderable: false
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false
                },
            ],
            "fnDrawCallback": function() {
                $('#datatabble input.statusChange').bootstrapToggle({
                    on: "Active",
                    off: "Deactive"
                });
            }
        });

        // t.on( 'order.dt search.dt', function () {
        //     t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        //         cell.innerHTML = i+1;
        //     } );
        // } ).draw();

        $('#datatabble').delegate('.delete','click', function(e){
            let action = $(this).attr('href');
            console.log()
            $('#myAction').attr('action',action);
            e.preventDefault();
            swal({
                title: 'Are you sure?',
                text: 'You will not be able to recover this record!',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dd4848',
                cancelButtonColor: '#8f8f8f',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, keep it'
            }).then((result) => {
                if (result.value) {
                    $('#myAction').submit();
                }
            });
        });
        });
    </script>
@endsection
<!-- END PAGE JS-->
