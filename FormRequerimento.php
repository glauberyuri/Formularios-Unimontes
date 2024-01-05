<?php
    if(isset($_POST['submit'])){

    $ilmo_name = $_POST["ilmo_name"];
    $assunto = $_POST["assunto"];
    $cargo = $_POST["inlineRadioOptions"];
    switch ($cargo)
    {
        case '1': // 1 checkbox
            $cargo = "Coordendador";
            break;
        case '2': // 2 checkbox
            $cargo = "Secretaria";
            break;
        case '3': // 3 checkbox
            $cargo= "Docente";
            break;
        default: // if 1 2 3 is both checked
    }
    $nomeCompleto = $_POST["nomeCompleto"];
    $email = $_POST["email"];
    $identidade = $_POST["identidade"];
    $CPFCode = $_POST["CPFCode"];
    $UFCode = $_POST["UFCode"];
    $endereco = $_POST["endereco"];
    $bairro = $_POST["bairro"];
    $cidade = $_POST["cidade"];
    $cepcode = $_POST["cepcode"];
    $ufendereco = $_POST["ufendereco"];
    $telefone_celular = $_POST["telefone_celular"];
    $telefone = $_POST["telefone"];
    $requerVA = $_POST["requerVA"];
    $fundamentos = $_POST["fundamentos"];
    $id_form = intval($_POST["id_form"]);
    $terms = filter_input(INPUT_POST, "terms", FILTER_VALIDATE_BOOLEAN);
        $host = 'localhost';
        $dbname = 'formularios_db';
        $user = 'root';
        $password = '';

        $connect = mysqli_connect($host, $user, $password, $dbname);

    $sql = "INSERT INTO tb_formularios (ilmo, assunto, cargo, nomeCompleto, email, identidade, CPF, UF, rua, bairro, cidade, cep, UFEndereco,celular,telefone,requerimento,fundamento,id_formConfig) VALUES ('$ilmo_name', '$assunto', '$cargo','$nomeCompleto','$email','$identidade','$CPFCode','$UFCode','$endereco','$bairro','$cidade','$cepcode','$ufendereco','$telefone_celular','$telefone','$requerVA','$fundamentos', 1)";
    $save = mysqli_query($connect, $sql);

    $idForm = mysqli_insert_id($connect);
    if($save){

        //Upload arquivos formularios
        if(isset($_FILES["file"])) {

            $files = $_FILES["file"];
            $names = $files["name"];
            $tmp_name = $files["tmp_name"];

            // SEMPRE QUE ALTERAR CAMINHO ONDE SERA SALVO OS PDFS DO FORMULARIO PARA A PASTA EXPECIFICA
            $pasta = "uploads/formularioRequerimento/";

            foreach ($names as $index => $name){
                $extension = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                $newname= uniqid().'.'.$extension;

                if($extension != "jpg" && $extension != "png" && $extension != "pdf")
                    die("Tipo de arquivo não aceito");

                $path = $pasta . $newname;
                $deu_certo = move_uploaded_file($tmp_name[$index], $path);

                if($deu_certo) {

                    $sqlUpload = "INSERT INTO tb_uploads (name, path, id_formulario) VALUES ('$newname', '$path', '$idForm')";

                    $saveUpload = mysqli_query($connect, $sqlUpload);
                    header( "Location: finalizado.php");


                }
            }
        }


    }
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="FormsCSS.css" rel="stylesheet" >
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
<div  class="h-100 mx-auto p-5 pt-5">
    <div class="card border px-2">
        <div class="card-body ">
            <h5 class="card-title">Formuários</h5>
            <h6 class="card-subtitle mb-2 text-body-secondary">PPGCS</h6>
            <div class="container p-4">
                <!-- Stack the columns on mobile by making one full-width and the other half-width -->
                <form action="FormRequerimento.php" method="post" class="needs-validation"  enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-12 col-md-4 pb-2">
                            <label for="ilmo_name" class="form-label">Ilmo.(a).Sr.(a)</label>
                            <input type="text" class="form-control" id="ilmo_name" name="ilmo_name" placeholder="" required>
                        </div>
                        <div class="invalid-tooltip">
                            Please choose a username.
                        </div>
                        <div class="col-12 col-md-8 pt-5 gap-3">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input border border-primary" type="radio" name="inlineRadioOptions" id="inlineRadioOptions" value="1">
                                <label class="form-check-label" for="inlineRadioOptions">Coordenador</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input border border-primary" type="radio" name="inlineRadioOptions" id="inlineRadioOptions" value="2">
                                <label class="form-check-label" for="inlineRadioOptions">Secretaria</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input border border-primary" type="radio" name="inlineRadioOptions" id="inlineRadioOptions" value="3">
                                <label class="form-check-label" for="inlineRadioOptions">Docente</label>
                            </div>
                        </div>
                    </div>
                    <div class="row pt-2">
                        <div class="col-12 col-md-4">
                            <label for="assunto" class="form-label">Assunto</label>
                            <textarea class="form-control" id="assunto" name="assunto" rows="3" required></textarea>
                        </div>
                        <div class="invalid-feedback">
                            Please choose a username.
                        </div>
                    </div>
                    <hr>
                    <dt class="col-sm-3 pt-3">Dados do solicitante</dt>
                    <!-- Columns start at 50% wide on mobile and bump up to 33.3% wide on desktop -->
                    <div class="row pt-4 gap-3 d-flex justify-content-between">
                        <div class="col-12 col-md-4">
                                <label for="nomeCompleto" class="form-label">Nome Completo</label>
                                <input type="text" class="form-control" id="nomeCompleto" name="nomeCompleto" placeholder="" required>
                        </div>
                        <div class="invalid-feedback">
                            Please choose a username.
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="email" class="form-label">Email</label>
                            <input type="text" class="form-control" id="email" name="email" placeholder="" required>
                        </div>
                        <div class="invalid-feedback">
                            Please choose a username.
                        </div>
                    </div>
                    <div class="row pt-3 pb-3">
                        <div class="col-12 col-md-4">
                            <label for="identidade" class="form-label">C. Identidade</label>
                            <input type="text" class="form-control" id="identidade" name="identidade" placeholder="" required>
                        </div>
                        <div class="invalid-feedback">
                            Please choose a username.
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="CPFCode" class="form-label">CPF</label>
                            <input type="text" class="form-control" id="CPFCode" name="CPFCode" placeholder="" required>
                        </div>
                        <div class="invalid-feedback">
                            Please choose a username.
                        </div>
                        <div class="col-12 col-md-1">
                            <label for="UFCode" class="form-label">UF</label>
                            <input type="text" class="form-control" id="UFCode" name="UFCode" placeholder="" required>
                        </div>
                        <div class="invalid-feedback">
                            Please choose a username.
                        </div>
                    </div>
                    <hr>
                    <dt class="col-sm-3 pt-3">Endereço</dt>
                    <div class="row pt-3">
                        <div class="col-12 col-md-3">
                            <label for="Endereco" class="form-label">Rua/Av</label>
                            <input type="text" class="form-control" id="Endereco" name="endereco" placeholder="" required>
                        </div>
                        <div class="invalid-feedback">
                            Please choose a username.
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="Bairro" class="form-label">Bairro</label>
                            <input type="text" class="form-control" id="Bairro" name="bairro" placeholder="" required>
                        </div>
                        <div class="invalid-feedback">
                            Please choose a username.
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="Cidade" class="form-label">Cidade</label>
                            <input type="text" class="form-control" id="Cidade" name="cidade" placeholder="" required>
                        </div>
                        <div class="invalid-feedback">
                            Please choose a username.
                        </div>
                        <div class="col-12 col-md-2">
                            <label for="CEPCode" class="form-label">CEP</label>
                            <input type="text" class="form-control" id="CEPCode" name="cepcode" placeholder="" required>
                        </div>
                        <div class="invalid-feedback">
                            Please choose a username.
                        </div>
                        <div class="col-6 col-md-1">
                            <label for="UFCodeEnd" class="form-label">UF</label>
                            <input type="text" class="form-control" id="UFCodeEnd" name="ufendereco" placeholder="" required>
                        </div>
                        <div class="invalid-feedback">
                            Please choose a username.
                        </div>
                    </div>
                    <!-- Columns are always 50% wide, on mobile and desktop -->
                    <div class="row pt-3">
                        <div class="col-4">
                            <label for="Telefone-Celular" class="form-label">Celular</label>
                            <input type="text" class="form-control" id="Telefone-Celular" name="telefone_celular" placeholder="" required>
                        </div>
                        <div class="invalid-feedback">
                            Please choose a username.
                        </div>
                        <div class="col-4">
                            <label for="Telefone" class="form-label">Telefone</label>
                            <input type="text" class="form-control" id="Telefone" name="telefone" placeholder="" required>
                        </div>
                    </div>
                    <div class="invalid-feedback">
                        Please choose a username.
                    </div>
                    <hr>
                    <div class="row pt-3 gap-3">
                        <div class="col-12 col-md-6">
                            <label for="requerVA" class="form-label">Vem Requerer de V. Sa</label>
                            <textarea class="form-control" id="requerVA" name="requerVA" rows="3" required></textarea>
                        </div>
                        <div class="invalid-feedback">
                            Please choose a username.
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="fundamentos" class="form-label">Fundamentando-se nas seguintes Razões</label>
                            <textarea class="form-control" id="fundamentos" name="fundamentos" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="invalid-feedback">
                        Please choose a username.
                    </div>
                    <div class="invisible">
                        <label for="id_form" class="form-label">Telefone</label>
                        <input type="text" class="form-control" id="id_form" name="id_form" value="1" placeholder="">
                    </div>
                    <div class="mb-3 pt-3">
                        <label for="formFile" class="form-label">Anexar Arquivo</label>
                        <input class="form-control" type="file" id="formFile" name="file[]" required>
                    </div>
                    <div class="invalid-feedback">
                        Please choose a username.
                    </div>
                    <div class="form-check form-check-inline pt-3">
                        <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                        <label class="form-check-label" for="terms">Declaro que as informações acima prestadas são verdadeiras, assumo a inteira responsabilidade pelas mesmas<span class="text-danger" >*</span></label>
                    </div>
                    <div class="invalid-feedback">
                        Please choose a username.
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button class="btn btn-primary me-md-2" type="submit" name="submit" id="cadastrar">Enviar</button>
                        <button class="btn btn-danger" type="button">Voltar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>