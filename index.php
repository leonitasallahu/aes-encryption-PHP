<?php
    require 'crypto.php'; 
    $timer = microtime(true);

    $secretKey = empty($_POST['secretKey']) ? ''  : $_POST['secretKey'];
    $plaintext = empty($_POST['plaintext']) ? '' : $_POST['plaintext'];
    $cipher = empty($_POST['cipher']) ? '' : $_POST['cipher'];
    $plain  = empty($_POST['plain'])  ? '' : $_POST['plain'];

    $encr = empty($_POST['encr']) ? $cipher : Crypto::encrypt($plaintext, $secretKey, 256);
    $decr = empty($_POST['decr']) ? $plain  : Crypto::decrypt($cipher, $secretKey, 256);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <title>AES in PHP</title>
</head>
<body>
<div class="container" style="padding: 7%">
    <div class="row">
        <h3 style="color: #3777DF">Text encryption/decryption using AES-256</h3>
        <form method="post">
            <div class="form-group" style="padding-top: 7%">    

                <table>
                    <tr>
                        <td>Secret key:</td>
                        <td ><input style="margin:10px" class="form-control" type="text" name="secretKey" size="16" value="<?= $secretKey ?>"></td>
                    </tr>
                    <tr>
                        <td>Plaintext:</td>
                        <td><textarea style="margin:10px" class="form-control" rows="4" cols="60" name="plaintext" ><?= htmlspecialchars($plaintext) ?></textarea></td>
                    </tr>
                    <tr>
                        <td><button class="btn btn-primary" type="submit" name="encr" value="Encrypt it">Encrypt</button></td>
                        <td><textarea style="margin:10px" class="form-control" rows="4" cols="60"  name="cipher" size="80" ><?= $encr ?></textarea></td>
                    </tr>
                    <tr>
                        <td><button class="btn btn-danger" type="submit" name="decr" value="Decrypt it">Decrypt</button></td>
                        <td><textarea style="margin:10px" class="form-control" rows="4" cols="60" name="plain" size="40" ><?= htmlspecialchars($decr) ?></textarea></td>
                    </tr>
                </table>
            </div>
        </form>
    </div>
<div>
<p style="color: #3777DF"> Execution time: <?= round(microtime(true) - $timer, 3) ?>s</p>
</body>
</html>