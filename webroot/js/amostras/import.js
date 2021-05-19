$(window).scroll(function (event) {
    var scroll = $(window).scrollTop();
    if (scroll > 40) {
        $(".dz-details").css("z-index", "0");
        $(".dz-image").css("z-index", "0");
        $(".dz-error-message").css("z-index", "0");
        $(".dz-error-mark").css("z-index", "0");
    } else {
        $(".dz-details").css("z-index", "20");
        $(".dz-image").css("z-index", "10");
        $(".dz-error-message").css("z-index", "1000");
        $(".dz-error-mark").css("z-index", "500");
    }
});

var qtd_files = 0;
var qtd_files_removed = 0;
// var configAssinto = "";

function submitForm() {
    Swal.fire({
        title: "Enviando Dados",
        html: "Por favor aguarde, estamos processando os dados",
        timer: "",
        onBeforeOpen: function () {
            Swal.showLoading();
        },
        onClose: function () {},
    }).then(function (result) {
        if (
            // Read more about handling dismissals
            result.dismiss === Swal.DismissReason.timer
        ) {
            console.log("I was closed by the timer");
        }
    });



    $("#sendData").submit();
}

function amountForm(file) {
    file = JSON.parse(file);
    console.log(file);

    var html = "";

    html += '<tr id="' + file.amostra_id + '">';
    html += '<th scope="row">' + file.amostra_id + " ";
    html +=
        '<input class="form-control" type="hidden" name="amostraid[]" value="' +
        file.amostra_id +
        '" />';

    html += "</th>";

    html +=
        '<td><input type="hidden" name="sintomatico[]" value="0"><input type="checkbox" onclick="changeValue(this)">';

        // <input style="width: 60px;" type="button" value="Não" name="sintomatico[]" onClick="changeValue(this)" class="form-control"/></td>

    $("#input-files").append(html);

    qtd_files++;
    console.log(qtd_files);
    $("#total-files").val(qtd_files);
}

function removeTr(fileId) {
    var newFileId = fileId.split(".")[0];
    $("#" + newFileId).remove();
    qtd_files_removed++;
    $("#files-removed").val(qtd_files_removed);
}

Dropzone.autoDiscover = false;
Dropzone.prototype.defaultOptions.dictRemoveFile = "Remover";
Dropzone.prototype.defaultOptions.dictDefaultMessage =
    "Drop files here to upload";
Dropzone.prototype.defaultOptions.dictFallbackMessage =
    "Your browser does not support drag'n'drop file uploads.";
Dropzone.prototype.defaultOptions.dictFallbackText =
    "Please use the fallback form below to upload your files like in the olden days.";
Dropzone.prototype.defaultOptions.dictFileTooBig =
    "File is too big ({{filesize}}MiB). Max filesize: {{maxFilesize}}MiB.";
Dropzone.prototype.defaultOptions.dictInvalidFileType =
    "You can't upload files of this type.";
Dropzone.prototype.defaultOptions.dictResponseError =
    "Server responded with {{statusCode}} code.";
Dropzone.prototype.defaultOptions.dictCancelUpload = "Cancelar";
Dropzone.prototype.defaultOptions.dictCancelUploadConfirmation =
    "Are you sure you want to cancel this upload?";
Dropzone.prototype.defaultOptions.dictMaxFilesExceeded =
    "You can not upload any more files.";

$(document).ready(function () {
    // configAssinto = $("#config-assinto").find(":selected").val();
    // $("#config-assinto").change(function () {
    //     configAssinto = $(this).val();
    //     console.log(configAssinto);
    // });
    execFormDrop();

});

function changeValue(obj) {

    var curr = $(obj).prev();
    if($(obj).is(':checked')){
        curr.val('1')
    }else{
        curr.val('0')
    }
}
function execFormDrop() {
    $("#formFiles").dropzone({
        maxFiles: 2000,
        addRemoveLinks: true,
        // params: function params(files, xhr, chunk) {
        //     return { config_assinto: configAssinto };
        // },
        url: BASE_URL_ADMIN + "amostras/import?qtd_files=" + qtd_files,
        accept: function (file, done) {
            let fileExt = file.name.split(".");
            let ext = fileExt[fileExt.length - 1];
            if (
                ext !== "txt" &&
                ext !== "csv" &&
                ext !== "xls" &&
                ext !== "xlsx"
            ) {
                $(file.previewElement)
                    .addClass("dz-error")
                    .find(".dz-error-message")
                    .text("Arquivo inválido");
                done("Arquivo inválido");
            } else {
                done();
            }
        },
        success: function (file, response) {
            amountForm(response);
        },
        error: function (file, message) {
            console.log(message);
            $(file.previewElement)
                .addClass("dz-error")
                .find(".dz-error-message")
                .text(message.message);
        },
        removedfile: function (file) {
            removeTr(file.name);
            file.previewElement.remove();
        },
    });
}
