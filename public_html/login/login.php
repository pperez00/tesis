<?php
session_start();
unset($_SESSION['id']);
?>

<?php require_once 'form_loguin_register.php'; ?>
<script>
    $("#signup").submit(function(e) {
        e.preventDefault();
        var form = $(this);
        var foto = $('#foto')[0].files;
        var form_jq = $('#signup');

        if (foto.length > 0) {
            $.ajax({
                type: "POST",
                url: 'login/registrarse.php',
                data: new FormData(form_jq[0]),
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    enviar_toast(data);
                    if (data.error == false) {
                        form_jq.trigger('reset');
                    }
                }
            });
        }
    });

    $("#login_form").submit(function(e) {
        e.preventDefault();
        var form = $(this);
        var form_jq = $('#login_form');
        $.ajax({
            type: "POST",
            url: form.attr('action'),
            data: new FormData(form_jq[0]),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                enviar_toast(data);
                if (data.error == false) {
                    setTimeout(() => {
                        window.location = 'index.php';
                    }, 1000);
                }

            }
        });
    });
</script>