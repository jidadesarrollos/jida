<?php
/**
 * Permite hacer consultas a Bases de datos,
 * Multiples consultas separadas por punto y coma.
 *
 * @author Julio Rodriguez
 * @version 0.1 4/01/2014
 *
 */

?>
<style>

    .consulta {
        width: 100%;
        height: 300px;
        max-width: 100%;
        min-width: 100%;
        min-height: 300px;
        border: #285E8E double;
    }

    tr.active {
        color: #67B168 !important;
    }

    .table-result TH,
    .table-result TD {

        font-size: 12px !important;

    }

</style>
<section class="row">


    <form action="" method="post">
        <?PHP
        if (isset($dataArray['resultQuery'])){
        ?>
        <section class="col-lg-12" id="result">
            <?= $dataArray['resultQuery']; ?>
            </div>
            <?PHP
            }
            ?>
            <div class="col-lg-12">
                <textarea class="consulta" name="consulta" id="consultaBD"></textarea>
            </div>
            <?PHP
            $select = "<select id=\"tablas\" name=\"tablas\">
                  ";
            foreach ($dataArray['tablasBD'] as $key => $tabla) {
                $select .= "<option value=\"$tabla\">$tabla</option>";

            }
            $select .= "</select>";
            ?>
            <div class="col-lg-2 col-lg-offset-5">
                <label for="tablas" class="pull-right">Tablas en BD: </p>
            </div>
            <div class="col-lg-3">
                <?= $select ?>
            </div>
            <div class="col-lg-2">

                <input type="submit" value="Ejecutar Query" name="ejecutarQuery" id="ejecutarQuery" class="pull-right">
            </div>

    </form>
</section>