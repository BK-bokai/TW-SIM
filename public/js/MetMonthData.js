
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
        let text = $("#dataInfo").text();
        let num = parseInt(text.match(/\d+/));
        $("#dataInfo").text("目前僅有" + String(num - 1) + "筆資料")
        $("#dataInfo").attr('class', 'red');
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

  $('#checkAll').on('change', function () {

    let objs = $("input[type='checkbox']");
    for (var i = 0; i < objs.length; i++) {
      objs[i].checked = this.checked;
    }
    console.log($("input[type='checkbox']"))

  })

  $("input[type='checkbox']").on('change',function(){
    if($('input[type="checkbox"]:checked').length >= 1)
    {
      $('.checkbtn').removeClass('disabled');
    }
    else
    {
      $('.checkbtn').addClass('disabled');
    }
  })


 

  $('.checkbtn').on('click', function (e) {
    e.preventDefault();
    let url = $(this).attr('url');
    let form = $('#allForm');
    let method = $(this).attr('method');
    form.attr('action',url);
    if( method == 'delete' )
    {
      Swal.fire({
        title: 'Are you sure?',
        text: `確定要刪除這些檔案`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.value) {
          Swal.fire(
            'Deleted!',
            '檔案已刪除',
            'success'
          )
          form.submit();
        }
      })
    }
    else
    {
      form.submit();
      setTimeout(function(){
        window.location.reload();
      },700)
    }
  })

})