<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css">
</head>

<body>
    {{-- <h1>hello</h1> --}}

    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-3">
                <select class="form-control" id="select-choise">
                    <option>Select Choice</option>
                    <option value="d">Day</option>
                    <option value="w">Weekly</option>
                    <option value="m">Monthly</option>
                    <option value="y">yearly</option>
                    <option value="customize">Customize</option>
                </select>
            </div>
            <div class="col-md-5 d-flex d-none" id="datefetch">
                <input type="date" class="form-control mx-2" name="sdate" placeholder="Enter date">
                <input type="date" class="form-control mx-2" name="edate" placeholder="Enter date">
                <button class="btn btn-success date-wise">Fetch</button>
            </div>
        </div>
        <div class="row justify-content-center mt-5">
            <div class="col-md-10">
                <table class="table" id="myTable">
                    <thead>
                        <tr>
                            <th scope="col">id</th>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Created_at</th>
                            <th scope="col">Updated_at</th>

                        </tr>
                    </thead>

                    {{-- <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <th scope="row">{{ $user->id }}</th>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->created_at }}</td>
                                <td>{{ $user->updated_at }}</td>

                            </tr>
                        @endforeach


                    </tbody> --}}
                </table>
                <div class="d-flex">
                    <form id="export-form" action="{{ route('downloadFilteredFile') }}" method="POST">
                        @csrf
                        <input type="hidden" name="choice" id="export-choice">
                        <input type="hidden" name="sdate" id="export-sdate">
                        <input type="hidden" name="edate" id="export-edate">
                        <button type="submit" class="btn btn-primary mt-2">Export Filtered CSV</button>
                    </form>
                    <button class="btn btn-danger mx-2"><a href="{{ route('logout') }}"
                            class="text-decoration-none text-light">logout</a></button>
                </div>

            </div>
        </div>
    </div>
</body>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>


<script>
    // ************** load all code *************************
    $(document).ready(function() {
        $('#myTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('get.users') }}",
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'updated_at',
                    name: 'updated_at'
                }
            ]

        });
        // ******** end of load all data ******


        // ****************************** start choise wise code ***********************

        $('#select-choise').on('change', function(event) {
            event.preventDefault();
            var choice = $(this).val();


            $('#export-choice').val(choice);

            if (choice === 'customize') {
                showform();
            } else {
                $('#datefetch').addClass('d-none');
            }

            $.ajax({
                url: "{{ route('get.filtered.users') }}",
                method: 'GET',
                data: {
                    choice: choice
                },
                success: function(response) {
                    // console.log(response);

                    $('#myTable').DataTable().clear().destroy();
                    $('#myTable').DataTable({
                        data: response.data,
                        columns: [{
                                data: 'id',
                                name: 'id'
                            },
                            {
                                data: 'name',
                                name: 'name'
                            },
                            {
                                data: 'email',
                                name: 'email'
                            },
                            {
                                data: 'created_at',
                                name: 'created_at'
                            },
                            {
                                data: 'updated_at',
                                name: 'updated_at'
                            }
                        ]
                    });
                }
            });

        });
        // ******** end of choise wise code ******


        //************** start date wise code *****

        $('.date-wise').on('click', function(event) {
            event.preventDefault();

            var startDate = $('input[name="sdate"]').val();
            var endDate = $('input[name="edate"]').val();

            $('#export-sdate').val(startDate);
            $('#export-edate').val(endDate);

            $.ajax({
                url: "{{ route('get.filtered.users') }}",
                method: 'GET',
                data: {
                    sdate: startDate,
                    edate: endDate
                },
                success: function(response) {
                    // console.log(response);
                    $('#myTable').DataTable().clear().destroy();
                    $('#myTable').DataTable({
                        data: response.data,
                        columns: [{
                                data: 'id',
                                name: 'id'
                            },
                            {
                                data: 'name',
                                name: 'name'
                            },
                            {
                                data: 'email',
                                name: 'email'
                            },
                            {
                                data: 'created_at',
                                name: 'created_at'
                            },
                            {
                                data: 'updated_at',
                                name: 'updated_at'
                            }
                        ]
                    });
                }

            });
        });

        // ******** end of date wise code ******


    });
</script>
<script>
    function showform() {
        // $('#datefetch').toggleClass('d-none');
        $('#datefetch').removeClass('d-none');
        $('#datefetch').addClass('d-block');
    }
</script>

</html>
