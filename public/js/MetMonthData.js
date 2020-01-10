
$(document).ready(function () {
  $('.delData').on('click', function (e) {
    e.preventDefault();
    let date = $(this).attr('datatime');
    let dataType = $(this).attr('datatype');
    let url = $(this).attr('url');
    Swal.fire({
      title: 'Are you sure?',
      text: `確定要刪除日期為: ${date} 的 ${dataType}`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
      if (result.value) {
        $("tr[date=" + date + "data]").slideUp(300)
        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          type: "DELETE",
          url: url,
          data: {
          },
          dataType: 'json',
          success: function (data) {
            console.log(data);
            console.log("ajax success");
          }
        })
        Swal.fire(
          'Deleted!',
          '檔案已刪除',
          'success'
        )
      }
    })

  })

  // $("form.del_img").on("submit", function() {
  //   return confirm("Are you sure?");
  // });

  $("button.delete").on('click', function (e) {
    e.preventDefault();
    location.reload();
  })

  $("button.del_img").on('click', function (e) {
    e.preventDefault();
    var form = $(this).parents().parents('form');
    // form.submit();
    // var c = confirm("你確定要刪除此項片嗎?")
    swal({
      title: "Are you sure?",
      text: "你確定要刪除此項片嗎?",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: "確認刪除",
      cancelButtonText: "取消此動作",
      closeOnConfirm: false,
    },
      function (isConfirm) {
        if (isConfirm) form.submit();
      });
  })

  $($(".img_box input[type=radio]")).on('change', function () {
    // alert($(this).attr('data-id'));
    // alert($("input[data-id=" + $(this).attr('data-id') + "]:checked").val())
    let url = $(this).attr('url');
    alert(url)
    var c = confirm("你確定要更改此設定嗎");
    if (c) {
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "PUT",
        // url: '{{url("admin/img")}}'+ '/' + $(this).attr('data-id'),
        url: url,
        data: {
          id: $(this).attr('data-id'),
          publish: $("input[data-id=" + $(this).attr('data-id') + "]:checked").val()
        },
        dataType: 'html',
        success: function (data) {
          console.log(data);
          console.log("ajax success");
        }
      })
    }
    else {
      location.reload();
    }

  });
})