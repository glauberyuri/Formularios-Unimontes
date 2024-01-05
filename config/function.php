<?php

    $host = 'localhost';
    $dbname = 'formularios_db';
    $user = 'root';
    $password = '';

    $connect = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    function get_total_all_records()
    {
    global $connect;

    $statement = $connect->prepare("SELECT * FROM tb_formularios");
    $statement->execute();

    $result = $statement->fetchAll();

    return $statement->rowCount();

    }

    function getFormularioToExport($id){

        global $connect;

        $statement = $connect->prepare("SELECT tb_formularios.*, tb_config_formularios.* 
                               FROM tb_formularios
                               INNER JOIN tb_config_formularios ON tb_formularios.id_formConfig = tb_config_formularios.id_formConfig
                               WHERE tb_formularios.id_formConfig = :id");
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    function retornaJson ($retorno, $bolEncode) {
        if ($bolEncode) {
            if(getValueHeaderActive('Content-Type') === FALSE){
                header('Content-Type: application/json');
            }
            echo json_encode($retorno, TRUE);
        } else {
            echo $retorno;
        }

        exit(0);
    }

    function getValueHeaderActive($headerName, $url = null){
        if(empty($headerName)) return false;
        $headerName = removerEspacosExtras(strtolower($headerName), FALSE, TRUE);

        $dados = array();
        if(!empty($url)) $dados = get_headers(retiraBarraString($url));
        else             $dados = headers_list();

        $itemHeader = array();
        $aux = array();
        foreach ($dados AS $itemHeader){
            list($aux[0], $aux[1]) = explode(':', $itemHeader, 2);
            if(
                removerEspacosExtras(strtolower($aux[0]), FALSE, TRUE) !==
                $headerName
            ) continue;

            return $aux[1];
        }
        unset($aux);
        unset($itemHeader);

        return false;
    }

    function removerEspacosExtras($str, $manterUmEspaco = TRUE, $removerTagLinhaHTML = FALSE){
        if(empty($str)) return "";
        return trim(preg_replace(getRegexSplitEspacos($removerTagLinhaHTML), (!empty($manterUmEspaco) ? " " : ""), $str));
    }

    function getRegexSplitEspacos($removerTagLinhaHTML = FALSE){
        $removerTagLinhaHTML = !empty($removerTagLinhaHTML);

        $strSplit = '([\r\n|\s|\t|\n|\r|\s])';
        if($removerTagLinhaHTML) $strSplit .= '|(<br\s*\/?>)';
        $strSplit = '/' . $strSplit . '/i';

        return $strSplit;
    }

    /**
     * Method retiraBarraString.
     * Retira barra final de strings
     * @param string $str Define a string para tirar a barra do final
     * @return bool|string
     */
    function retiraBarraString($str){
        $str = trim($str);
        while (substr($str, -1) == '/' || substr($str, -1) == '\\') $str = substr($str, 0, -1);

        return $str;
    }
?>