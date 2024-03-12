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
            $.ajax({
                url:'{{ route("process-register") }}',
                type:'post',
                data:element.serializeArray(),
                dataType:'json',
                success: function(response){
                    if(response["status"] == true) {
                        // window.location.href = '{{ route("categories.index") }}';
                        $("#name").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback').html("");
                        $("#email").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback').html("");
                        $("#phone").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback').html("");
                        $("#password").removeClass('is-invalid')
                            .siblings('p')
                            .removeClass('invalid-feedback').html("");
                    }else{
                        var errors = response['errors'];
                        if (errors['name']) {
                            $("#name").addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback').html(errors['name']);
                        } else {
                            $("#name").removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback').html("");
                        }
                        if (errors['email']) {
                            $("#email").addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback').html(errors['email']);
                        } else {
                            $("#email").removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback').html("");
                        }
                        if (errors['phone']) {
                            $("#phone").addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback').html(errors['phone']);
                        } else {
                            $("#phone").removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback').html("");
                        }
                        if (errors['password']) {
                            $("#password").addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback').html(errors['password']);
                        } else {
                            $("#password").removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback').html("");
                        }
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