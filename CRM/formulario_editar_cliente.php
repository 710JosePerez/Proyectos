<?php
include_once "encabezado.php";
include_once "funciones.php";
$cliente = obtenerClientePorId($_GET["id"]);
?>
<div class="row">
    <div class="col-12">
        <h1>Editar cliente</h1>
        <form action="actualizar_cliente.php" method="post">
            <input type="hidden" name="id" value="<?php echo $cliente->id ?>">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input value="<?php echo $cliente->nombre ?>" required type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre">
            </div>
            <div class="form-group">
                <label for="edad">Edad</label>
                <input value="<?php echo $cliente->edad ?>" required type="number" class="form-control" name="edad" id="edad" placeholder="Edad">
            </div>
            <div class="form-group">
                <label for="departamento">Departamento</label>
                <select class="form-control" name="departamento" id="departamento">
                    <option <?php echo $cliente->departamento ===  "Alta Verapaz" ? "selected" : "" ?> value="Alta Verapaz">Alta Verapaz</option>
                    <option <?php echo $cliente->departamento ===  "Baja Verapaz" ? "selected" : "" ?> value="Baja Verapaz">Baja Verapaz</option>
                    <option <?php echo $cliente->departamento ===  "Chimaltenango" ? "selected" : "" ?> value="Chimaltenango">Chimaltenango</option>
                    <option <?php echo $cliente->departamento ===  "Chiquimula" ? "selected" : "" ?> value="Chiquimula">Chiquimula</option>
                    <option <?php echo $cliente->departamento ===  "Guatemala" ? "selected" : "" ?> value="Guatemala">Guatemala</option>
                    <option <?php echo $cliente->departamento ===  "El Progreso" ? "selected" : "" ?> value="El Progreso">El Progreso</option>
                    <option <?php echo $cliente->departamento ===  "Escuintla" ? "selected" : "" ?> value="Escuintla">Escuintla</option>
                    <option <?php echo $cliente->departamento ===  "Huehuetenango" ? "selected" : "" ?> value="Huehuetenango">Huehuetenango</option>
                    <option <?php echo $cliente->departamento ===  "Izabal" ? "selected" : "" ?> value="Izabal">Izabal</option>
                    <option <?php echo $cliente->departamento ===  "Jalapa" ? "selected" : "" ?> value="Jalapa">Jalapa</option>
                    <option <?php echo $cliente->departamento ===  "Jutiapa" ? "selected" : "" ?> value="Jutiapa">Jutiapa</option>
                    <option <?php echo $cliente->departamento ===  "Petén" ? "selected" : "" ?> value="Petén">Petén</option>
                    <option <?php echo $cliente->departamento ===  "Quetzaltenango" ? "selected" : "" ?> value="Quetzaltenango">Quetzaltenango</option>
                    <option <?php echo $cliente->departamento ===  "Quiché" ? "selected" : "" ?> value="Quiché">Quiché</option>
                    <option <?php echo $cliente->departamento ===  "Retalhuleu" ? "selected" : "" ?> value="Retalhuleu">Retalhuleu</option>
                    <option <?php echo $cliente->departamento ===  "Sacatepéquez" ? "selected" : "" ?> value="Sacatepéquez">Sacatepéquez</option>
                    <option <?php echo $cliente->departamento ===  "San Marcos" ? "selected" : "" ?> value="San Marcos">San Marcos</option>
                    <option <?php echo $cliente->departamento ===  "Santa Rosa" ? "selected" : "" ?> value="Santa Rosa">Santa Rosa</option>
                    <option <?php echo $cliente->departamento ===  "Sololá" ? "selected" : "" ?> value="Sololá">Sololá</option>
                    <option <?php echo $cliente->departamento ===  "Suchitepéquez" ? "selected" : "" ?> value="Suchitepéquez">Suchitepéquez</option>
                    <option <?php echo $cliente->departamento ===  "Totonicapán" ? "selected" : "" ?> value="Totonicapán">Totonicapán</option>
                    <option <?php echo $cliente->departamento ===  "Zacapa" ? "selected" : "" ?> value="Zacapa">Zacapa</option>
                </select>
            </div>
            <div class="form-group">
                <button class="btn btn-success">Guardar</button>
            </div>
        </form>
    </div>
</div>
<?php include_once "pie.php" ?>