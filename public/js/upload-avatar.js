var removebutton=$("#buttonremove");
var myDropzone = $("#my-dropzone");

Dropzone.options.myDropzone = {
    paramName: "photo", // The name that will be used to transfer the file
    maxFilesize: 2, // MB
   // maxFiles: 1,    dictDefaultMessage: '',
    createImageThumbnails: false,
   previewTemplate: '<div id="errordisplay" class="dz-error-message dzpreviewerror"></div>',
    //previewTemplate: '<span></span>',
  //addRemoveLinks: true,
    uploadMultiple:false,
/*    canceled: function(file) {
        return this.emit("error", file, "");
    },*/


    accept:
    function(file, done) {
        if ((file.type == "image/jpeg") || (file.type == "image/png") || (file.type == "image/gif")) {
            done();
        }
        else {
            done("Invalid Extension ! Only jpg, png or gif files format are allowed");
        }
    },


    init: function() {

        this.on('removedfile', function(file,response){
  //         this.enable();
            $("#buttonremove").css("display", "none");
            $("#imagebutton").attr("src","/images/add-user.jpg");
//            $('#droparea').attr('title', 'Click or drag here to add picture');


        });


        this.on("success", function(file,result) {
            newfile=file;
        //    this.disable();

            $("#buttonremove").css("display", "inline");
            lastfile=$("#imagebutton").attr("src");

            $("#imagebutton").attr("src","/photos/"+newfile.name);
            $.ajax({url:"/upload/deltempfile?file="+lastfile,success:function(result){
                $("#photoup").val(null);
            }});


            var _this = this;
            $("#photoup").val(newfile.name);
            removebutton.click(function(){

                _this.removeFile(newfile);
                $("#buttonremove").css("display", "none");
                $.ajax({url:"/upload/deltempfile?file="+newfile.name,success:function(result){
                    $("#photoup").val("");
                }});
            })

        });
        this.on("error",function(file,error){
        $('#errordisplay').html(error);
        $('#errordisplay').fadeIn(2000).delay(3000).fadeOut(2000);
        });
        //load photo from database
        thisDropzone = this;
        $.get('/upload/get-photo?id='+$("#idphoto").val(), function(foto) {

              if (foto.size>0){
/*
               var mockFile = { name: foto.name, size: foto.size };


               thisDropzone.options.addedfile.call(thisDropzone, mockFile);

               thisDropzone.options.thumbnail.call(thisDropzone, mockFile, "/photos/"+foto.name);*/
               var timeseed = new Date().getTime();  //forcing photo from cache to get refreshed from server
               $("#imagebutton").attr("src","/photos/"+foto.name+"?"+timeseed);
               $("#buttonremove").css("display", "inline");
               $("#photoup").val(foto.name);

              }
        });

         removebutton.click(function(){
             photo=$("#imagebutton").attr("src");
             $("#imagebutton").attr("src","/images/add-user.jpg");
             $("#buttonremove").css("display", "none");

             $("#photoup").val("");

             })
         }

}
