<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Login-Google</title>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6 justify-content-center mt-5 shadow py-5 px-5">
                <form action="{{ route('chklogin') }}" method="POST">
                    <div class="mb-3">
                        <a href="{{ route('AddUser') }}" class="btn btn-success">Add User</a>
                    </div>
                    @csrf
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Email address</label>
                        <input type="email" name="email" class="form-control" id="exampleInputEmail1" required>
                        <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" id="exampleInputPassword1">
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary p-2 px-5 mt-3">Login</button>

                        <div class="line d-flex align-items-center mt-2">
                            <hr class="flex-grow-1 mx-2" style="border-top: 1px solid #000;">
                            <strong class="mx-2">Or</strong>
                            <hr class="flex-grow-1 mx-2" style="border-top: 1px solid #000;">
                        </div>

                        <a href="{{ route('google-auth') }}" class="btn btn-link fs-5">Google Account</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
