var removebutton=$("#buttonremove");
var myDropzone = $("#my-dropzone");

Dropzone.options.myDropzone = {
    paramName: "photo", // The name that will be used to transfer the file
    maxFilesize: 3,// MB
    createImageThumbnails: true,
//    previewTemplate: '<div id="errordisplay" class="dz-error-message dzpreviewerror"></div>',
    //previewTemplate: '<span></span>',
  //  addRemoveLinks: true,

    uploadMultiple:false,
    autoProcessQueue:false,
    processingmultiple:false,
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


    },


    init: function() {
        var newfile;
        var rejects=[];
        var uploads=[];
        var myfiles=[];
        dzone=true;

        var newupload=true;

//        this.params = { comm: 'New Files' };

        this.on("addedfile", function(file) {
           var _this = this;

           if (newupload)
           {
               while(myfiles.length > 0) {
                   myfiles.pop();
               }

           }

            myfiles.push(file.name);

            $("#realfiles").val(JSON.stringify(myfiles));

            // Create the remove button
            var removeButton = Dropzone.createElement("<IMG class='dropic' title='click to remove picture' SRC='/images/remove1.png'> ");
            var framephoto=Dropzone.createElement("<DIV class='dropzoneframe'></DIV> ");

            newupload=false;
            //upload button click
            $("#uploadbutton").show();
            $("#uploadbutton").click(function(){
               _this.processFile(file);
            });

            //reset button click
            $("#resetbutton").show();
            $("#resetbutton").click(function(){
                while(myfiles.length > 0) {
                    myfiles.pop();
                }
               _this.removeAllFiles();
                $("#realfiles").val(JSON.stringify(myfiles));

            });

            // Listen to the click event
            removeButton.addEventListener("click", function(e) {
                // Make sure the button click doesn't submit the form:
                e.preventDefault();
                e.stopPropagation();

                // Remove the file preview.

                var file_to_remove=myfiles.indexOf(file.name);

                myfiles.splice(file_to_remove,1);


                _this.removeFile(file);

                $("#realfiles").val(JSON.stringify(myfiles));

                // If you want to the delete the file on the server as well,
                // you can do the AJAX request here.
            });

            // Add the button to the file preview element.
            file.previewElement.appendChild(removeButton);
            file.previewElement.appendChild(framephoto);

        });
        this.on("success", function(file,result) {

            newupload=true;
            uploads.push(file.name+" - uploaded"+"<BR>");

/*            for (i=0;i<myfiles.length;i++)
            {

                $("#realfiles").eq(i).val(myfiles[i]);
            }
      */
            $('#uploaddone').html(uploads+"<BR>"+rejects);

            //    $('#uploaddone').fadeIn(2000).delay(5000).fadeOut(2000);

           //     ("#droparea").slideUp()

           // this.removeFile(file);

        });

        this.on("error",function(file,error){
        rejects.push(file.name+" - rejected - "+error+"<BR>");

        });

    }
};





function listAllProperties(o){
    var objectToInspect;
    var result = [];

    for(objectToInspect = o; objectToInspect !== null; objectToInspect = Object.getPrototypeOf(objectToInspect)){
        result = result.concat(Object.getOwnPropertyNames(objectToInspect));
    }

    return result;
}