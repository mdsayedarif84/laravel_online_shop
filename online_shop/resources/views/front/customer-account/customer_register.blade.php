@extends('front.home')
@section('title')
Register
@endsection
@section('body')
    <style>
        .text-success {
        color: #28a745;
    }
    .text-danger {
        color: #dc3545;
    }
    </style>
    <main>
        <section class="section-5 pt-3 pb-3 mb-3 bg-white">
            <div class="container">
                <div class="light-font">
                    <ol class="breadcrumb primary-color mb-0">
                        <li class="breadcrumb-item"><a class="white-text" href="#">Home</a></li>
                        <li class="breadcrumb-item">Register</li>
                    </ol>
                </div>
            </div>
        </section>

        <section class=" section-10">
            <div class="container">
                <div class="login-form">    
                    <form action="" name="registationForm" id="registationForm" method="post">
                        <h4 class="modal-title">Register Now</h4>
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Name" id="name" name="name">
                            <p></p>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Email" id="email" name="email">
                            <p></p>
                        </div>
                        <div class="form-group">
                            <input type="text" onkeyup="numberCheck();" class="form-control" placeholder="Phone" id="phone" name="phone">
                            <p></p>
                            <div class="nbrCheckAlert" id="nbrCheckAlert"></div>
                        </div>
                        <div class="form-group">
                            <input type="password" id="password" name="password" class="form-control" placeholder="Password" >
                            <p></p>
                        </div>
                        <div class="form-group">
                            <input type="password" id="password_confirmation" name="password_confirmation" onkeyup="checkPasswordMatch();" class="form-control" placeholder="Confirm Password" >
                            <p></p>
                            <div class="registrationFormAlert" id="divCheckPasswordMatch"></div>
                        </div>
                        <div class="form-group small">
                            <a href="#" class="forgot-link">Forgot Password?</a>
                        </div> 
                        <button type="submit" class="btn btn-dark btn-block btn-lg" value="Register">Register</button>
                    </form>			
                    <div class="text-center small">Already have an account? <a href="{{route('login')}}">Login Now</a></div>
                </div>
            </div>
        </section>
    </main>
@endsection
@section('customJs')
    <script type="text/javascript">
        $('#registationForm').submit(function(event){
            event.preventDefault();
            var element = $(this);
            $('button[type="submit"]').prop('disabled',true);
            $.ajax({
                url:'{{ route("process-register") }}',
                type:'post',
                data:element.serializeArray(),
                dataType:'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response){
                    $('button[type="submit"]').prop('disabled',false);

                    var errors   =   response.errors;
                    if(response.status == false){
                        if(errors.name){
                            $("#name").siblings('p').addClass('invalid-feedback').html(errors.name);
                            $("#name").addClass('is-invalid');
                        }else{
                            $("#name").siblings('p').removeClass('is-invalid').html('');
                            $("#name").removeClass('is-invalid');
                        }
                        if(errors.email){
                            $("#email").siblings('p').addClass('invalid-feedback').html(errors.email);
                            $("#email").addClass('is-invalid');
                        }else{
                            $("#email").siblings('p').removeClass('is-invalid').html('');
                            $("#email").removeClass('is-invalid');
                        }
                        if(errors.phone){
                            $("#phone").siblings('p').addClass('invalid-feedback').html(errors.phone);
                            $("#phone").addClass('is-invalid');
                        }else{
                            $("#phone").siblings('p').removeClass('is-invalid').html('');
                            $("#phone").removeClass('is-invalid');
                        }
                        if(errors.password){
                            $("#password").siblings('p').addClass('invalid-feedback').html(errors.password);
                            $("#password").addClass('is-invalid');
                        }else{
                            $("#password").siblings('p').removeClass('is-invalid').html('');
                            $("#password").removeClass('is-invalid');
                        }
                    }else{
                        $("#name").siblings('p').removeClass('is-invalid').html('');
                        $("#name").removeClass('is-invalid');

                        $("#email").siblings('p').removeClass('is-invalid').html('');
                        $("#email").removeClass('is-invalid');

                        $("#phone").siblings('p').removeClass('is-invalid').html('');
                        $("#phone").removeClass('is-invalid');

                        $("#password").siblings('p').removeClass('is-invalid').html('');
                        $("#password").removeClass('is-invalid');

                        window.location.href="{{ route('login') }}";
                    }
                },
                error: function(jqXHR, exception){
                console.log("Something Went Wrong!");
                }
            })
        });
    </script>
    <script>
       function checkPasswordMatch() {
        var password = $("#password").val();
        var confirmPassword = $("#password_confirmation").val();

        if (password != confirmPassword)
            $("#divCheckPasswordMatch").html("Passwords do not match!").addClass('text-danger').removeClass('text-success');

        else
            $("#divCheckPasswordMatch").html("Passwords match.").addClass('text-success').removeClass('text-danger');
    }
    function numberCheck(){
        var inputNbr    =   $("#phone").val();
        if (inputNbr.length != 11 ) 
            $("#nbrCheckAlert").html("Nbr Only 11 Digit!").addClass('text-danger').removeClass('text-success');
        else
            $("#nbrCheckAlert").html("Nbr 11 Digit Done.").addClass('text-success').removeClass('text-danger');
    }
</script> 
@endsection