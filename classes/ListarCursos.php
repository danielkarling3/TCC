<?php
include_once '../classes/Disciplina.php';
include_once '../classes/BD/crudPDO.php';
include_once '../classes/Curso.php';

class ListarCursos {

    private $cursos = array();

    public function __construct() {
        //session_start();
        $id_usuario = $_SESSION["usuario"]['id'];

//        $fetch = selecionarWHERE("curso", array("id", "codigo", "nome", "semanas"), "id_usuario = $id_usuario");
        //novo select, seleciona os cursos compartilhados contigo
        $fetch = selecionarWHERE("curso left join compartilhado on curso.id = compartilhado.id_curso ", array("curso.id", "curso.codigo", "curso.nome", "curso.semanas"), "curso.id_usuario = $id_usuario OR compartilhado.id_compartilhado = $id_usuario");
        //novo select, seleciona os cursos compartilhados contigo

        foreach ($fetch as $linha) {
            $disc = new Curso();
            $disc->curso($linha["nome"], $linha["codigo"], $linha["id"], $linha["semanas"]);
            $this->cursos[] = $disc;
        }
    }

    public function listar() {
        ?>
        <center>            
            <table class="table table-hover">
                <tr class = "text-center bg-info2">
                    <th class="text-center ">Código</th><th class="text-center">Compartilhar</th><th class="text-center">Alterar</th><th class="text-center">Nome</th>
                </tr>
                <?php
                foreach ($this->getCursos() as $curso) {
                    $nome = $curso->getNome();
                    $codigo = $curso->getCodigo();
                    echo "<tr class='text-center bg-info2'>"
                    . "<td>" . $curso->getCodigo() . "</td>"
                    . "<td><button onclick='compartilhar(" . $curso->getId() . ")'>Compartilhar<button></td>"
                    . "<td class='text-warning'><button onclick='alterarCurso(" . $curso->getId() . ")'> ALTERAR</button></td>"
                    //. "<td class='text-warning'><a href='formCurso.php?idCurso=" . $curso->getId() . "'> Alterar</a></td>"
                    . "<td class='text-warning'><a href='listarDisciplinas.php?codigo=" . $codigo . "'>" . $nome . "</td>"
                    . "</tr>";
                }
                ?>
            </table>           
        </center>
        <?php
    }

    function getCursos() {
        return $this->cursos;
    }

}
