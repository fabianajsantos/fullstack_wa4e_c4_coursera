<!doctype html>
<html>
<head>
</head>
<body>
<script>
    $(document).ready(function (){
        $.post('view.php', function (retorna){
            $("#positions").(retorna);
        })
    })
</script>

</body>
</html>