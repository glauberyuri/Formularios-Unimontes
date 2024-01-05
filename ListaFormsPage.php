<?php
    include 'config/function.php';

    $connect = new PDO("mysql:host=localhost;dbname=formularios_db", "root", "");
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $queryConfigForm= "SELECT * FROM tb_config_formularios ORDER BY create_date DESC LIMIT 10";
    $resultConfig = $connect->query($queryConfigForm);

    $queryListaUploads = "SELECT * FROM tb_uploads";

    $listaUploads = $connect->prepare($queryListaUploads);

    // verifica se esta passando id formulario para gerar relatorio
    if (isset($_GET["id"])) {
        $id = $_GET["id"];
        export($id);
    }
    // Sua função export
    function export($id) {
        $idEmpresa = $id;
        $formularios = getFormularioToExport($idEmpresa);
        $ret = include("config/export.php");
        header("Pragma: public");
        header("Expires: 0"); // set expiration time
        header("Cache-Control: no-cache");
        header('Content-Disposition: attachment; filename="'.($formularios[0]['name'] ?? '').date('d-m-Y').'.xls"');
        header('Content-Type: application/vnd.ms-excel; charset=utf-8'); # Don't use application/force-download - it's not a real MIME type, and the Content-Disposition header is sufficient
        header('Content-Length: ' . strlen($ret));
        header('Connection: close');

        retornaJson($ret, 0);

    }
?>
<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lista Formulários</title>
    <link href="FormsCSS.css" rel="stylesheet" >
    <link rel="stylesheet" href="https://unpkg.com/@jarstone/dselect/dist/css/dselect.css">
    <script src="https://unpkg.com/@jarstone/dselect/dist/js/dselect.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
<!-- O Modal -->
<div class="modal fade" id="model_pdf">
    <div class="modal-dialog modal-lg"> <!-- Adicione a classe modal-lg para tornar o modal maior -->
        <div class="modal-content">
            <!-- Cabeçalho do modal -->
            <div class="modal-header">
                <h4 class="modal-title">Tabela de Documentos</h4>
            </div>

            <!-- Conteúdo do modal -->
            <div class="modal-body">
                <div class="table-responsive"> <!-- Adicione a classe table-responsive para tabelas com rolagem -->
                    <table class="table table-bordered" id="tabela_upload">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome do Arquivo</th>
                            <th>Data</th>
                            <th>Ações</th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- Aqui serão adicionadas as linhas da tabela com JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Rodapé do modal -->
            <div class="modal-footer">
                <button type="button" class="btn btn-primary"  data-bs-dismiss="modal">Fechar</button>
            </div>

        </div>
    </div>
</div>
<!-- As a heading -->
<header class="py-3 mb-3 border-bottom">
    <div class="container-fluid d-grid gap-2 align-items-center" style="grid-template-columns: 1fr 2fr;">
        <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-dark text-decoration-none">
            <span class="fs-4">PPGCS</span>
        </a>
    </div>
</header>
<div class="container-fluid pb-3">
    <div class="d-grid gap-3" style="grid-template-columns: 1fr 2fr;">
        <div class="bg-light border rounded-3">
            <div class="bg-light border rounded-3">
                <div class="container-fluid text-center">
                    <div class="row mb-3">
                        <div class="col-12 text-light bg-dark text-center rounded-top">
                            <h4 class="">Formulários</h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <p class="fs-4">Pesquisar</p>
                        </div>
                        <div class="col-12 mb-4">
                            <select name="select-box" class="form-select" id="select_box" >
                                <?php
                                foreach ($resultConfig as $row)
                                {
                                    echo '<option value="'.$row["id_formConfig"].'">'.$row["name"].'</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-12 ">
                            <table class="table table-hover rounded-top">
                                <thead class="table-dark">
                                <tr>
                                    <th scope="col">Nome</th>
                                    <th scope="col">Data</th>
                                    <th scope="col">Exportar</th>
                                </tr>
                                </thead>
                                <tbody class="text-start">
                                <?php
                                // After fetching all rows, reset the statement pointer
                                $resultConfig->execute();

                                foreach ($resultConfig as $form_data) {
                                    // Calculate the number of days since creation
                                    $createDate = strtotime($form_data['create_date']);
                                    $currentDate = time();
                                    $daysSinceCreation = floor(($currentDate - $createDate) / (60 * 60 * 24));

                                    // Calculate the date one month from the creation date
                                    $oneMonthLater = date('Y-m-d H:i:s', strtotime('+1 month', $createDate));



                                    echo "<tr>";
                                    echo    "<td>".$form_data['name']."</td>";
                                    if ($currentDate > $oneMonthLater) {
                                        echo    "<td>".date('d-m-Y', $oneMonthLater)."</td>"; // Display date one month later
                                    } else {
                                        echo    "<td>".$daysSinceCreation." dias atrás</td>"; // Display days since creation
                                        // Display an empty cell if one month hasn't passed
                                    }
                                    echo "<td>  <a type='button'class='btn btn-success export' href='ListaFormsPage.php?id=".$form_data['id_formConfig']."'>
                                                    <svg xmlns='http://www.w3.org/2000/svg' style='color:white' height='1em' viewBox='0 0 576 512'><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d='M0 64C0 28.7 28.7 0 64 0H224V128c0 17.7 14.3 32 32 32H384V288H216c-13.3 0-24 10.7-24 24s10.7 24 24 24H384V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V64zM384 336V288H494.1l-39-39c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l80 80c9.4 9.4 9.4 24.6 0 33.9l-80 80c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l39-39H384zm0-208H256V0L384 128z ' fill='white'/></svg>
                                                    Excel
                                                </a>
                                          </td>";
                                    echo "</tr>";
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-light border rounded-3">
            <div class="container">
                <div class="row mb-3">
                    <div class="col-12 text-light bg-dark text-center rounded-top">
                        <h4 class="">Candidatos</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 ">
                        <table id="lista-candidatos" class="display compact nowrap rounded-top">
                                <thead >
                                        <th>Cargo</th>
                                        <th>Nome Completo</th>
                                        <th>Email</th>
                                        <th>Identidade</th>
                                        <th>CPF</th>
                                        <th>Celular</th>
                                        <th>Ações</th>
                                </thead>
                                <tbody>
                                </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="js/dselect.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script type="text/javascript" language="javascript">
    //Datatables
    $(document).ready(function() {
        var dataTable = $('#lista-candidatos').DataTable({
            "paging": true,
            "language": {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json',
            },
            "processing": true,
            "serverSide": true,
            "order": [],
            "info": true,
            "ajax": {
                url: "lista_candidatos.php",
                type: "POST",
                data: function(d) {
                    d.selectedOption = $('#select_box').val(); // Add the selected value as a parameter called "selectedOption"
                }
            },
            "columnDefs": [
                {
                    "targets": [0, 3, 4],
                    className: "dt-body-left",
                    "orderable": false
                }
            ]
        });
        $('#select_box').on('change', function() {
            dataTable.ajax.reload(); // Reload DataTables data with the new selected value
        });
    });
    //Vizualizar PDFS
    $(document).on('click', '.view', function (){
        var id_formulario = $(this).attr("id");
        $.ajax({
            url: "config/get_uploads.php",
            method: "POST",
            data: {id_formulario: id_formulario},
            dataType: "json",
            success: function (data) {
                // Limpar o corpo da tabela antes de adicionar novos dados
                $("#tabela_upload tbody").empty();
                $("#model_pdf").modal("show");
                    // Loop através dos dados e adicionar cada linha à tabela
                    $.each(data, function(index, item) {
                        var row = "<tr>";
                        row += "<td>" + item.id_upload + "</td>";
                        row += "<td>" + item.name + "</td>";
                        row += "<td>" + item.data_upload + "</td>";
                        row += "<td>" + item.action + "</td>";
                        row += "</tr>";
                        $("#tabela_upload tbody").append(row);
                    });
            }
        })
    });
    //Search
    dselect(document.querySelector('#select_box'), {
        search: true
    });
</script>
</body>
</html>