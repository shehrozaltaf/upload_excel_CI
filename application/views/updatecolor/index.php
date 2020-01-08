<div class="content-wrapper">
    <section class="content">
        <div id="Error" style="display: none"></div>

        <div class="row">

            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Email Upload Form</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form id="UploadPersonFile" method="post" onsubmit="return false" class="uk-form-stacked">
                        <div class="box-body">
                            <div class="form-group">
                                <lable id="ErrorUpload" style="display: none"></lable>
                            </div>
                            <div class="form-group">
                                <label for="Name">Upload Excel File</label>
                                <input type="file" name="FilePath" id="FilePathEdit">
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" id="btn-Add-File" class="btn btn-primary uk-float-right md-btn md-btn-flat md-btn-flat-primary">
                                Submit
                            </button>
                        </div>
                    </form>
                </div>
                <!-- /.box -->
            </div>
        </div>

    </section>
</div>

<script>


    $('#btn-Add-File').click(function(){
        getUploadXLS();
    });

    function getUploadXLS(){
        var formData = new FormData($("#UploadPersonFile")[0]);
        var inputs = $('#EmailFormUpload input');
        CallAjax('<?php echo base_url() . 'index.php/updatecolor/uploadFile' ?>', formData, 'POST', function (Result) {
            console.log(Result);
            if (Result == 1) {
                getColors(1, 'Added', inputs, 'Error');
            }
            else if (Result == 4) {
                getColors(4, 'Already Exist', inputs, 'Error');
            }
            else {
                getColors(5, 'Contain Some Error on Saving', inputs, 'Error');
            }
        },true);
    }



</script>