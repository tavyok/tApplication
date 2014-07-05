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
        var files=new Array();
        var newupload=true;

        this.on("addedfile", function(file) {
           var _this = this;

           if (newupload)
           {
               while(myfiles.length > 0) {
                   myfiles.pop();
               }
            //    files=null;
       //        alert(files.length);
/*              files.forEach(function(value,index,arg){
               alert(value.name);
                     //_this.removeFile(value);
                  });
               while(files.length > 0) {
                  files.pop();
               }*/

           }

            myfiles.push(file.name);

            // Create the remove button
            var removeButton = Dropzone.createElement("<IMG class='dropic' title='click to remove picture' SRC='/images/remove1.png'> ");

            var framephoto=Dropzone.createElement("<DIV class='dropzoneframe'></DIV> ");

            newupload=false;
            $("#uploadbutton").show();
            $("#uploadbutton").click(function(){
               _this.processFile(file);


            });
            $("#resetbutton").show();
            $("#resetbutton").click(function(){
               _this.removeAllFiles();

            });

            // Listen to the click event
            removeButton.addEventListener("click", function(e) {
                // Make sure the button click doesn't submit the form:
                e.preventDefault();
                e.stopPropagation();

                // Remove the file preview.

                file_to_remove=myfiles.indexOf(file.name);

                myfiles.splice(file_to_remove,1);

                _this.removeFile(file);

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
            files.push(file);
            for (i=0;i<myfiles.length;i++)
            {

                $("#realfiles").eq(i).val(myfiles[i]);
            }
         //   alert($("#realfiles").val);
            $('#uploaddone').html(uploads+"<BR>"+rejects);

            //    $('#uploaddone').fadeIn(2000).delay(5000).fadeOut(2000);

           //     ("#droparea").slideUp()

           // this.removeFile(file);

        });

        this.on("error",function(file,error){
        rejects.push(file.name+" - rejected - "+error+"<BR>");
        files.push(file);
        });


    }

}
function listAllProperties(o){
    var objectToInspect;
    var result = [];

    for(objectToInspect = o; objectToInspect !== null; objectToInspect = Object.getPrototypeOf(objectToInspect)){
        result = result.concat(Object.getOwnPropertyNames(objectToInspect));
    }

    return result;
}