
var myDropzone = $("#my-dropzone");

Dropzone.options.myDropzone = {
    paramName: "photo", // The name that will be used to transfer the file
    maxFilesize: 2, // MB
    accept: function(file, done) {
        console.log( file );
        if (file.type == "image/jpeg") {
            done();
        }
        else {
            done("Invalid Extension ! Only jpg allowed ");
        }
    },
    init: function() {
        this.on("success", function(arg, response) {
            console.log( response );
        });
    }
};
