@extends('admin.dashboard.dashboard')
@section('title')
Create Category
@endsection
@section('body')

@endsection
@section('customJs')
<script>
$("#categoryForm").submit(function(event) {
   event.preventDefault();
   var element = $(this);
   // $("button[type=submit)]").prop('disabled',true);
   $.ajax({
      url: '{{route("categories.store")}}',
      type: 'POST',
      data: element.serializeArray(),
      dataType: 'json',
      success: function(response) {
         // $("button[type=submit)]").prop('disabled',false);
         if (response["status"] == true) {
            window.location.href = '{{ route("categories.index") }}';
            $("#name").removeClass('is-invalid')
               .siblings('p')
               .removeClass('invalid-feedback').html("");

            $("#slug").removeClass('is-invalid')
               .siblings('p')
               .removeClass('invalid-feedback').html("");
         } else {
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
            if (errors['slug']) {
               $("#slug").addClass('is-invalid')
                  .siblings('p')
                  .addClass('invalid-feedback').html(errors['slug']);
            } else {
               $("#slug").removeClass('is-invalid')
                  .siblings('p')
                  .removeClass('invalid-feedback').html("");
            }
         }

      },
      error: function(jqXHR, exception) {
         console.log("Something Went Wrong!");
      }
   })
});

$("#name").change(function() {
   element = $(this);
   // $("button[type=submit)]").prop('disabled',true);
   $.ajax({
      url: '{{ route("getSlug") }}',
      type: 'get',
      data: {
         title: element.val()
      },
      dataType: 'json',
      success: function(response) {
         // $("button[type=submit)]").prop('disabled',false);
         if (response["status"] == true) {
            $("#slug").val(response["slug"]);
         }
      }
   });
});

Dropzone.autoDiscover = false;
const dropzone = $("#image").dropzone({
   init: function() {
      this.on('addedfile', function(file) {
         if (this.files.length > 1) {
            this.removeFile(this.files[0]);
         }
      });
   },
   url: "{{ route('temp-images.create') }}",
   maxFiles: 1,
   paramName: 'image',
   addRemoveLinks: true,
   acceptedFiles: "image/jpeg,image/png,image/gif",
   headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
   },
   success: function(file, response) {
      $("#image_id").val(response.image_id);
      // console.log(response)
   }
});
</script>
@endsection