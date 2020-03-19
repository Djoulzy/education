window.ajaxDataLoader = function(url, dataType, method, postVal) {
    var host = document.location.protocol+"//"+document.location.hostname
    if (document.location.port != 0) host += ":"+document.location.port
    host += "/" + url

    // let encoded_postVal = null
    // if (postVal) encoded_postVal = { 'values': JSON.stringify(postVal) }

    return $.ajax({
        url : host,
        type : method,
        data: postVal,
        async: true,
        dataType : dataType,
    })
    .fail(function(data) { console.log("-- Error -- url: ", url) })
}