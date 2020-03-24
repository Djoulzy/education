window.step = 0
window.nb_Quest = 0
window.results = null
window.submit_ok = false
window.runlink = ''
window.redirect = ''

window.setProgress = function() {
    init()
    for(var i=1; i< step; i++) {
        if(results[i] == true) {
            $("#prog_"+i).addClass("active_ok")
            $("#prog_"+i).html('<i class="fas fa-check" style="font-size: 20px;"></i>')
        }
        else if(results[i] == false) {
            $("#prog_"+i).addClass("active_nok")
            $("#prog_"+i).html('<i class="fas fa-times" style="font-size: 20px;"></i>')
        }
    }

    $("#prog_"+step).addClass("active")
    $("#prog_"+step).html('<i class="fas fa-arrow-up" style="font-size: 20px;"></i>')

    if (step > nb_Quest) {
        submit_ok = true
        $("#next").removeClass("btn-danger")
        $("#next").addClass("btn-info")
        $("#next").html("Terminé ! Voir la correction ...")
    }
}

$( document ).ready(function() {

    $(".reponses").on('change', function() {
        submit_ok = true
        $(".reponses").each(function() {
            if ($(this).val() == '') submit_ok = false
        })
        if (submit_ok) {
            $("#next").removeClass("btn-danger")
            $("#next").addClass("btn-success")
            $("#next").html("Suivant")
        }
    })

    $("#next").click(function() {
        console.log(submit_ok)
        if (!submit_ok) return

        if (step > nb_Quest) {
            window.location.href = redirect
        }
        else {
            $.when(ajaxDataLoader(runlink, 'json', 'POST', getPostVal())).done(function(data) {
                console.log(data.step)
                updateQuestion(data)
                step = data.step
                results = data.results
                setProgress()
            })
        }
    })
});

