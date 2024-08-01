<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Reset Password Email</title>
</head>

<body>
   <p> Hello {{ $formData['user']->name }}</p>
   <h1 class="text-center">You Have Requested To Change Password!</h1>
   <p>Please Click The Below Link To Change Password!</p>
   <a href="{{ route( 'account.reset-password',$formData['token'] ) }}">Click Here!</a>

   <p>Thanks!!</p>

</body>

</html>