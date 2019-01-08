<h1>单图上传</h1>
//单图上传
<form enctype="multipart/form-data" id="form1">
    <input type="file" id="avatar" name="Upload[imageFile]">
    <input type="hidden" name="type_id" value="1">
    <input type="hidden" name="type_model" value="api\modules\v1\models\user\Member">
    <button type="button" id="dan">保存</button>
</form>
<h1>多图上传</h1>
<form enctype="multipart/form-data" id="form2">
    <input type="file" id="avatar" name="Upload[imageFiles][]" multiple>
    <input type="hidden" name="type_id" value="1">
    <input type="hidden" name="type_model" value="api\modules\v1\models\user\Member">
    <button id="duo" type="button">保存</button>
</form>

<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>

<script src="https://cdn.bootcss.com/jquery.form/4.2.2/jquery.form.js"></script>
<script>
    
    $('#duo').click(function(){
        var formdata = new FormData($('#form2')[0]);
        $.ajax({
            url: '/upload/photo',
            type: 'POST',
            datatype: 'json',
            data: formdata,
            cache:false,
            traditional: true,
            contentType: false,
            processData: false,
            success: function (data) {},
            error: function () {}
        });
    });
    //单图上传
    $('#dan').click(function(){
        var formdata = new FormData($('#form1')[0]);
        $.ajax({
            url: '/upload/avatar',
            type: 'POST',
            datatype: 'json',
            data: formdata,
            cache:false,
            traditional: true,
            contentType: false,
            processData: false,
            success: function (data) {},
            error: function () {}
        });
    });
</script>
