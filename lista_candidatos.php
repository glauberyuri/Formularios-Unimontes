<?php
    $connect = new PDO("mysql:host=localhost;dbname=formularios_db", "root", "");
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    include('./config/function.php');

        $id = $_POST['selectedOption'];
        $query = '';
        $output = array();
        $query .= "SELECT * FROM tb_formularios  WHERE id_formConfig = $id";

        if (isset($_POST["search"]["value"])) {
            $searchValue = $_POST["search"]["value"];
            $query .= ' AND (nomeCompleto LIKE "%' . $searchValue . '%"';
            $query .= ' OR email LIKE "%' . $searchValue . '%"';
            $query .= ' OR CPF LIKE "%' . $searchValue . '%"';
            $query .= ' OR cargo LIKE "%' . $searchValue . '%")';
        }

        if(isset($_POST["order"])) {
            $query .= ' ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
        } else {
            $query .= ' ORDER BY id_formulario ASC';
        }

        if ($_POST["length"] != -1) {
            $query .= ' LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
        }

    $statement = $connect->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();
    $data = array();
    $filtered_rows = $statement->rowCount();
    foreach ($result as $row)
    {
        $sub_array = array();

        $sub_array[] = $row["cargo"];
        $sub_array[] = $row["nomeCompleto"];
        $sub_array[] = $row["email"];
        $sub_array[] = $row["identidade"];
        $sub_array[] = $row["CPF"];
        $sub_array[] = $row["celular"];
        $sub_array[] = '<button type="button" name="documents" id="'.$row["id_formulario"].'" class="btn btn-primary btn-sm view">&nbsp<i class="bi bi-search"></i>Documentos</button>';

        $data[] = $sub_array;
    }

    $output = array(
        "draw" => intval($_POST["draw"]),
        "recordsTotal" => $filtered_rows,
        "recordsFiltered" => get_total_all_records(),
        "data" => $data
    );

    echo json_encode($output);
?>
