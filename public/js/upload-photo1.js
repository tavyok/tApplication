var removebutton=$("#buttonremove");
var myDropzone = $("#my-dropzone");

Dropzone.options.myDropzone = {
    paramName: "photo", // The name that will be used to transfer the file
    maxFilesize: 2, // MB
    maxFiles: 1,
  //  dictDefaultMessage: '',
  //  createImageThumbnails: false,
  //  previewTemplate: '<span></span>',
  //addRemoveLinks: true,
    uploadMultiple:false,
    canceled: function(file) {
        return this.emit("error", file, "");
    },

    accept:
    function(file, done) {
        console.log( file );
        if ((file.type == "image/jpeg") || (file.type == "image/png") || (file.type == "image/gif")) {

            done();

        }
        else {
            done("Invalid Extension ! Only jpg, png or gif files format are allowed");
        }
    },

    init: function() {

        this.on('removedfile', function(file,response){
           this.enable();
            $("#buttonremove").css("display", "none");
            $('#droparea').attr('title', 'Click or drag here to add picture');


        });


        this.on("success", function(file, response) {
            newfile=file;
        //    this.disable();
            $("#buttonremove").css("display", "inline");
            $('#droparea').attr('title', 'Remove file first to add another picture');
            var _this = this;
            $("#photoup").val(newfile.name);
            removebutton.click(function(){

                _this.removeFile(newfile);

                $.ajax({url:"/upload/deltempfile?file="+newfile.name,success:function(result){
                    $("#photoup").val(null);
                }});
            })

        });

        thisDropzone = this;
        $.get('/upload/get-photo?id='+$("#idphoto").val(), function(data) {

           $.each(data, function(key,value){
              if (value.size>0)
              {
               var mockFile = { name: value.name, size: value.size };

                alert(value.name);

               thisDropzone.options.addedfile.call(thisDropzone, mockFile);

               thisDropzone.options.thumbnail.call(thisDropzone, mockFile, "/photos/"+value.name);

              }
            });
        })
    }

}






