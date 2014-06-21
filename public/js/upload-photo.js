var removebutton=$("#buttonremove");
var myDropzone = $("#my-dropzone");

Dropzone.options.myDropzone = {
    paramName: "photo", // The name that will be used to transfer the file
    maxFilesize: 3,// MB
    createImageThumbnails: true,
//    previewTemplate: '<div id="errordisplay" class="dz-error-message dzpreviewerror"></div>',
    //previewTemplate: '<span></span>',
  //  addRemoveLinks: true,
    uploadMultiple:true,
    thumbnailWidth: 190,
    thumbnailHeight: 190,
/*    canceled: function(file) {
        return this.emit("error", file, "");
    },*/


    accept:
    function(file, done) {

        if ((file.type == "image/jpeg") || (file.type == "image/png") || (file.type == "image/gif")) {
            done();
        }
        else {
            done("Invalid extension for file! Only jpg, png or gif files format are allowed");
        }
  /*      if (file.size<=2)
        {
            done();
        }
        else {
            done("File "+file.name+" rejected (exceeds 2 MBytes)");
        }*/

    },


    init: function() {
        var errors=[];
        this.on('removedfile', function(file,response){
  //         this.enable();
            $("#buttonremove").css("display", "none");
            $("#imagebutton").attr("src","/images/add-user.jpg");
//            $('#droparea').attr('title', 'Click or drag here to add picture');


        });


        this.on("addedfile", function(file) {

            // Create the remove button
            var removeButton = Dropzone.createElement("<IMG class='dropic' title='click to remove picture' SRC='/images/remove1.png'> ");


            // Capture the Dropzone instance as closure.
            var _this = this;

            // Listen to the click event
            removeButton.addEventListener("click", function(e) {
                // Make sure the button click doesn't submit the form:
                e.preventDefault();
                e.stopPropagation();

                // Remove the file preview.
                _this.removeFile(file);
                // If you want to the delete the file on the server as well,
                // you can do the AJAX request here.
            });

            // Add the button to the file preview element.
            file.previewElement.appendChild(removeButton);
        });

/*        this.on("success", function(file,result) {
            newfile=file;

            $("#photoup").val(newfile.name);

        });*/
        this.on("error",function(file,error){
        errors.push(file.name+" - "+error);

        });

 /*       this.on("queuecomplete",function(file){

        for (i=0;i<errors.length;i++)
        {
 //       alert(errors[i]);
        $('#errordisplay').html(errors[i]);
        $('#errordisplay').fadeIn(2000).delay(2000).fadeOut(2000);



        }
        });
        //load photo from database
        thisDropzone = this;
        $.get('/upload/get-photo?id='+$("#idphoto").val(), function(foto) {

              if (foto.size>0){
*//*
               var mockFile = { name: foto.name, size: foto.size };


               thisDropzone.options.addedfile.call(thisDropzone, mockFile);

               thisDropzone.options.thumbnail.call(thisDropzone, mockFile, "/photos/"+foto.name);*//*
               var timeseed = new Date().getTime();  //forcing photo from cache to get refreshed from server
               $("#imagebutton").attr("src","/photos/"+foto.name+"?"+timeseed);
               $("#buttonremove").css("display", "inline");
               $("#photoup").val(foto.name);

              }
        });
*/
         }

}
