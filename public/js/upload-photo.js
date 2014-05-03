var removebutton=$("#buttonremove");
var myDropzone = $("#my-dropzone");

Dropzone.options.myDropzone = {
    paramName: "photo", // The name that will be used to transfer the file
    maxFilesize: 2, // MB
    maxFiles: 1,
  //addRemoveLinks: true,
    uploadMultiple:false,
    canceled: function(file) {
        return this.emit("error", file, "");
    },
 //   maxFiles:1,
    accept:
    function(file, done) {
        console.log( file );
        if ((file.type == "image/jpeg") || (file.type == "image/png")) {

            done();

        }
        else {
            done("Invalid Extension ! Only jpg or png allowed ");
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
            this.disable();
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

    }
}






