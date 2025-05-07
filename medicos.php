<?php include 'buscar_medico.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lista de Médicos - Clínica Salud y Bienestar</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="d-flex flex-column min-vh-100">

<header class="bg-primary text-white text-center py-3">
  <h1>Clínica Salud y Bienestar</h1>
  <p>Lista de Médicos Disponibles</p>
</header>
 <!-- Menú de navegación -->
 <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.html">Inicio</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="loginpaciente.html"><i class="fas fa-user-injured"></i> Pacientes</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="medicos.php"><i class="fas fa-user-md"></i> Médicos</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#servicios"><i class="fas fa-microscope"></i> Análisis</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#turnos"><i class="far fa-pen-to-square"></i> Turnos</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#contacto"><i class="fas fa-envelope"></i> Contacto</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="medicos_registro.html"><i class="fas fa-user-plus"></i> Registrar Médicos</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
<div class="container my-4">
  <form method="GET" class="mb-4">
    <div class="row g-2 align-items-end">
      <div class="col-md-6">
        <label for="especialidad" class="form-label">Filtrar por Especialidad:</label>
        <select name="especialidad" id="especialidad" class="form-select">
          <option value="">-- Todas --</option>
          <?php foreach ($especialidades as $esp): ?>
            <option value="<?= $esp['id'] ?>" <?= $filtro == $esp['id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($esp['especialidad']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-2">
        <button type="submit" class="btn btn-primary w-100">Filtrar</button>
      </div>
    </div>
  </form>

  <?php if ($medicos): ?>
    <div class="row">
      <?php foreach ($medicos as $medico): ?>
        <div class="col-md-3 mb-4">
          <a href="medico_info.php?id=<?= $medico['id'] ?>" class="btn btn-outline-primary w-100 py-3 text-start">
            <i class="fas fa-user-md me-2"></i>
            <?= htmlspecialchars($medico['nombre']) ?><br>
            <small class="text-muted"><?= htmlspecialchars($medico['especialidad']) ?></small>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <div class="alert alert-info text-center">No hay médicos para esta especialidad.</div>
  <?php endif; ?>
</div>

<footer class="bg-dark text-white text-center py-3 mt-auto">
  <p>&copy; 2025 Clínica Salud y Bienestar</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


