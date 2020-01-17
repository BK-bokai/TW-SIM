$(document).ready(function () {
  $('.delEva').on('click', function () {
    let url = $(this).attr('url');
    let id = $(this).attr('btnid')

    Swal.fire({
      title: 'Are you sure?',
      text: `確定要刪除此性能評估結果嗎?`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
      if (result.value) {
        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          type: "DELETE",
          url: url,
          dataType: 'json',
          success: function (data) {
            $(`#${id}`).slideUp(300)
            console.log(data);
            console.log("ajax success");
          },
          error: function (xhr, ajaxOptions, thrownError) {
            console.log(xhr.responseText);
          },
        })
        Swal.fire(
          'Deleted!',
          '檔案已刪除',
          'success'
        )
      }
    })

  })
})