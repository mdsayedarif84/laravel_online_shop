<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Contact Email</title>
</head>

<body>
   <h1 class="text-center">You Have Received A Contact Mail!</h1>
   <p class="text-center">Name: {{$mailData['name']}}</p>
   <p class="text-center">Email: {{$mailData['email']}}</p>
   <p class="text-center">Subject: {{$mailData['subject']}}</p>

   <p class="text-center">Email:</p>
   <p class="text-center">{{$mailData['message']}}</p>

</body>

</html>