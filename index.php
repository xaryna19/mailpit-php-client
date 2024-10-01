<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mailpit Client</title>
    <link rel="stylesheet" href="./dist/bootstrap.min.css">
    <link rel="stylesheet" href="./dist/mailpit.client.css">
    <link rel="stylesheet" href="./dist/jquery-confirm.min.css">
    <script src="./dist/bootstrap.bundle.min.js"></script>
    <script src="./dist/jquery.min.js"></script>
    <script src="./dist/htmx.min.js"></script>
    <script src="./dist/jquery-confirm.min.js"></script>
</head>
<body
    hx-get="./ajax.php?action=list"
    hx-target="#email-list"
    hx-swap="innerHTML"
    hx-trigger="load"
>
<main>
    <div id="email-list"></div>
    <?php if( isset($_GET['id']) ) : ?>
        <div id="email-preview" hx-get="./ajax.php?action=view&id=<?= $_GET['id'] ?>" hx-target="#email-preview" hx-swap="innerHTML" hx-trigger="load"></div>
    <?php else : ?>
        <div id="email-preview"></div>
    <?php endif; ?>
</main>
<script>
function deleteAll(){
    $.confirm({
        title: 'Delete all messages?',
        content: 'This will permanently delete all messages',
        icon: 'fa fa-question-circle-o',
        theme: 'supervan',
        closeIcon: true,
        animation: 'scale',
        type: 'red',
        buttons: {
            delete: {
                text: 'Delete',
                btnClass: 'btn-red',
                action: function(){
                    location.href = "./ajax.php?action=delete";
                }
            },
            cancel: {
                text: 'Cancel',
                btnClass: 'btn-blue'
            }
        }
    });
}
    function ChangeUrl(title, url) {
        if (typeof (history.pushState) != "undefined") { var obj = { Title: title, Url: url }; history.pushState(obj, obj.Title, obj.Url);
        } else { alert("Browser does not support HTML5."); }
    }
</script>

</body>
</html>