<table class="border: 1px solid black;">
    <thead>
    <tr>
        <th style="text-align: left;">NOME</th>
        <?php if(in_array($formularios[0]['id_formulario'] ?? 0, [1,2,3,4])) {?>
        <th style="text-align: left;">Ilmo(a)</th>
        <?php } ?>
        <th style="text-align: left;">TELEFONE</th>
        <th style="text-align: left;">E-MAIL</th>
        <th style="text-align: left;">CARGO</th>
        <th style="text-align: left;">IDENTIDADE</th>
        <th style="text-align: left;">CPF</th>
        <th style="text-align: left;">UF</th>
        <th style="text-align: left;"><?=utf8_decode("ENDEREÇO")?></th>
        <th style="text-align: left;">ASSUNTO</th>
        <th style="text-align: left;">REQUERIMENTO</th>
        <th style="text-align: left;">FUNDAMENTO</th>
        <th></th>
    </tr>
    </thead>
    <tbody>

    <?php  if(!empty($formularios)){
        $cont=0;
        foreach($formularios AS $formulario){ ?>
            <tr style="font-weight: bold;text-align: left;margin-top: 20px;">
                <td><?=utf8_decode((string)$formulario['nomeCompleto'])?></td>
                <td><?=(string)$formulario['ilmo']?></td>
                <td><?=(string)$formulario['celular']?></td>
                <td><?=(string)$formulario['email']?></td>
                <td><?=(string)$formulario['cargo']?></td>
                <td><?=(string)$formulario['identidade']?></td>
                <td><?=(string)$formulario['CPF']?></td>
                <td><?=(string)$formulario['UF']?></td>
                <td>
                    <?=
                    $formulario['cep']
                    .(empty($formulario['rua']) ? '' : ' - '.utf8_decode($formulario['rua']) )
                    .(empty($formulario['bairro']) ? '' : ' - '.utf8_decode($formulario['bairro']) )
                    .(empty($formulario['cidade']) ? '' : ' - '.utf8_decode($formulario['cidade']) )
                    .(empty($formulario['estado']) ? '' : ' - '.utf8_decode($formulario['estado']) )
                    .(empty($formulario['UFEndereco']) ? '' : ' - '.utf8_decode($formulario['UFEndereco']) )
                    ?>
                </td>
                <td><?=(string)$formulario['assunto']?></td>
                <td><?=(string)$formulario['fundamento']?></td>
                <td><?=(string)$formulario['requerimento']?></td>
            </tr>
            <?php
            $cont++;
            if($cont!=count($formularios)){echo '<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';}
            ?>
        <?php }
    }
    ?>
    </tbody>
</table>
